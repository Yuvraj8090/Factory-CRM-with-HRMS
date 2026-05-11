<x-app-layout>
    <x-slot name="header">
        <x-crud.page-header
            title="Operations Dashboard"
            description="Track sales momentum, financial collections, workforce attendance, and plant readiness from one AdminLTE control panel."
            icon="chart-bar"
            :breadcrumbs="[['label' => 'Dashboard']]"
        >
            <span class="badge badge-primary px-3 py-2">Today: {{ now()->format('l, d M Y') }}</span>
        </x-crud.page-header>
    </x-slot>

    @php
        $activeLeads = \App\Models\Lead::query()->where('is_converted', false)->count();
        $pendingInvoices = \App\Models\Invoice::query()->whereIn('payment_status', ['Pending', 'Partial'])->count();
        $presentEmployees = \App\Models\Attendance::query()->whereDate('date', today())->where('status', 'Present')->count();
        $monthlyCollections = \App\Models\Payment::query()->whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->sum('amount');
        $qualifiedLeads = \App\Models\Lead::query()->whereHas('stage', fn ($query) => $query->where('name', 'Qualified'))->count();
        $sentQuotations = \App\Models\Quotation::query()->where('status', 'Sent')->count();
        $overdueInvoices = \App\Models\Invoice::query()->where('invoice_status', 'Overdue')->count();
        $reorderItems = \App\Models\ItemMaster::query()->whereColumn('opening_stock', '<=', 'reorder_level')->count();
        $attendanceExceptions = \App\Models\Attendance::query()->whereDate('date', today())->whereIn('status', ['Late', 'Half Day', 'Leave'])->count();
    @endphp

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($activeLeads) }}</h3>
                    <p>Active Leads</p>
                </div>
                <div class="icon"><i class="fas fa-user-plus"></i></div>
                <a href="{{ route('crm.leads.index') }}" class="small-box-footer">Open pipeline <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($pendingInvoices) }}</h3>
                    <p>Pending Invoices</p>
                </div>
                <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <a href="{{ route('finance.invoices.index') }}" class="small-box-footer">Review receivables <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($presentEmployees) }}</h3>
                    <p>Employees Present</p>
                </div>
                <div class="icon"><i class="fas fa-user-check"></i></div>
                <a href="{{ route('hrms.attendances.index') }}" class="small-box-footer">Open attendance <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>₹{{ number_format((float) $monthlyCollections, 2) }}</h3>
                    <p>Collections This Month</p>
                </div>
                <div class="icon"><i class="fas fa-wallet"></i></div>
                <a href="{{ route('finance.payments.index') }}" class="small-box-footer">View payments <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-1">Commercial Pulse</h3>
                            <p class="text-muted mb-0">Revenue, quotations, and overdue exposure.</p>
                        </div>
                        <a href="{{ route('crm.leads.index') }}" class="btn btn-primary btn-sm">Manage Leads</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-info"><i class="fas fa-filter"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Qualified Funnel</span>
                                    <span class="info-box-number">{{ number_format($qualifiedLeads) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-warning"><i class="fas fa-file-signature"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Sent Quotations</span>
                                    <span class="info-box-number">{{ number_format($sentQuotations) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-danger"><i class="fas fa-triangle-exclamation"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Overdue Risk</span>
                                    <span class="info-box-number">{{ number_format($overdueInvoices) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-light border mb-0">
                        The dashboard is now using AdminLTE cards, info boxes, and responsive grid behavior so the same visual language carries across desktop, tablet, and mobile views.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Plant Readiness</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Items at reorder level</span>
                        <strong>{{ number_format($reorderItems) }}</strong>
                    </div>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-warning" style="width: {{ min(100, max(10, $reorderItems * 10)) }}%"></div>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Attendance exceptions</span>
                        <strong>{{ number_format($attendanceExceptions) }}</strong>
                    </div>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-danger" style="width: {{ min(100, max(10, $attendanceExceptions * 10)) }}%"></div>
                    </div>

                    <a href="{{ route('hrms.payrolls.index') }}" class="btn btn-outline-primary btn-block">Go to HRMS workspace</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
