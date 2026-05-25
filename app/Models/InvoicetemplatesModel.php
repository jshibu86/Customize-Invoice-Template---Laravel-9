<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicetemplatesModel extends Model
{
    use HasFactory;

    protected $table = 'invoice_templates';
    protected $guarded = [];

     protected $casts = [
        'layout'        => 'array',
        'fields_config' => 'array',
        'is_active'     => 'boolean',
    ];

    public static function forSubscriber(int $subscriberId): self
    {
        return static::where('subscriber_id', $subscriberId)
                     ->where('is_active', true)
                     ->firstOrFail();
    }
}
