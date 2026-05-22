<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'phone',
        'address',
        'status',
    ];

    public function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    // /**
    //  * Get the customer's subscriptions.
    //  *
    //  * @return HasMany<Subscription, $this>
    //  */
    // public function subscriptions()
    // {
    //     return $this->hasMany(Subscription::class);
    // }
}
