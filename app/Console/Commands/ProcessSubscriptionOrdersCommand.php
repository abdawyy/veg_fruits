<?php

namespace App\Console\Commands;

use App\Actions\Subscriptions\ProcessDueSubscriptionsAction;
use Illuminate\Console\Command;

class ProcessSubscriptionOrdersCommand extends Command
{
    protected $signature = 'aldawy:process-subscriptions';

    protected $description = 'Process due produce-box subscriptions (counts due rows; extend to generate orders).';

    public function handle(ProcessDueSubscriptionsAction $action): int
    {
        $due = $action->execute();
        $this->info("Due subscriptions: {$due}");

        return self::SUCCESS;
    }
}
