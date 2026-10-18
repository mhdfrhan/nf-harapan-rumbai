<?php

namespace App\Domain\Sales\Enums;

enum SaleStatus: string
{
    case Draft = 'draft';
    case Posted = 'posted';
    case Cancelled = 'cancelled';
    case Voided = 'voided';
}
