<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subscription_plans')->insert([
            [
                'subscription_plan_name' => 'Bronze',
                'subscription_plan_description' => 'Basic plan with limited features',
                'subscription_plan_price' => 9.99,
                'subscription_plan_duration' => 30, // duration in days
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subscription_plan_name' => 'Silver',
                'subscription_plan_description' => 'Intermediate plan with additional features',
                'subscription_plan_price' => 19.99,
                'subscription_plan_duration' => 30, // duration in days
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'subscription_plan_name' => 'Gold',
                'subscription_plan_description' => 'Premium plan with all features',
                'subscription_plan_price' => 29.99,
                'subscription_plan_duration' => 30, // duration in days
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
