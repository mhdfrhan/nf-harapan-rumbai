<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ReadinessController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $probe = '.readiness-'.bin2hex(random_bytes(8));

        try {
            DB::select('select 1');
            if (! Storage::disk('private')->put($probe, 'ok')) {
                throw new \RuntimeException('Private storage is not writable.');
            }
            Storage::disk('private')->delete($probe);

            return response()->json(['status' => 'ready']);
        } catch (Throwable) {
            Storage::disk('private')->delete($probe);

            return response()->json(['status' => 'unavailable'], 503);
        }
    }
}
