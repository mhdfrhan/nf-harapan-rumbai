<?php

namespace App\Providers;

use App\Models\GoodsReceipt;
use App\Models\PayablePayment;
use App\Models\Sale;
use App\Models\StockAdjustment;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\StoreProfile;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        Relation::enforceMorphMap([
            'sale' => Sale::class,
            'receipt' => GoodsReceipt::class,
            'payable_payment' => PayablePayment::class,
            'stock_adjustment' => StockAdjustment::class,
        ]);
        Gate::before(fn (User $user, string $ability) => $user->hasPermission($ability) ? true : null);
        View::composer('pages.sales.show', fn ($view) => $view->with('storeProfile', app(StoreProfile::class)->get()));
        Event::listen(Login::class, function (Login $event): void {
            if ($event->user instanceof User) {
                $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
                app(AuditLogger::class)->record('auth.login.succeeded', $event->user, $event->user);
            }
        });
        Event::listen(Failed::class, function (Failed $event): void {
            app(AuditLogger::class)->record('auth.login.failed', $event->user instanceof User ? $event->user : null, $event->user instanceof User ? $event->user : null, [], [], ['username' => mb_substr((string) request()->input('username'), 0, 80)]);
        });
        Event::listen(Logout::class, function (Logout $event): void {
            if ($event->user instanceof User) {
                app(AuditLogger::class)->record('auth.logout', $event->user, $event->user);
            }
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(function (): Password {
            $rule = Password::min(12)->mixedCase()->letters()->numbers()->symbols();

            return app()->isProduction() ? $rule->uncompromised() : $rule;
        });
    }
}
