<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollItem;
use App\Models\PayrollPeriod;
use App\Models\SalaryComponent;
use App\Services\PayrollCalculator;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function __construct(protected PayrollCalculator $payrollCalculator)
    {
        $this->authorizeResource(PayrollPeriod::class, 'payroll');
    }

    public function index(Request $request): JsonResponse|View
    {
        $payrolls = PayrollPeriod::with(['creator', 'approver'])
            ->withCount('items')
            ->when($request->filled('year'), fn ($query) => $query->forYear($request->integer('year')))
            ->latest('period_start')
            ->paginate($request->integer('per_page', 12));

        if (! $request->expectsJson()) {
            return view('payrolls.index', [
                'payrolls' => $payrolls,
                'years' => PayrollPeriod::query()
                    ->orderBy('period_start')
                    ->get(['period_start'])
                    ->map(fn (PayrollPeriod $period) => optional($period->period_start)->format('Y'))
                    ->filter()
                    ->unique()
                    ->values(),
            ]);
        }

        return response()->json($payrolls);
    }

    public function create(Request $request): JsonResponse|View
    {
        $data = [
            'employees' => Employee::with('user')->active()->orderBy('employee_code')->get(),
            'salaryComponents' => SalaryComponent::active()->orderBy('type')->orderBy('name')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('payrolls.create', $data);
        }

        return response()->json($data);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $this->authorize('generate', PayrollPeriod::class);

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
            'payout_date' => ['nullable', 'date', 'after_or_equal:period_end'],
            'notes' => ['nullable', 'string'],
            'employee_ids' => ['nullable', 'array'],
            'employee_ids.*' => ['exists:employees,id'],
        ]);

        $payroll = PayrollPeriod::create([
            'name' => $data['name'] ?: 'Payroll ' . Carbon::parse($data['period_start'])->format('F Y'),
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'payout_date' => $data['payout_date'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        $employees = Employee::with(['user', 'salaryComponents.salaryComponent'])
            ->active()
            ->when(! empty($data['employee_ids']), fn ($query) => $query->whereIn('id', $data['employee_ids']))
            ->get();

        $payroll = $this->payrollCalculator->generate($payroll, $employees);

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.payrolls.show', $payroll)
                ->with('status', 'Payroll generated successfully.');
        }

        return response()->json($payroll, 201);
    }

    public function show(Request $request, PayrollPeriod $payroll): JsonResponse|View
    {
        $payroll->load(['items.employee.user', 'creator', 'approver']);

        if (! $request->expectsJson()) {
            return view('payrolls.show', compact('payroll'));
        }

        return response()->json($payroll);
    }

    public function edit(Request $request, PayrollPeriod $payroll): JsonResponse|View
    {
        $data = [
            'payroll' => $payroll,
            'employees' => Employee::with('user')->active()->orderBy('employee_code')->get(),
        ];

        if (! $request->expectsJson()) {
            return view('payrolls.edit', $data);
        }

        return response()->json($data);
    }

    public function update(Request $request, PayrollPeriod $payroll): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
            'payout_date' => ['nullable', 'date', 'after_or_equal:period_end'],
            'notes' => ['nullable', 'string'],
        ]);

        $payroll->update($data);

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.payrolls.show', $payroll)
                ->with('status', 'Payroll details updated successfully.');
        }

        return response()->json($payroll->fresh(['items.employee.user', 'creator', 'approver']));
    }

    public function destroy(Request $request, PayrollPeriod $payroll): JsonResponse|RedirectResponse
    {
        $payroll->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.payrolls.index')
                ->with('status', 'Payroll deleted successfully.');
        }

        return response()->json(['message' => 'Payroll deleted successfully.']);
    }

    public function submitForReview(Request $request, PayrollPeriod $payroll): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $payroll);

        $payroll->update(['status' => 'review']);
        $payroll->items()->update(['status' => 'review']);

        if (! $request->expectsJson()) {
            return back()->with('status', 'Payroll moved to review.');
        }

        return response()->json($payroll->fresh());
    }

    public function approve(Request $request, PayrollPeriod $payroll): JsonResponse|RedirectResponse
    {
        $this->authorize('approve', $payroll);

        $payroll->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        $payroll->items()->update(['status' => 'approved']);

        if (! $request->expectsJson()) {
            return back()->with('status', 'Payroll approved successfully.');
        }

        return response()->json($payroll->fresh(['approver']));
    }

    public function bankTransfer(PayrollPeriod $payroll)
    {
        $this->authorize('view', $payroll);

        $rows = $payroll->items()->with('employee.user')->orderBy('id')->get();

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Employee Code', 'Employee Name', 'Bank Name', 'Account Number', 'IFSC', 'Net Salary']);
            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->employee?->employee_code,
                    $row->employee?->user?->name,
                    $row->bank_name,
                    $row->bank_account_number,
                    $row->ifsc_code,
                    $row->net_salary,
                ]);
            }
            fclose($handle);
        }, 'payroll-bank-transfer-' . $payroll->id . '.csv');
    }

    public function payslip(Request $request, PayrollPeriod $payroll, PayrollItem $payrollItem): JsonResponse|View
    {
        $this->authorize('view', $payroll);

        abort_unless($payrollItem->payroll_period_id === $payroll->id, 404);

        $payrollItem->load('employee.user');

        if (! $request->expectsJson()) {
            return view('payrolls.payslip', compact('payroll', 'payrollItem'));
        }

        return response()->json($payrollItem);
    }
}
