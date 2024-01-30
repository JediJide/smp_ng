<?php

namespace App\Observers;

use App\Models\Statement;
use Illuminate\Support\Facades\Cache;

class StatementObserver
{
    /**
     * Handle the Statement "created" event.
     *
     * @param Statement $statement
     * @return void
     */
    public function created(Statement $statement)
    {
        Cache::forget('statement_tree');
    }

    /**
     * Handle the Statement "updated" event.
     *
     * @param Statement $statement
     * @return void
     */
    public function updated(Statement $statement)
    {
        Cache::forget('statement_tree');
    }

    /**
     * Handle the Statement "deleted" event.
     *
     * @param Statement $statement
     * @return void
     */
    public function deleted(Statement $statement)
    {
        Cache::forget('statement_tree');
    }

    /**
     * Handle the Statement "restored" event.
     *
     * @param Statement $statement
     * @return void
     */
    public function restored(Statement $statement)
    {
        Cache::forget('statement_tree');
    }

    /**
     * Handle the Statement "force deleted" event.
     *
     * @param Statement $statement
     * @return void
     */
    public function forceDeleted(Statement $statement)
    {
        Cache::forget('statement_tree');
    }
}
