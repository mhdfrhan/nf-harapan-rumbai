<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuditLogger
{
    private const REDACTED = ['password', 'password_confirmation', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'];

    /**
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     * @param  array<string, mixed>  $metadata
     */
    public function record(string $event, ?User $actor = null, ?Model $target = null, array $before = [], array $after = [], array $metadata = []): AuditLog
    {
        /** @var Request|null $request */
        $request = app()->bound('request') ? request() : null;

        return AuditLog::query()->create([
            'occurred_at' => now(),
            'actor_id' => $actor?->id,
            'event' => $event,
            'auditable_type' => $target ? class_basename($target) : null,
            'auditable_id' => $target?->getKey(),
            'request_id' => $request?->attributes->get('request_id'),
            'ip_address' => $request?->ip(),
            'user_agent_summary' => $request ? mb_substr((string) $request->userAgent(), 0, 255) : null,
            'before_json' => $this->redact($before),
            'after_json' => $this->redact($after),
            'metadata_json' => $this->redact($metadata),
        ]);
    }

    /**
     * @param  array<array-key, mixed>  $data
     * @return array<array-key, mixed>
     */
    private function redact(array $data): array
    {
        $redacted = [];
        foreach ($data as $key => $value) {
            if (is_string($key) && in_array(mb_strtolower($key), self::REDACTED, true)) {
                continue;
            }
            $redacted[$key] = is_array($value) ? $this->redact($value) : $value;
        }

        return $redacted;
    }
}
