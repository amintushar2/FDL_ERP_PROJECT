@extends('layouts.app')
@section('title', 'Leave Entry')

@push('styles')
@include('hrm.leave._styles')
@endpush

@section('content')
<div class="container-fluid py-3 hrm-page">

    <div class="hrm-page-title">
        <i class="bi bi-calendar2-check"></i> Leave Entry
        <a href="{{ route('leave.create') }}" class="hrm-btn hrm-btn-primary ms-auto" style="font-size:12px;padding:5px 13px">
            <i class="bi bi-plus-circle"></i> New Entry
        </a>
    </div>

    @if(session('success'))
    <div class="hrm-alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif

    {{-- Search --}}
    <div class="hrm-search-card">
        <form method="GET" action="{{ route('leave.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="hrm-label">New Emp No</label>
                    <input type="text" name="new_empno" class="hrm-control"
                           value="{{ request('new_empno') }}" placeholder="e.g. 1001">
                </div>
                <div class="col-md-2">
                    <label class="hrm-label">Sys Emp No</label>
                    <input type="text" name="empno" class="hrm-control"
                           value="{{ request('empno') }}" placeholder="e.g. E-0042">
                </div>
                <div class="col-md-1">
                    <label class="hrm-label">Year</label>
                    <input type="number" name="year" class="hrm-control"
                           value="{{ request('year') }}" placeholder="{{ date('Y') }}">
                </div>
                <div class="col-md-3">
                    <label class="hrm-label">Company</label>
                    <select name="company_id" class="hrm-control" style="height:30px">
                        <option value="">All Companies</option>
                        @foreach($companies as $c)
                        <option value="{{ $c->company_id }}"
                            {{ request('company_id') == $c->company_id ? 'selected' : '' }}>
                            {{ $c->company_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-2">
                    <button type="submit" class="hrm-btn hrm-btn-primary" style="padding:5px 13px">
                        <i class="bi bi-search"></i> Search
                    </button>
                    <a href="{{ route('leave.index') }}" class="hrm-btn hrm-btn-secondary" style="padding:5px 13px">Reset</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Results --}}
    <div class="hrm-card">
        <div class="hrm-card-header">
            <i class="bi bi-table"></i> Results
            <span class="ms-2" style="background:rgba(255,255,255,.18);padding:1px 9px;border-radius:12px;font-size:11px">
                {{ $records->total() }}
            </span>
        </div>
        <div class="hrm-card-body p-0">
            <div class="table-responsive">
                <table class="hrm-table">
                    <thead>
                        <tr>
                            <th style="width:40px">#</th>
                            <th>Emp No (Sys)</th>
                            <th>Emp No (New)</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Year</th>
                            <th>Company</th>
                            <th style="width:90px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($records as $r)
                    <tr>
                        <td>{{ $loop->iteration + ($records->currentPage()-1)*$records->perPage() }}</td>
                        <td style="color:#6c757d;font-size:11.5px">{{ $r->empno }}</td>
                        <td><strong style="color:#1a3c5e">{{ $r->new_empno }}</strong></td>
                        <td>{{ trim($r->ename) }}</td>
                        <td>{{ $r->des_name }}</td>
                        <td>{{ $r->year }}</td>
                        <td>{{ $r->company_id }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('leave.edit', [$r->empno, $r->year, $r->company_id]) }}"
                               class="hrm-btn-danger-outline me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="{{ route('leave.show', [$r->empno, $r->year, $r->company_id]) }}"
                               class="hrm-btn-success-outline" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No records found.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($records->hasPages())
        <div class="hrm-pagination">{{ $records->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
