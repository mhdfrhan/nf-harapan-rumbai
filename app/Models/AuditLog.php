<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasPublicId;

    public $timestamps = false;

    protected $fillable = ['occurred_at', 'actor_id', 'event', 'auditable_type', 'auditable_id', 'request_id', 'ip_address', 'user_agent_summary', 'before_json', 'after_json', 'metadata_json'];

    protected function casts(): array
    {
        return ['occurred_at' => 'datetime', 'before_json' => 'array', 'after_json' => 'array', 'metadata_json' => 'array'];
    }

    /** @return BelongsTo<User, $this> */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
