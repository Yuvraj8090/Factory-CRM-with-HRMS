<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Facades\DataTables;

trait BuildsDataTables
{
    protected function isDataTableRequest(Request $request): bool
    {
        return $request->ajax() && $request->has('draw');
    }

    protected function dataTable(Builder $query): EloquentDataTable
    {
        return DataTables::eloquent($query);
    }

    protected function statusBadge(string $value): string
    {
        $map = [
            'Active' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'Inactive' => 'bg-slate-100 text-slate-700 ring-slate-200',
            'Blocked' => 'bg-rose-100 text-rose-800 ring-rose-200',
            'Pending' => 'bg-amber-100 text-amber-800 ring-amber-200',
            'Approved' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'Rejected' => 'bg-rose-100 text-rose-800 ring-rose-200',
            'Present' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'Absent' => 'bg-rose-100 text-rose-800 ring-rose-200',
            'Late' => 'bg-amber-100 text-amber-800 ring-amber-200',
            'Half Day' => 'bg-orange-100 text-orange-800 ring-orange-200',
            'Leave' => 'bg-sky-100 text-sky-800 ring-sky-200',
            'Draft' => 'bg-slate-100 text-slate-700 ring-slate-200',
            'Sent' => 'bg-sky-100 text-sky-800 ring-sky-200',
            'Accepted' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'Paid' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'Partial' => 'bg-amber-100 text-amber-800 ring-amber-200',
            'Overdue' => 'bg-rose-100 text-rose-800 ring-rose-200',
            'Cancelled' => 'bg-slate-100 text-slate-700 ring-slate-200',
            'Completed' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'In Progress' => 'bg-indigo-100 text-indigo-800 ring-indigo-200',
            'Open' => 'bg-sky-100 text-sky-800 ring-sky-200',
            'Rescheduled' => 'bg-violet-100 text-violet-800 ring-violet-200',
            'New' => 'bg-sky-100 text-sky-800 ring-sky-200',
            'Contacted' => 'bg-amber-100 text-amber-800 ring-amber-200',
            'Qualified' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'Proposal Sent' => 'bg-indigo-100 text-indigo-800 ring-indigo-200',
            'Negotiation' => 'bg-orange-100 text-orange-800 ring-orange-200',
            'Won' => 'bg-teal-100 text-teal-800 ring-teal-200',
            'Lost' => 'bg-rose-100 text-rose-800 ring-rose-200',
        ];

        $classes = $map[$value] ?? 'bg-slate-100 text-slate-700 ring-slate-200';

        return (string) new HtmlString(sprintf(
            '<span class="%s inline-flex rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset">%s</span>',
            e($classes),
            e($value)
        ));
    }

    protected function recordLink(string $title, string $url, array $meta = []): string
    {
        $metaHtml = collect($meta)
            ->filter(fn (?string $line) => filled($line))
            ->map(fn (string $line, int $index) => sprintf(
                '<p class="%s">%s</p>',
                $index === 0 ? 'mt-1 text-sm text-slate-500' : 'mt-2 text-xs text-slate-500',
                e($line)
            ))
            ->implode('');

        return (string) new HtmlString(sprintf(
            '<a href="%s" class="font-bold text-slate-950 hover:text-amber-700">%s</a>%s',
            e($url),
            e($title),
            $metaHtml
        ));
    }

    protected function actionButtons(string $showUrl, string $editUrl): string
    {
        return (string) new HtmlString(sprintf(
            '<div class="flex justify-end gap-2"><a href="%s" class="btn btn-outline-secondary btn-sm">View</a><a href="%s" class="btn btn-outline-secondary btn-sm">Edit</a></div>',
            e($showUrl),
            e($editUrl)
        ));
    }

    protected function money(?float $amount): string
    {
        return '₹' . number_format((float) $amount, 2);
    }
}
