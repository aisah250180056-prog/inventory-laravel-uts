<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'image',
        'category_id',
        'supplier_id',
        'price',
        'purchase_price',
        'selling_price',
        'minimum_stock',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'price' => 'decimal:2',
            'stock' => 'integer',
            'minimum_stock' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function ($product) {
            if ($product->selling_price !== null && $product->price === null) {
                $product->price = $product->selling_price;
            } elseif ($product->price !== null && $product->selling_price === null) {
                $product->selling_price = $product->price;
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->minimum_stock;
    }

    public function hasTransactions(): bool
    {
        return $this->purchaseItems()->exists() 
            || $this->saleItems()->exists() 
            || $this->stockMovements()->exists();
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
                return $this->image;
            }
            if (file_exists(public_path('uploads/products/' . $this->image))) {
                return asset('uploads/products/' . $this->image);
            }
            if (file_exists(public_path('storage/' . $this->image))) {
                return asset('storage/' . $this->image);
            }
        }

        // Default placeholder SVG image data URL
        $nameInitial = urlencode($this->name ?: 'Item');
        return "https://placehold.co/400x300/e2e8f0/1e293b?text=" . $nameInitial;
    }
}
