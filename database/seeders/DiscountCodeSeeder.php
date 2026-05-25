<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use Illuminate\Database\Seeder;

class DiscountCodeSeeder extends Seeder
{
    public function run(): void
    {
        $codes = [
            ['code' => 'DISKON10',  'type' => 'percent', 'value' => 10,    'label' => 'Diskon 10%'],
            ['code' => 'DISKON20',  'type' => 'percent', 'value' => 20,    'label' => 'Diskon 20%'],
            ['code' => 'PROMO50',   'type' => 'percent', 'value' => 50,    'label' => 'Diskon 50%'],
            ['code' => 'DISKON100', 'type' => 'percent', 'value' => 100,   'label' => 'Diskon 100% (GRATIS)'],
            ['code' => 'HEMAT10K',  'type' => 'fixed',   'value' => 10000, 'label' => 'Potongan Rp10.000'],
            ['code' => 'HEMAT25K',  'type' => 'fixed',   'value' => 25000, 'label' => 'Potongan Rp25.000'],
            ['code' => 'HEMAT50K',  'type' => 'fixed',   'value' => 50000, 'label' => 'Potongan Rp50.000'],
        ];

        foreach ($codes as $c) {
            DiscountCode::firstOrCreate(['code' => $c['code']], $c);
        }
    }
}
