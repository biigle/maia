<?php

namespace Biigle\Modules\Maia\Console\Commands;

use Biigle\ImageAnnotation;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;
use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Maia\TrainingProposal;
use File;
use FilesystemIterator;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Storage;

class MigratePatchStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maia:migrate-patch-storage
        {--dry-run : Do not copy any files}
        {path : Old storage directory for MAIA patches, relative to the BIIGLE storage directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate MAIA patches to the new disk storage.';

    /**
     * MAIA training proposal patch storage disk.
     *
     * @var string
     */
    protected $tpDisk;

    /**
     * MAIA annotation candidate patch storage disk.
     *
     * @var string
     */
    protected $acDisk;

    /**
     * Specifies if this is a dry run.
     *
     * @var bool
     */
    protected $dryRun;

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $this->tpDisk = Storage::disk(config('maia.training_proposal_storage_disk'));
        $this->acDisk = Storage::disk(config('maia.annotation_candidate_storage_disk'));
        $this->dryRun = $this->option('dry-run');
        $oldPath = storage_path($this->argument('path'));

        try {
            $directories = File::directories($oldPath);
        } catch (InvalidArgumentException $e) {
            $this->error($e->getMessage());
            return;
        }

        foreach ($directories as $directory) {
            $files = [];
            $iterator = new FilesystemIterator($directory);
            foreach ($iterator as $file) {
                $files[] = $file;
            }

            $jobId = basename($directory);
            $this->info("Migrating job {$jobId}.");
            $progress = $this->output->createProgressBar(count($files));

            collect($files)->chunk(1000)->each(function ($chunk) use ($progress) {
                $proposalFiles = [];
                $candidateFiles = [];

                foreach ($chunk as $file) {
                    list($type, $basename) = explode('-', $file->getBasename());
                    $id = intval(explode('.', $basename)[0]);
                    if ($type === 'p') {
                        $proposalFiles[$id] = $file;
                    } else {
                        $candidateFiles[$id] = $file;
                    }
                }

                $models = TrainingProposal::with('image')
                    ->findMany(array_keys($proposalFiles));
                $this->migrate($models, $proposalFiles, $this->tpDisk, 'p-');

                $models = AnnotationCandidate::with('image')
                    ->findMany(array_keys($candidateFiles));
                $this->migrate($models, $candidateFiles, $this->acDisk, 'c-');

                $progress->advance($chunk->count());
            });
            $progress->finish();
            $this->line('');
        }

        $this->info("Finished. You can delete '{$oldPath}' now.");
    }

    protected function migrate($models, $files, $disk, $prefix)
    {
        foreach ($models as $model) {
            $diskPath = fragment_uuid_path($model->image->uuid);
            $file = $files[$model->id];
            if (!$this->dryRun) {
                $disk->putFileAs($diskPath, $file, ltrim($file->getBasename(), $prefix));
            }
        }
    }
}
