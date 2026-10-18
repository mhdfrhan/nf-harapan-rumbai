<?php

namespace App\Domain\Payables\Enums;

enum PayableStatus: string
{
    case Unpaid = 'unpaid';
    case PartiallyPaid = 'partially_paid';
    case Paid = 'paid';
    case Overdue = 'overdue';
    case Voided = 'voided';
}
