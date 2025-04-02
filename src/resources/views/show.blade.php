@extends('app')
@section('title', "MAIA job #{$job->id}")
@section('full-navbar', true)

@push('styles')
{{vite_hot(base_path('vendor/biigle/largo/hot'), ['src/resources/assets/sass/main.scss'], 'vendor/largo')}}
{{vite_hot(base_path('vendor/biigle/maia/hot'), ['src/resources/assets/sass/main.scss'], 'vendor/maia')}}
@endpush

@push('scripts')
{{vite_hot(base_path('vendor/biigle/largo/hot'), ['src/resources/assets/js/main.js'], 'vendor/largo')}}
{{vite_hot(base_path('vendor/biigle/maia/hot'), ['src/resources/assets/js/main.js'], 'vendor/maia')}}
<script type="module">
    biigle.$declare('maia.job', {!! $job->toJson() !!});
    biigle.$declare('maia.states', {!! $states->toJson() !!});
    biigle.$declare('maia.labelTrees', {!! $trees->toJson() !!});
    biigle.$declare('annotations.imageFileUri', '{!! url('api/v1/images/:id/file') !!}');
    biigle.$declare('maia.tpUrlTemplate', '{{$tpUrlTemplate}}');
    biigle.$declare('maia.acUrlTemplate', '{{$acUrlTemplate}}');
</script>
@endpush

@section('content')
<div id="maia-show-container" class="sidebar-container" v-cloak>
    <div class="sidebar-container__content">
        <div v-show="infoTabOpen" class="maia-content">
            @include('maia::show.info-content')
        </div>
        @if ($job->shouldShowTrainingProposals())
            @if ($job->state_id >= $states['training-proposals'])
                <div v-show="selectProposalsTabOpen" v-cloak class="maia-content">
                    @include('maia::show.select-proposals-content')
                </div>
                <div v-show="refineProposalsTabOpen" v-cloak class="maia-content">
                    @include('maia::show.refine-proposals-content')
                </div>
            @endif
        @endif
        @if ($job->state_id >= $states['annotation-candidates'])
            <div v-show="selectCandidatesTabOpen" v-cloak class="maia-content">
                @include('maia::show.select-candidates-content')
            </div>
            <div v-show="refineCandidatesTabOpen" v-cloak class="maia-content">
                @include('maia::show.refine-candidates-content')
            </div>
        @endif
        <loader-block :active="loading"></loader-block>
    </div>
    <sidebar v-bind:open-tab="openTab" v-on:open="handleTabOpened" v-on:toggle="updateImageGridDimensions">
        <sidebar-tab name="info" icon="info-circle" title="Job information">
            @include('maia::show.info-tab')
        </sidebar-tab>
        @if ($job->shouldShowTrainingProposals())
            @if ($job->state_id < $states['training-proposals'])
                <sidebar-tab name="select-proposals" icon="plus-square" title="Training proposals are not ready yet" disabled></sidebar-tab>
                <sidebar-tab name="refine-proposals" icon="pen-square" title="Training proposals are not ready yet" disabled></sidebar-tab>
            @else
                <sidebar-tab name="select-proposals" icon="plus-square" title="Select training proposals">
                    @include('maia::show.select-proposals-tab')
                </sidebar-tab>
                <sidebar-tab name="refine-proposals" icon="pen-square" title="Refine training proposals">
                    @include('maia::show.refine-proposals-tab')
                </sidebar-tab>
            @endif
        @endif
        @if ($job->state_id < $states['annotation-candidates'])
            <sidebar-tab name="select-candidates" icon="check-square" title="Annotation candidates are not ready yet" disabled></sidebar-tab>
            <sidebar-tab name="refine-candidates" icon="pen-square" title="Annotation candidates are not ready yet" disabled></sidebar-tab>
        @else
            <sidebar-tab name="select-candidates" icon="check-square" title="Select annotation candidates">
                @include('maia::show.select-candidates-tab')
            </sidebar-tab>
            <sidebar-tab name="refine-candidates" icon="pen-square" title="Refine annotation candidates">
                @include('maia::show.refine-candidates-tab')
            </sidebar-tab>
        @endif
    </sidebar>
</div>
@endsection

@section('navbar')
<div id="geo-navbar" class="navbar-text navbar-volumes-breadcrumbs">
    @include('volumes.partials.projectsBreadcrumb', ['projects' => $volume->projects]) / <a href="{{route('volume', $volume->id)}}">{{$volume->name}}</a> / <a href="{{route('volumes-maia', $volume->id)}}">MAIA</a> / <strong>Job #{{$job->id}}</strong>
</div>
@endsection
