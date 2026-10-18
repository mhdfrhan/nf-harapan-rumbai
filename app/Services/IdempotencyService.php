<?php

namespace App\Services;

use App\Models\IdempotencyKey;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class IdempotencyService
{
    /**
     * @template T of Model
     *
     * @param  array<string, mixed>  $payload
     * @param  class-string<T>  $resultClass
     * @param  Closure(int): T  $resolver
     * @param  Closure(): T  $callback
     * @return T
     */
    public function run(User $actor, string $action, string $key, array $payload, string $resultClass, Closure $resolver, Closure $callback): Model
    {
        return DB::transaction(function () use ($actor, $action, $key, $payload, $resultClass, $resolver, $callback) {
            $hash = hash('sha256', json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE));
            $record = IdempotencyKey::query()->where(['user_id' => $actor->id, 'action' => $action, 'key' => $key])->lockForUpdate()->first();

            if ($record) {
                if (! hash_equals($record->request_hash, $hash)) {
                    throw ValidationException::withMessages(['idempotency_key' => 'Permintaan yang sama tidak boleh digunakan untuk data berbeda.']);
                }
                if ($record->status === 'completed' && $record->result_type && $record->result_id) {
                    if ($record->result_type !== $resultClass) {
                        throw ValidationException::withMessages(['idempotency_key' => 'Hasil permintaan tersimpan tidak sesuai dengan jenis transaksi.']);
                    }

                    return $resolver($record->result_id);
                }
                throw ValidationException::withMessages(['idempotency_key' => 'Permintaan masih diproses. Tunggu sebentar lalu periksa kembali.']);
            }

            $record = IdempotencyKey::query()->create(['key' => $key, 'user_id' => $actor->id, 'action' => $action, 'request_hash' => $hash, 'status' => 'processing', 'expires_at' => now()->addDays(7)]);
            $result = $callback();
            $record->update(['status' => 'completed', 'result_type' => $result::class, 'result_id' => $result->getKey()]);

            return $result;
        }, 3);
    }
}
