<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        $attendance = Attendance::with(['employeeUser', 'creator'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->whereHas('employeeUser', fn ($userQuery) => $userQuery
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"));
            })
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->integer('user_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('month'), function ($query) use ($request) {
                [$year, $month] = explode('-', $request->string('month')->toString());
                $query->forMonth((int) $year, (int) $month);
            })
            ->latest('date')
            ->paginate($request->integer('per_page', 31));

        if (! $request->expectsJson()) {
            return view('attendances.index', [
                'attendances' => $attendance,
                'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
                'statuses' => ['Present', 'Absent', 'Late', 'Half Day', 'Leave'],
            ]);
        }

        return response()->json($attendance);
    }

    public function create(Request $request): JsonResponse|View
    {
        $data = [
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
            'statuses' => ['Present', 'Absent', 'Late', 'Half Day', 'Leave'],
        ];

        if (! $request->expectsJson()) {
            return view('attendances.create', $data);
        }

        return response()->json($data);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validateAttendance($request);
        $data['created_by'] = auth()->id();

        $attendance = new Attendance($data);
        $attendance->calculateHours();
        $attendance->save();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.attendances.show', $attendance)
                ->with('status', 'Attendance saved successfully.');
        }

        return response()->json($attendance->load(['employeeUser', 'creator']), 201);
    }

    public function show(Request $request, Attendance $attendance): JsonResponse|View
    {
        $attendance->load(['employeeUser', 'creator']);

        if (! $request->expectsJson()) {
            return view('attendances.show', compact('attendance'));
        }

        return response()->json($attendance);
    }

    public function edit(Request $request, Attendance $attendance): JsonResponse|View
    {
        $data = [
            'attendance' => $attendance->load(['employeeUser', 'creator']),
            'users' => User::query()->select('id', 'name')->orderBy('name')->get(),
            'statuses' => ['Present', 'Absent', 'Late', 'Half Day', 'Leave'],
        ];

        if (! $request->expectsJson()) {
            return view('attendances.edit', $data);
        }

        return response()->json($data);
    }

    public function update(Request $request, Attendance $attendance): JsonResponse|RedirectResponse
    {
        $data = $this->validateAttendance($request, $attendance->id);
        $attendance->fill($data);
        $attendance->calculateHours();
        $attendance->save();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.attendances.show', $attendance)
                ->with('status', 'Attendance updated successfully.');
        }

        return response()->json($attendance->fresh()->load(['employeeUser', 'creator']));
    }

    public function destroy(Request $request, Attendance $attendance): JsonResponse|RedirectResponse
    {
        $attendance->delete();

        if (! $request->expectsJson()) {
            return redirect()
                ->route('hrms.attendances.index')
                ->with('status', 'Attendance record archived successfully.');
        }

        return response()->json(['message' => 'Attendance deleted successfully.']);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls'],
        ]);

        $rows = $this->parseTabularFile($request->file('file'));
        $imported = 0;

        foreach ($rows as $row) {
            if (empty($row['user_id']) || empty($row['date'])) {
                continue;
            }

            $attendance = Attendance::firstOrNew([
                'user_id' => $row['user_id'],
                'date' => $row['date'],
            ]);

            $attendance->fill([
                'check_in' => $row['check_in'] ?? null,
                'check_out' => $row['check_out'] ?? null,
                'status' => $row['status'] ?? 'Present',
                'notes' => $row['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
            $attendance->calculateHours();
            $attendance->save();
            $imported++;
        }

        return response()->json(['message' => 'Attendance imported successfully.', 'imported' => $imported]);
    }

    public function export(Request $request)
    {
        $rows = Attendance::with('employeeUser')
            ->when($request->filled('month'), function ($query) use ($request) {
                [$year, $month] = explode('-', $request->string('month')->toString());
                $query->forMonth((int) $year, (int) $month);
            })
            ->orderBy('date')
            ->get()
            ->map(fn (Attendance $attendance) => [
                'employee' => $attendance->employeeUser?->name,
                'date' => optional($attendance->date)->format('Y-m-d'),
                'check_in' => $attendance->check_in,
                'check_out' => $attendance->check_out,
                'work_hours' => $attendance->work_hours,
                'overtime_hours' => $attendance->overtime_hours,
                'status' => $attendance->status,
                'notes' => $attendance->notes,
            ]);

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Employee', 'Date', 'Check In', 'Check Out', 'Work Hours', 'Overtime Hours', 'Status', 'Notes']);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 'attendance-export.csv');
    }

    protected function validateAttendance(Request $request, ?int $attendanceId = null): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'date' => ['required', 'date'],
            'check_in' => ['nullable', 'date_format:H:i'],
            'check_out' => ['nullable', 'date_format:H:i', 'after_or_equal:check_in'],
            'status' => ['required', 'in:Present,Absent,Late,Half Day,Leave'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    protected function parseTabularFile(UploadedFile $file): Collection
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (in_array($extension, ['xlsx', 'xls'], true)) {
            if (! class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
                throw ValidationException::withMessages([
                    'file' => 'Excel imports require the phpoffice/phpspreadsheet package. CSV imports work immediately.',
                ]);
            }

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $headerRow = array_shift($rows) ?? [];
            $header = collect($headerRow)
                ->mapWithKeys(fn ($value, $key) => [$key => strtolower(trim((string) $value))])
                ->all();

            return collect($rows)->map(function (array $row) use ($header) {
                $mapped = [];
                foreach ($row as $key => $value) {
                    $mapped[$header[$key] ?? strtolower((string) $key)] = $value;
                }

                return $mapped;
            });
        }

        $lines = array_filter(array_map('trim', file($file->getRealPath())));
        $header = null;

        return collect($lines)->map(function (string $line) use (&$header) {
            $row = str_getcsv($line);
            if ($header === null) {
                $header = array_map(fn ($value) => strtolower(trim($value)), $row);
                return null;
            }

            return array_combine($header, array_pad($row, count($header), null));
        })->filter();
    }
}
