<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function download(Document $document, AuditLogger $audit): StreamedResponse
    {
        abort_unless(request()->user()->hasPermission('documents.view'), 403);
        abort_unless(Storage::disk($document->storage_disk)->exists($document->storage_path), 404);
        $audit->record('document.downloaded', request()->user(), $document);

        return Storage::disk($document->storage_disk)->download($document->storage_path, $document->original_name, ['X-Content-Type-Options' => 'nosniff', 'Cache-Control' => 'private, no-store']);
    }
}
