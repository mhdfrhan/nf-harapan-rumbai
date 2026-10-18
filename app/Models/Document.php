<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasPublicId, SoftDeletes;

    protected $fillable = ['attachable_type', 'attachable_id', 'category', 'original_name', 'storage_disk', 'storage_path', 'detected_mime', 'extension', 'size_bytes', 'sha256', 'uploaded_by', 'scanned_status'];

    protected function casts(): array
    {
        return ['size_bytes' => 'integer'];
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    /** @return BelongsTo<User, $this> */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
