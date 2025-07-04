<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckPetitionsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'petitions:check-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the status of active petitions and update them if they have enough signatures.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking petition statuses...');

        $petitions = \App\Models\Petition::where('status', 'active')->get();

        foreach ($petitions as $petition) {
            if ($petition->signatures->count() >= $petition->signatures_required) {
                $petition->status = 'pending_review';
                $petition->save();
                $this->info("Petition #{$petition->id} status updated to pending_review.");
            }
        }

        $this->info('Done.');
    }
}
