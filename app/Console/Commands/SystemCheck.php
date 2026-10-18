<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SystemCheck extends Command
{
    protected $signature = 'store:check';

    protected $description = 'Verify critical runtime configuration, database access, and private storage';

    public function handle(): int
    {
        $errors = [];
        try {
            DB::select('select 1');
        } catch (Throwable $exception) {
            $errors[] = 'Database: '.$exception->getMessage();
        }

        $probe = '.health-'.bin2hex(random_bytes(8));
        try {
            if (! Storage::disk('private')->put($probe, 'ok') || Storage::disk('private')->get($probe) !== 'ok') {
                $errors[] = 'Private storage tidak dapat dibaca dan ditulis.';
            }
        } catch (Throwable $exception) {
            $errors[] = 'Private storage: '.$exception->getMessage();
        } finally {
            Storage::disk('private')->delete($probe);
        }

        if (app()->isProduction()) {
            if (config('app.debug')) {
                $errors[] = 'APP_DEBUG harus false pada production.';
            }
            if (! str_starts_with((string) config('app.url'), 'https://')) {
                $errors[] = 'APP_URL production harus memakai HTTPS.';
            }
            if (! config('session.secure')) {
                $errors[] = 'SESSION_SECURE_COOKIE harus true pada production.';
            }
            if (! config('session.http_only') || ! config('session.encrypt')) {
                $errors[] = 'Session production harus HttpOnly dan terenkripsi.';
            }
            if (config('database.default') !== 'mysql') {
                $errors[] = 'Database production harus menggunakan koneksi MySQL yang disiapkan khusus aplikasi.';
            }
            if (blank(config('app.key'))) {
                $errors[] = 'APP_KEY belum diisi.';
            }
        }

        if ($errors !== []) {
            foreach ($errors as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $this->info('Pemeriksaan sistem berhasil.');

        return self::SUCCESS;
    }
}
