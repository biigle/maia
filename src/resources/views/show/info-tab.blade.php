<div class="sidebar-tab__content sidebar-tab__content--maia">
    <div class="maia-tab-content__top">
        <p>
            Job #{{$job->id}} @include('maia::mixins.job-state', ['job' => $job])
        </p>
        <p>
            created <span title="{{$job->created_at->toIso8601String()}}">{{$job->created_at->diffForHumans()}} by {{$job->user->firstname}} {{$job->user->lastname}}</span>
        </p>
        @if ($job->shouldUseNoveltyDetection())
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">
                            Novelty Detection
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Clusters</td>
                        <td class="text-right"><code>{{Arr::get($job->params, 'nd_clusters')}}</code></td>
                    </tr>
                    <tr>
                        <td>Patch size</td>
                        <td class="text-right"><code>{{Arr::get($job->params, 'nd_patch_size')}}</code></td>
                    </tr>
                    <tr>
                        <td>Threshold</td>
                        <td class="text-right"><code>{{Arr::get($job->params, 'nd_threshold')}}</code></td>
                    </tr>
                    <tr>
                        <td>Latent size</td>
                        <td class="text-right"><code>{{Arr::get($job->params, 'nd_latent_size')}}</code></td>
                    </tr>
                    <tr>
                        <td>Training size</td>
                        <td class="text-right"><code>{{Arr::get($job->params, 'nd_trainset_size')}}</code></td>
                    </tr>
                    <tr>
                        <td>Training epochs</td>
                        <td class="text-right"><code>{{Arr::get($job->params, 'nd_epochs')}}</code></td>
                    </tr>
                    <tr>
                        <td>Stride</td>
                        <td class="text-right"><code>{{Arr::get($job->params, 'nd_stride')}}</code></td>
                    </tr>
                    <tr>
                        <td>Ignore radius</td>
                        <td class="text-right"><code>{{Arr::get($job->params, 'nd_ignore_radius')}}</code></td>
                    </tr>
                </tbody>
            </table>
        @elseif($job->shouldUseExistingAnnotations())
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            Existing annotations
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @if (Arr::has($job->params, 'oa_restrict_labels'))
                            <td>Restricted to label IDs: {{implode(', ', Arr::get($job->params, 'oa_restrict_labels', []))}}</td>
                        @else
                            <td>Using all annotations of this volume.</td>
                        @endif
                    </tr>
                    @if ($job->shouldShowTrainingProposals())
                        <tr>
                            <td>Using a subset of selected training proposals.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @elseif($job->shouldUseKnowledgeTransfer())
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            Knowledge transfer
                            @if ($job->params['training_data_method'] === \Biigle\Modules\Maia\MaiaJob::TRAIN_AREA_KNOWLEDGE_TRANSFER)
                                (area)
                            @else
                                <br>(distance to ground)
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                            $volumeId = Arr::get($job->params, 'kt_volume_id');
                            $v = Biigle\Volume::find($volumeId);
                        ?>
                        @if (Arr::has($job->params, 'kt_restrict_labels'))
                            <td>Restricted to label IDs: {{implode(', ', Arr::get($job->params, 'kt_restrict_labels', []))}} of volume {{$v ? $v->name : $volumeId}}.</td>
                        @else
                            <td>Using all annotations of volume {{$v ? $v->name : $volumeId}}.</td>
                        @endif
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
    <div class="maia-tab-content__bottom">
        <form class="text-right" action="{{ url("api/v1/maia-jobs/{$job->id}") }}" method="POST" onsubmit="return confirm('Are you sure that you want to delete this job?')">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
            @if ($job->state_id === $states['novelty-detection'])
                <button class="btn btn-danger" type="button" title="The job cannot be deleted while the novelty detection is running" disabled>Delete this job</button>
            @elseif ($job->state_id === $states['instance-segmentation'])
                <button class="btn btn-danger" type="button" title="The job cannot be deleted while the instance segmentation is running" disabled>Delete this job</button>
            @else
                <button class="btn btn-danger" type="submit">Delete this job</button>
            @endif
        </form>
    </div>
</div>
