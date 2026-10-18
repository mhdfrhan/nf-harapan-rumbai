<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class StoreProfile
{
    /** @return array<string, mixed> */
    public function get(): array
    {
        $defaults = ['name' => config('store.name'), 'address' => config('store.address'), 'phone' => '', 'owner' => 'Ibu Emi Erawati', 'open_days' => 'Senin sampai Sabtu', 'open_hours' => '09.00 sampai 22.00', 'receipt_footer' => 'Terima kasih telah berbelanja di Toko NF Harapan Rumbai.'];
        if (! Schema::hasTable('settings')) {
            return $defaults;
        }

        $stored = Cache::remember('store.profile', now()->addHour(), fn () => Setting::query()->where('key', 'store.profile')->value('value_json'));

        return is_array($stored) ? array_merge($defaults, $stored) : $defaults;
    }

    public function forget(): void
    {
        Cache::forget('store.profile');
    }
}
