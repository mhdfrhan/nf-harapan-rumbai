<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\AuditLogger;
use App\Services\StoreProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreSettingsController extends Controller
{
    public function edit(StoreProfile $profiles): View
    {
        $profile = $profiles->get();

        return view('pages.settings.store', compact('profile'));
    }

    public function update(Request $request, AuditLogger $audit, StoreProfile $profiles): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'address' => ['required', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:40'],
            'owner' => ['required', 'string', 'max:120'],
            'open_days' => ['required', 'string', 'max:120'],
            'open_hours' => ['required', 'string', 'max:120'],
            'receipt_footer' => ['nullable', 'string', 'max:500'],
        ]);

        $setting = Setting::query()->firstOrNew(['key' => 'store.profile']);
        $before = $setting->exists && is_array($setting->value_json) ? $setting->value_json : [];
        $setting->fill(['value_json' => $data, 'type' => 'json', 'is_sensitive' => false])->save();
        $profiles->forget();
        $audit->record('settings.store_updated', $request->user(), $setting, $before, $data);

        return back()->with('status', 'Pengaturan toko berhasil disimpan.');
    }
}
