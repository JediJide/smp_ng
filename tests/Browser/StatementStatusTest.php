<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class StatementStatusTest extends DuskTestCase
{
    public function testIndex(): void
    {
        $admin = App\Models\User::find(1);
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin);
            $browser->visit(route('admin.statementstatus.index'));
            $browser->assertRouteIs('admin.statementstatus.index');
        });
    }
}
