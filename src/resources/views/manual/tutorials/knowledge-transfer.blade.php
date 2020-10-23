@extends('manual.base')

@section('manual-title', 'Knowledge transfer')

@section('manual-content')
    <div class="row">
        <p class="lead">
            Using knowledge transfer to obtain training data.
        </p>

        {{-- TODO --}}
        <p>
            <span class="label label-warning">Coming soon</span>
        </p>

        <h3>Further reading</h3>
        <ul>
            <li><a href="{{route('manual-tutorials', ['maia', 'about'])}}">An introduction to the Machine Learning Assisted Image Annotation method (MAIA).</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'novelty-detection'])}}">Using novelty detection to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'existing-annotations'])}}">Using existing annotations to obtain training data.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'training-proposals'])}}">Reviewing the training proposals from novelty detection.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'instance-segmentation'])}}">The automatic instance segmentation.</a></li>
            <li><a href="{{route('manual-tutorials', ['maia', 'annotation-candidates'])}}">Reviewing the annotation candidates from instance segmentation.</a></li>
        </ul>
    </div>
@endsection
