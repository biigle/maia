@extends('app')
@section('title', "{$volume->name} MAIA")

@push('styles')
<link href="{{ cachebust_asset('vendor/maia/styles/main.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ cachebust_asset('vendor/maia/scripts/main.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <div class="col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
        @if ($hasJobsInProgress)
            <div class="maia-status maia-status--running">
                <span class="fa-stack fa-2x" title="Job running for {{ $volume->name }}">
                    @if ($hasJobsRunning)
                        <i class="fas fa-circle fa-stack-1x"></i>
                        <i class="fas fa-cog fa-spin fa-stack-2x"></i>
                    @else
                        <i class="fas fa-circle fa-stack-2x"></i>
                    @endif
                    <i class="fas fa-robot fa-stack-1x fa-inverse"></i>
                </span>
            </div>
        @elseif ($newestJobHasFailed)
            <div class="maia-status maia-status--failed">
                <span class="fa-stack fa-2x" title="The most recent job failed for {{ $volume->name }}">
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class="fas fa-robot fa-stack-1x fa-inverse"></i>
                </span>
            </div>
        @else
            <div class="maia-status">
                <span class="fa-stack fa-2x" title="No jobs in progress for {{ $volume->name }}">
                    <i class="fas fa-circle fa-stack-2x"></i>
                    <i class="fas fa-robot fa-stack-1x fa-inverse"></i>
                </span>
            </div>
        @endif
        @include('maia::index.job-list')
        @if ($hasJobsInProgress)
            <p class="text-muted">New jobs cannot be created while there are unfinished other jobs.</p>
        @else
            @include('maia::index.job-form')
        @endunless
    </div>
</div>
@endsection

@section('navbar')
<div id="geo-navbar" class="navbar-text navbar-volumes-breadcrumbs">
    @include('volumes.partials.projectsBreadcrumb', ['projects' => $volume->projects]) / <a href="{{route('volume', $volume->id)}}">{{$volume->name}}</a> / <strong>MAIA</strong> <span class="label label-warning" title="This is an experimental feature">experimental</span>
</div>
@endsection
