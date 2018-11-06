@extends('app')
@section('title', "{$volume->title}")

@push('styles')
<link href="{{ cachebust_asset('vendor/volumes/styles/main.css') }}" rel="stylesheet">
<link href="{{ cachebust_asset('vendor/maia/styles/main.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ cachebust_asset('vendor/maia/scripts/main.js') }}"></script>
@endpush

@section('content')
<div class="container">
    <div class="col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
        @if ($jobRunning)
            <div class="maia-status maia-status--running">
                <span class="fa-stack fa-2x" title="Job running for {{ $volume->name }}">
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
        @unless($jobRunning)
            @include('maia::index.job-form')
        @endunless
    </div>
</div>
@endsection

@section('navbar')
<div id="geo-navbar" class="navbar-text navbar-volumes-breadcrumbs">
    @include('volumes::partials.projectsBreadcrumb', ['projects' => $volume->projects]) / <a href="{{route('volume', $volume->id)}}">{{$volume->name}}</a> / <strong>MAIA</strong>
</div>
@endsection
