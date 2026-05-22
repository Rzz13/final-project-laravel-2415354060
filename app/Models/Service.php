<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'status',
    ];

    function casts(): array
    {
        return [
            'status' => 'boolean',
            'price' => 'integer',
        ];
    }

    /**
     * Get the service's price in dollars.
     *
     * @return HasMany<Subscription, $this>
     */

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
