@props(['code', 'title', 'description', 'action' => 'Kembali ke Beranda'])

<!DOCTYPE html>
<html lang="id">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-cream-100 text-zinc-900 antialiased selection:bg-accent selection:text-white">
        <main class="flex min-h-svh items-center justify-center p-6">
            <div class="w-full max-w-lg rounded-3xl border border-zinc-200/90 bg-white p-8 text-center shadow-xs">
                <a href="{{ url('/') }}" class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-accent text-white shadow-md transition-transform hover:scale-105" aria-label="Toko NF Harapan Rumbai">
                    <x-app-logo-icon class="text-base font-extrabold" />
                </a>

                <div class="mt-6">
                    <span class="inline-flex items-center rounded-full bg-accent/10 px-3 py-1 text-xs font-bold text-accent">
                        Status {{ $code }}
                    </span>
                </div>

                <h1 class="mt-4 text-2xl sm:text-3xl font-extrabold tracking-tight text-zinc-950">{{ $title }}</h1>
                <p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-zinc-600">{{ $description }}</p>

                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    <button type="button" class="ui-btn text-xs font-semibold" onclick="history.back()">
                        Kembali ke Halaman Sebelumnya
                    </button>
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="ui-btn ui-btn-primary text-xs font-bold shadow-xs">
                        {{ $action }}
                    </a>
                </div>

                <p class="mt-8 text-xs text-zinc-400">
                    Jika kendala masih berlanjut, silakan laporkan ke pemilik toko.
                </p>
            </div>
        </main>
    </body>
</html>
