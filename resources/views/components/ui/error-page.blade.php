@props(['code', 'title', 'description', 'action' => 'Kembali ke ringkasan'])

<!DOCTYPE html>
<html lang="id">
    <head>@include('partials.head')</head>
    <body class="min-h-screen bg-cream-100 text-zinc-900 antialiased">
        <main class="flex min-h-svh items-center justify-center p-6">
            <div class="w-full max-w-lg text-center">
                <a href="{{ url('/') }}" class="mx-auto flex size-12 items-center justify-center rounded-[14px] bg-accent text-white" aria-label="Toko NF Harapan Rumbai"><x-app-logo-icon /></a>
                <p class="mt-10 text-sm font-bold tracking-[0.12em] text-accent">KESALAHAN {{ $code }}</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-[-0.04em] text-zinc-950">{{ $title }}</h1>
                <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-zinc-600">{{ $description }}</p>
                <div class="mt-7 flex flex-col justify-center gap-2 sm:flex-row">
                    <button type="button" class="ui-btn" onclick="history.back()">Kembali</button>
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="ui-btn ui-btn-primary">{{ $action }}</a>
                </div>
                <p class="mt-10 text-xs text-zinc-500">Jika masalah terus terjadi, catat waktu kejadian dan hubungi pemilik toko.</p>
            </div>
        </main>
    </body>
</html>
