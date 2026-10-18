<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class DocumentStorage
{
    private const MIME_EXTENSIONS = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'application/pdf' => 'pdf'];

    public function store(UploadedFile $file, User $actor, string $category, ?Model $attachable = null): Document
    {
        abort_unless($actor->hasPermission('documents.upload'), 403);
        if ($attachable && Document::query()->where('attachable_type', class_basename($attachable))->where('attachable_id', $attachable->getKey())->count() >= config('store.documents.max_files_per_transaction')) {
            throw ValidationException::withMessages(['proof' => 'Jumlah bukti untuk transaksi ini sudah mencapai batas.']);
        }
        $mime = $file->getMimeType();
        if (! $mime || ! isset(self::MIME_EXTENSIONS[$mime]) || ! in_array($mime, config('store.documents.allowed_mime_types'), true)) {
            throw ValidationException::withMessages(['proof' => 'Bukti harus berupa JPG, PNG, atau PDF yang valid.']);
        }
        if ($file->getSize() <= 0 || $file->getSize() > config('store.documents.max_kilobytes') * 1024) {
            throw ValidationException::withMessages(['proof' => 'Ukuran bukti tidak valid atau melebihi batas.']);
        }
        if (str_starts_with($mime, 'image/')) {
            $dimensions = @getimagesize($file->getRealPath());
            if (! is_array($dimensions) || ($dimensions[0] * $dimensions[1]) > config('store.documents.max_image_pixels')) {
                throw ValidationException::withMessages(['proof' => 'Dimensi gambar tidak valid atau terlalu besar.']);
            }
        }
        $extension = self::MIME_EXTENSIONS[$mime];
        $path = 'documents/'.now()->format('Y/m').'/'.Str::uuid().'.'.$extension;
        $contents = file_get_contents($file->getRealPath());
        if ($contents === false || ! Storage::disk('private')->put($path, $contents)) {
            throw ValidationException::withMessages(['proof' => 'Bukti gagal disimpan. Silakan coba kembali.']);
        }

        try {
            return Document::query()->create(['attachable_type' => $attachable ? class_basename($attachable) : null, 'attachable_id' => $attachable?->getKey(), 'category' => $category, 'original_name' => Str::limit(basename($file->getClientOriginalName()), 255, ''), 'storage_disk' => 'private', 'storage_path' => $path, 'detected_mime' => $mime, 'extension' => $extension, 'size_bytes' => $file->getSize(), 'sha256' => hash('sha256', $contents), 'uploaded_by' => $actor->id]);
        } catch (Throwable $exception) {
            Storage::disk('private')->delete($path);

            throw $exception;
        }
    }
}
