<?php

namespace Laravel\Horizon\Listeners;

use Laravel\Horizon\Events\MasterSupervisorLooped;

class MonitorMasterSupervisorMemory
{
    /**
     * Handle the event.
     *
     * @param  \Laravel\Horizon\Events\MasterSupervisorLooped  $event
     * @return void
     */
    public function handle(MasterSupervisorLooped $event)
    {
        $master = $event->master;
        $usage = $master->memoryUsage();
        $limit = config('horizon.memory_limit', 64);

        if ($usage > $limit) {
            $master->output('error', 'The supervisor has exceeded the configured memory limit and has been terminated. [Usage: '.$usage.' Limit: '.$limit.']');

            $master->terminate(12);
        }
    }
}
