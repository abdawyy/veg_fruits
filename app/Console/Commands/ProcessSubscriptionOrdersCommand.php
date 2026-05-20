<?php

namespace App\Console\Commands;

use App\Actions\Subscriptions\ProcessDueSubscriptionsAction;
use Illuminate\Console\Command;

class ProcessSubscriptionOrdersCommand extends Command
{
    protected $signature = 'aldawy:process-subscriptions';

    protected $description = 'Generate orders for due produce-box subscriptions.';

    public function handle(ProcessDueSubscriptionsAction $action): int
    {
        $processed = $action->execute();
        $this->info("Subscription orders created: {$processed}");

        return self::SUCCESS;
    }
}
