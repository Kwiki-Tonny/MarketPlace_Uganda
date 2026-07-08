<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        Setting::set('site_name', 'MarketPlace Uganda', 'general');
        Setting::set('site_email', 'info@marketplaceuganda.com', 'general');
        Setting::set('site_phone', '0790480453', 'general');
        Setting::set('site_address', 'Kampala, Uganda', 'general');

        Setting::set('payment_methods', json_encode([
            'mobile_money' => [
                'name' => 'MTN Mobile Money',
                'number' => '0777123456',
                'instructions' => 'Send payment to the number above and share confirmation code.'
            ],
            'airtel_money' => [
                'name' => 'Airtel Money',
                'number' => '0788123456',
                'instructions' => 'Send payment to the number above and share confirmation code.'
            ]
        ]), 'payment');
    }
}