<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class ManageLocationAutoState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:manage-auto-state';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the location devices based on sensor readings';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Location[] $locations */
        $locations = Location::where('mode', 'auto')->get();
        foreach ($locations as $location) {

            if ($location->heater) {
                if ($location->target_temperature > $location->temperature) {
                    //To cold
                    $location->heater->state = true;
                } else {
                    $location->heater->state = false;
                }
                $location->heater->save();
            }

            if ($location->fan) {
                if ($location->target_temperature > $location->temperature) {
                    //To cold
                    $location->fan->state = false;
                } else {
                    $location->fan->state = true;
                }
                $location->fan->save();
            }
        }
    }
}
