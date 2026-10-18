<?php

namespace Tests\Feature\Administration;

use App\Domain\Identity\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdministrationSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_inactive_user_cannot_authenticate(): void
    {
        $user = User::factory()->create(['is_active' => false]);

        $this->post(route('login.store'), ['username' => $user->username, 'password' => 'password'])
            ->assertSessionHasErrors('username');

        $this->assertGuest();
    }

    public function test_owner_cannot_change_own_role_or_disable_own_account(): void
    {
        $owner = User::factory()->create(['role' => Role::Owner, 'is_active' => true]);

        $this->actingAs($owner)->put(route('admin.users.update', $owner), ['role' => Role::Cashier->value, 'is_active' => 0])
            ->assertSessionHasErrors('role');

        self::assertSame(Role::Owner, $owner->fresh()->role);
        self::assertTrue($owner->fresh()->is_active);
    }

    public function test_auditor_can_read_but_cannot_change_store_settings(): void
    {
        $auditor = User::factory()->create(['role' => Role::Auditor]);

        $this->actingAs($auditor)->get(route('settings.store'))->assertOk();
        $this->actingAs($auditor)->put(route('settings.store.update'), [])->assertForbidden();
    }

    public function test_owner_password_reset_forces_staff_to_replace_temporary_password(): void
    {
        $owner = User::factory()->create(['role' => Role::Owner]);
        $staff = User::factory()->create(['role' => Role::Cashier, 'force_password_change' => false]);
        $temporaryPassword = 'Temporary-2026!';

        $this->actingAs($owner)->put(route('admin.users.password', $staff), ['password' => $temporaryPassword, 'password_confirmation' => $temporaryPassword])
            ->assertSessionHasNoErrors();

        self::assertTrue($staff->fresh()->force_password_change);
    }
}
