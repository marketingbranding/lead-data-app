<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->call([
            CabangSeeder::class,
            BankSeeder::class,
            KavlingSeeder::class,
            SalesSeeder::class,
            PromoSeeder::class,
            KonsumenSeeder::class,
            LeadTimeSeeder::class,
            BiCheckingSeeder::class,
            PsjbSeeder::class,
            PemberkasanSeeder::class,
            ProsesBankSeeder::class,
            PpjbDevSeeder::class,
            AkadSeeder::class,
            BastSeeder::class,
            RolePermissionSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
