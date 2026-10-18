<?php

namespace App\Domain\Inventory\Enums;

enum StockMovementType: string
{
    case OpeningBalance = 'opening_balance';
    case PurchaseReceipt = 'purchase_receipt';
    case PurchaseVoid = 'purchase_void';
    case Sale = 'sale';
    case SaleVoid = 'sale_void';
    case AdjustmentIn = 'adjustment_in';
    case AdjustmentOut = 'adjustment_out';
}
