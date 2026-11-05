<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasPublicId;

    public $timestamps = false;

    protected $fillable = ['occurred_at', 'actor_id', 'event', 'auditable_type', 'auditable_id', 'request_id', 'ip_address', 'user_agent_summary', 'before_json', 'after_json', 'metadata_json'];

    protected function casts(): array
    {
        return ['occurred_at' => 'datetime', 'before_json' => 'array', 'after_json' => 'array', 'metadata_json' => 'array'];
    }

    /** @return BelongsTo<User, $this> */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function getEventLabelAttribute(): string
    {
        return match ($this->event) {
            'sale.posted' => 'Transaksi Penjualan Kasir Disimpan',
            'sale.voided' => 'Transaksi Penjualan Dibatalkan (Void)',
            'auth.login.succeeded' => 'Pengguna Berhasil Masuk',
            'auth.login.failed' => 'Gagal Masuk (Kata Sandi Salah)',
            'auth.logout' => 'Pengguna Keluar Sistem',
            'receipt.posted' => 'Penerimaan Barang Masuk Disimpan',
            'catalog.product_created', 'product.created' => 'Produk Baru Ditambahkan',
            'catalog.product_updated', 'product.updated' => 'Data Produk Diperbarui',
            'stock.adjusted' => 'Koreksi Stok Fisik (Penyesuaian)',
            'payable.payment_posted' => 'Pembayaran Hutang Pemasok Dicatat',
            'payable.payment_voided' => 'Pembayaran Hutang Dibatalkan',
            'user.role_changed' => 'Peran Pengguna Diubah',
            'report.exported' => 'Laporan Diunduh (Ekspor CSV)',
            'document.downloaded' => 'Berkas Bukti Nota Diunduh',
            default => str($this->event)->replace('.', ' - ')->headline()->toString(),
        };
    }

    public function getEventBadgeColorAttribute(): string
    {
        return match ($this->event) {
            'sale.posted', 'auth.login.succeeded', 'payable.payment_posted' => 'emerald',
            'receipt.posted', 'catalog.product_created', 'product.created', 'stock.adjusted' => 'blue',
            'catalog.product_updated', 'product.updated', 'user.role_changed' => 'amber',
            'sale.voided', 'auth.login.failed', 'payable.payment_voided' => 'rose',
            default => 'zinc',
        };
    }
}
