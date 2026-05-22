<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function casts(): array
    {
        return [
            'email' => 'string',
            'phone' => 'string',
            'address' => 'string',
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
