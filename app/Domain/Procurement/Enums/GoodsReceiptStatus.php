<?php

namespace App\Domain\Procurement\Enums;

enum GoodsReceiptStatus: string
{
    case Draft = 'draft';
    case Posted = 'posted';
    case Cancelled = 'cancelled';
    case Voided = 'voided';
}
