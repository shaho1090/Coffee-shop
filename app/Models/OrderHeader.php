<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class OrderHeader extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = [
        'total_price',
        'status',
    ];

    protected $with = [
        'lines'
    ];

    public function getTotalPriceAttribute()
    {
        return $this->lines()->get()->sum('line_total_price');
    }

    public function getStatusAttribute()
    {
        return OrderStatus::find($this->status_id)->title;
    }

    public function lines(): HasMany
    {
        return $this->hasMany(OrderLine::class, 'header_id', 'id');
    }

    public function createNew()
    {
        return $this->create([
            'date' => Carbon::now()->toDateTimeString(),
            'user_id' => auth()->id(),
            'status_id' => OrderStatus::waiting()->id,
        ]);
    }

    public function addLines(array $orderLines): OrderHeader
    {
        foreach ($orderLines as $line) {
            $this->lines()->create([
                'product_variant_id' => $line['product_variant_id'],
                'quantity' => $line['quantity'],
            ]);
        }

        return $this;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param array $lines
     */
    public function updateLines(array $lines)
    {
        foreach ($lines as $line) {
            $orderLine = $this->lines()->find($line['line_id']);
            unset($line['line_id']);
            $orderLine->update($line);
        }
    }

    public function isInWaitingState(): bool
    {
        return $this->status_id == OrderStatus::waiting()->id;
    }

}
