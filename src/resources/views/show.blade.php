@extends('app')
@section('title', "MAIA job #{$job->id}")
@section('full-navbar', true)

@push('styles')
<link href="{{ cachebust_asset('vendor/volumes/styles/main.css') }}" rel="stylesheet">
<link href="{{ cachebust_asset('vendor/largo/styles/main.css') }}" rel="stylesheet">
<link href="{{ cachebust_asset('vendor/annotations/styles/ol.css') }}" rel="stylesheet">
<link href="{{ cachebust_asset('vendor/annotations/styles/main.css') }}" rel="stylesheet">
<link href="{{ cachebust_asset('vendor/maia/styles/main.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ cachebust_asset('vendor/volumes/scripts/main.js') }}"></script>
<script src="{{ cachebust_asset('vendor/largo/scripts/main.js') }}"></script>
@if (app()->environment('local'))
    <script src="{{ cachebust_asset('vendor/annotations/scripts/ol-debug.js') }}"></script>
@else
    <script src="{{ cachebust_asset('vendor/annotations/scripts/ol.js') }}"></script>
@endif
<script src="{{ cachebust_asset('vendor/annotations/scripts/main.js') }}"></script>
<script src="{{ cachebust_asset('vendor/maia/scripts/main.js') }}"></script>
<script type="text/javascript">
    biigle.$declare('maia.job', {!! $job->toJson() !!});
    biigle.$declare('maia.states', {!! $states->toJson() !!});
</script>
@endpush

@section('content')
<div id="maia-show-container" class="sidebar-container" v-cloak>
    <div class="sidebar-container__content">
        @include('maia::show.info-content')
        @include('maia::show.filter-tp-content')
    </div>
    <sidebar v-bind:open-tab="openTab" v-on:open="handleTabOpened" v-on:toggle="handleSidebarToggle">
        <sidebar-tab name="info" icon="info-circle" title="Job information">
            @include('maia::show.info-tab')
        </sidebar-tab>
        @if ($job->state_id < $states['training-proposals'])
            <sidebar-tab name="filter-training-proposals" icon="plus-square" title="Training proposals are not ready yet" disabled></sidebar-tab>
            <sidebar-tab name="refine-training-proposals" icon="pen-square" title="Training proposals are not ready yet" disabled></sidebar-tab>
        @else
            <sidebar-tab name="filter-training-proposals" icon="plus-square" title="Filter training proposals">
                @include('maia::show.filter-tp-tab')
            </sidebar-tab>
            <sidebar-tab name="refine-training-proposals" icon="pen-square" title="Refine training proposals">
                @include('maia::show.refine-tp-tab')
            </sidebar-tab>
        @endif
        @if ($job->state_id < $states['annotation-candidates'])
            <sidebar-tab name="review-annotation-candidates" icon="check-square" title="Annotation candidates are not ready yet" disabled></sidebar-tab>
        @else
            <sidebar-tab name="review-annotation-candidates" icon="check-square" title="Review annotation candidates">
                @include('maia::show.review-ac-tab')
            </sidebar-tab>
        @endif
    </sidebar>
</div>
@endsection

@section('navbar')
<div id="geo-navbar" class="navbar-text navbar-volumes-breadcrumbs">
    @include('volumes::partials.projectsBreadcrumb', ['projects' => $volume->projects]) / <a href="{{route('volume', $volume->id)}}">{{$volume->name}}</a> / <a href="{{route('volumes-maia', $volume->id)}}">MAIA</a> / <strong>Job #{{$job->id}}</strong>
</div>
@endsection
