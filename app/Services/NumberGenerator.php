<?php

namespace App\Services;

use App\Models\BusinessSequence;
use Carbon\CarbonInterface;

class NumberGenerator
{
    public function next(string $type, CarbonInterface $date): string
    {
        $day = $date->toDateString();
        BusinessSequence::query()->firstOrCreate(
            ['type' => $type, 'sequence_date' => $day],
            ['last_value' => 0],
        );

        $sequence = BusinessSequence::query()
            ->where('type', $type)
            ->where('sequence_date', $day)
            ->lockForUpdate()
            ->firstOrFail();
        $sequence->increment('last_value');

        return sprintf('%s-%s-%04d', $type, $date->format('Ymd'), $sequence->fresh()->last_value);
    }
}
