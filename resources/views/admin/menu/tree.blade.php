@extends('layouts.app')
@section('title', 'Menu Tree')
@section('page-title', 'Menu Tree')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Menu Hierarchy</a></li>
    <li class="breadcrumb-item active">Tree View</li>
@endsection

@php
    $childrenByParent = $menus->groupBy(fn($m) => $m->parent_id ?: '');

    $renderTree = function ($parentId = '') use (&$renderTree, $childrenByParent) {
        $items = $childrenByParent->get($parentId, collect());

        if ($items->isEmpty()) {
            return '';
        }

        $html = '<ul class="list-group list-group-flush ms-' . ($parentId ? '3' : '0') . '">';

        foreach ($items as $item) {
            $active = $item->is_active === 'Y' ? 'Active' : 'Inactive';
            $badge = $item->is_active === 'Y' ? 'success' : 'secondary';
            $html .= '<li class="list-group-item">';
            $html .= '<div class="d-flex align-items-center justify-content-between gap-2">';
            $html .=
                '<div><code>' . e($item->child_id) . '</code> <span class="fw-semibold">' . e($item->title) . '</span>';
            $html .= '<div class="text-muted small">' . e($item->DIR ?: '') . '</div></div>';
            $html .=
                '<span class="badge bg-' .
                $badge .
                '-subtle text-' .
                $badge .
                ' border border-' .
                $badge .
                '-subtle">' .
                $active .
                '</span>';
            $html .= '</div>';
            $html .= $renderTree($item->child_id);
            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    };
@endphp

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-diagram-2 me-2"></i>Menu Tree</span>
            <a href="{{ route('menus.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
        <div class="card-body">
            {!! $renderTree() ?: '<div class="text-center text-muted py-4">No menus configured</div>' !!}
        </div>
    </div>
@endsection
