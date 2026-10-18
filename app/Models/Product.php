<?php

namespace App\Models;

use App\Models\Concerns\HasPublicId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $sku
 * @property string $name
 * @property bool $is_active
 * @property bool $allows_negative_stock
 * @property string $low_stock_threshold
 * @property StockBalance|null $stockBalance
 * @property Collection<int, ProductUnit> $saleUnits
 */
class Product extends Model
{
    use HasPublicId;

    protected $fillable = ['category_id', 'base_unit_id', 'sku', 'name', 'description', 'barcode', 'low_stock_threshold', 'allows_negative_stock', 'is_active', 'lock_version'];

    protected function casts(): array
    {
        return ['low_stock_threshold' => 'decimal:4', 'allows_negative_stock' => 'boolean', 'is_active' => 'boolean', 'lock_version' => 'integer'];
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @return BelongsTo<Unit, $this> */
    public function baseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    /** @return HasMany<ProductUnit, $this> */
    public function saleUnits(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    /** @return HasOne<StockBalance, $this> */
    public function stockBalance(): HasOne
    {
        return $this->hasOne(StockBalance::class);
    }

    /** @return HasMany<StockMovement, $this> */
    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * @param  Builder<Product>  $query
     * @return Builder<Product>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn (Builder $q, string $value) => $q->where(function (Builder $inner) use ($value): void {
            $inner->where('name', 'like', "%{$value}%")->orWhere('sku', 'like', "%{$value}%")->orWhere('barcode', $value);
        }));
    }
}
