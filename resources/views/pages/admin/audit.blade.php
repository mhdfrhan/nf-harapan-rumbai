<x-layouts::app :title="__('Audit Aktivitas Sistem')">
    <div class="ui-page space-y-6">
        <x-ui.page-header 
            eyebrow="Keamanan & Audit" 
            title="Log Audit & Aktivitas Sistem" 
            description="Telusuri setiap transaksi kasir, perubahan stok gudang, dan aktivitas login pengguna secara transparan." 
        />

        <!-- Filter Toolbar Audit -->
        <section class="ui-panel p-4 sm:p-5">
            <form method="GET" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5 items-end">
                <div class="lg:col-span-1">
                    <label class="ui-label text-xs mb-1 block">Cari Kejadian</label>
                    <input name="event" class="ui-input text-xs" value="{{ request('event') }}" placeholder="Contoh: Penjualan...">
                </div>

                <div>
                    <label class="ui-label text-xs mb-1 block">Petugas / Pengguna</label>
                    <select name="actor_id" class="ui-select text-xs">
                        <option value="">Semua Pengguna</option>
                        @foreach($actors as $actor)
                            <option value="{{ $actor->id }}" @selected((string)request('actor_id') === (string)$actor->id)>
                                {{ $actor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="ui-label text-xs mb-1 block">Dari Tanggal</label>
                    <input name="from" type="date" class="ui-input text-xs" value="{{ request('from') }}">
                </div>

                <div>
                    <label class="ui-label text-xs mb-1 block">Sampai Tanggal</label>
                    <input name="to" type="date" class="ui-input text-xs" value="{{ request('to') }}">
                </div>

                <div class="flex items-center gap-2">
                    <button class="ui-btn ui-btn-primary text-xs font-bold w-full" type="submit">
                        Filter Log
                    </button>
                    @if(request('event') || request('actor_id') || request('from') || request('to'))
                        <a href="{{ route('admin.audit') }}" wire:navigate class="ui-btn text-xs font-semibold">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </section>

        @if ($logs->isEmpty())
            <x-ui.state-frame emptyTitle="Tidak Ada Catatan Log Aktivitas" emptyDescription="Tidak ada catatan audit yang cocok dengan filter tanggal atau kriteria pencarian di atas." />
        @else
            <!-- Daftar Log Audit Modern -->
            <section class="ui-panel overflow-hidden">
                <ol class="divide-y divide-zinc-100">
                    @foreach ($logs as $log)
                        <li class="grid gap-4 p-5 sm:grid-cols-[160px_200px_minmax(0,1fr)] items-start hover:bg-cream-50/50 transition-colors">
                            <div>
                                <time class="text-sm font-bold text-zinc-900 block">
                                    {{ $log->occurred_at->translatedFormat('d M Y') }}
                                </time>
                                <span class="text-xs font-medium text-zinc-500">
                                    {{ $log->occurred_at->format('H.i.s') }} WIB
                                </span>
                            </div>

                            <div>
                                <p class="text-sm font-bold text-zinc-950">{{ $log->actor?->name ?? 'Sistem Otomatis' }}</p>
                                <p class="mt-1 text-xs font-mono text-zinc-500">{{ $log->ip_address ?: 'IP tidak tercatat' }}</p>
                            </div>

                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold bg-{{ $log->event_badge_color }}-100 text-{{ $log->event_badge_color }}-900 border border-{{ $log->event_badge_color }}-200">
                                        <span class="size-1.5 rounded-full bg-{{ $log->event_badge_color }}-600"></span>
                                        <span>{{ $log->event_label }}</span>
                                    </span>
                                    <span class="text-xs font-mono text-zinc-400">({{ $log->event }})</span>
                                </div>
                                <p class="break-all text-xs text-zinc-600 leading-relaxed pt-1">
                                    <span class="font-semibold text-zinc-700">Request:</span> {{ $log->request_id ?: '-' }} &bull; 
                                    <span class="font-semibold text-zinc-700">Target Entitas:</span> {{ $log->auditable_type ?: '-' }} #{{ $log->auditable_id ?: '-' }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </section>

            <div>
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</x-layouts::app>
