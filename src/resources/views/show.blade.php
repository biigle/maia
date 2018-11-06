@extends('app')
@section('title', "Maia job #{$job->id}")
@section('full-navbar', true)

@push('styles')
<link href="{{ cachebust_asset('vendor/volumes/styles/main.css') }}" rel="stylesheet">
<link href="{{ cachebust_asset('vendor/annotations/styles/ol.css') }}" rel="stylesheet">
<link href="{{ cachebust_asset('vendor/annotations/styles/main.css') }}" rel="stylesheet">
<link href="{{ cachebust_asset('vendor/maia/styles/main.css') }}" rel="stylesheet">
@endpush

@push('scripts')
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
    </div>
    <sidebar open-tab="info" v-on:open="handleTabOpened">
        <sidebar-tab name="info" icon="info-circle" title="Job information">
            @include('maia::show.info-tab')
        </sidebar-tab>
        <sidebar-tab name="filter-training-proposals" icon="plus-square" title="Filter training proposals" v-bind:highlight="tpTabHighlight" @if ($job->state_id < $states['training-proposals']) disabled @endif>
            <div class="sidebar-tab__content">
                filter proposals
            </div>
        </sidebar-tab>
        <sidebar-tab name="refine-training-proposals" icon="pen-square" title="Refine training proposals" @if ($job->state_id < $states['training-proposals']) disabled @endif>
            <div class="sidebar-tab__content">
                refine
            </div>
        </sidebar-tab>
        <sidebar-tab name="filter-annotation-candidates" icon="check-square" title="Filter annotation candidates" v-bind:highlight="acTabHighlight" @if ($job->state_id < $states['annotation-candidates']) disabled @endif>
            <div class="sidebar-tab__content">
                filter candidates
            </div>
        </sidebar-tab>
    </sidebar>
</div>
@endsection

@section('navbar')
<div id="geo-navbar" class="navbar-text navbar-volumes-breadcrumbs">
    @include('volumes::partials.projectsBreadcrumb', ['projects' => $volume->projects]) / <a href="{{route('volume', $volume->id)}}">{{$volume->name}}</a> / <a href="{{route('volumes-maia', $volume->id)}}">MAIA</a> / <strong>Job #{{$job->id}}</strong>
</div>
@endsection
