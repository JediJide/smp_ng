<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class RolesTest extends DuskTestCase
{
    public function testIndex(): void
    {
        $admin = App\Models\User::find(1);
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin);
            $browser->visit(route('admin.roles.index'));
            $browser->assertRouteIs('admin.roles.index');
        });
    }
}
