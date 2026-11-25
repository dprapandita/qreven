<?php

namespace App\Jobs;

use App\Models\TicketOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ExpireTicketOrder implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [5, 30];

    public function __construct(public TicketOrder $order) {}

    public function handle(): void
    {
        DB::transaction(function (): void {
            $order = TicketOrder::query()
                ->whereKey($this->order->id)
                ->lockForUpdate()
                ->first();

            if ($order === null || ! $order->isPending()) {
                return;
            }

            if ($order->expires_at !== null && $order->expires_at->isFuture()) {
                return;
            }

            $order->expire();
        });
    }
}
