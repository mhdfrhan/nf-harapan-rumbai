<?php

namespace App\Console\Commands;

use App\Domain\Payables\Enums\PayableStatus;
use App\Domain\Shared\ValueObjects\Quantity;
use App\Models\Payable;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReconcileBusinessData extends Command
{
    protected $signature = 'store:reconcile {--fail-on-difference : Return a failing exit code when a difference is found}';

    protected $description = 'Compare stock and payable snapshots with their immutable transaction records';

    public function handle(): int
    {
        $differences = [];

        Product::query()->with('stockBalance')->chunkById(250, function ($products) use (&$differences): void {
            foreach ($products as $product) {
                $ledger = (string) DB::table('stock_movements')->where('product_id', $product->id)->sum('quantity_delta');
                $snapshot = (string) ($product->stockBalance->quantity_on_hand ?? '0.0000');
                if (Quantity::from($ledger)->scaled !== Quantity::from($snapshot)->scaled) {
                    $differences[] = ["Stok {$product->sku}", $ledger, $snapshot];
                }
            }
        });

        Payable::query()->chunkById(250, function ($payables) use (&$differences): void {
            foreach ($payables as $payable) {
                $posted = (int) $payable->payments()->where('status', 'posted')->sum('amount_rupiah');
                $expectedBalance = $payable->status === PayableStatus::Voided ? 0 : $payable->principal_rupiah - $posted;
                if ($posted !== $payable->paid_rupiah || $expectedBalance !== $payable->balance_rupiah) {
                    $differences[] = ["Kewajiban {$payable->number}", "bayar={$posted}; sisa={$expectedBalance}", "bayar={$payable->paid_rupiah}; sisa={$payable->balance_rupiah}"];
                }
            }
        });

        if ($differences === []) {
            $this->info('Rekonsiliasi selesai tanpa selisih.');

            return self::SUCCESS;
        }

        $this->error('Ditemukan '.count($differences).' selisih. Tidak ada data yang diubah.');
        $this->table(['Objek', 'Hasil ledger', 'Snapshot'], $differences);

        return $this->option('fail-on-difference') ? self::FAILURE : self::SUCCESS;
    }
}
