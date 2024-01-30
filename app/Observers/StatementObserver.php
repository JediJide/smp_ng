<?php

namespace App\Observers;

use App\Models\Statement;
use Illuminate\Support\Facades\Cache;

class StatementObserver
{
    /**
     * Handle the Statement "created" event.
     *
     * @return void
     */
    public function created(Statement $statement): void
    {
        Cache::forget('statement_tree');
    }

    /**
     * Handle the Statement "updated" event.
     *
     * @return void
     */
    public function updated(Statement $statement): void
    {
        Cache::forget('statement_tree');
    }

    /**
     * Handle the Statement "deleted" event.
     *
     * @return void
     */
    public function deleted(Statement $statement): void
    {
        Cache::forget('statement_tree');
    }

    /**
     * Handle the Statement "restored" event.
     *
     * @return void
     */
    public function restored(Statement $statement): void
    {
        Cache::forget('statement_tree');
    }

    /**
     * Handle the Statement "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Statement $statement): void
    {
        Cache::forget('statement_tree');
    }
}
