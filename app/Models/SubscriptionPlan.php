<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscription_plan_name',
        'subscription_plan_description',
        'subscription_plan_price',
        'subscription_plan_duration',
    ];
}
