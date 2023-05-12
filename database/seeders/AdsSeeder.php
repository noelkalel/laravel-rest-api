<?php

namespace Database\Seeders;

use App\Models\Ad;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AdsSeeder extends Seeder
{
    public function run(): void
    {
        Ad::create([
            'user_id'     => 1,
            'text'        => 'text',
            'valid_until' => Carbon::now()->addDays(3),
        ]);

        Ad::create([
            'user_id'     => 2,
            'text'        => 'text two',
            'valid_until' => Carbon::now()->addDays(4),
        ]);
    }
}
