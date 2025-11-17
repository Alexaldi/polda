<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Institution;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Polda Jawa Barat institution for assignment
        $poldaJabar = Institution::where('name', 'Polda Jawa Barat')->first();

        // Admin Account - Super Admin level
        $admin = User::updateOrCreate(
            ['email' => 'admin@arsipberkas-propam.id'],
            [
                'institution_id' => $poldaJabar?->id,
                'division_id' => null,
                'username' => 'admin_polda',
                'name' => 'Administrator Polda Jawa Barat',
                'password' => Hash::make('ArsipPropam@2025'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Admin');

        // Kasubbid Accounts - Sub Division Heads
        $kasubbidAccounts = [
            [
                'name' => 'Kasubbid Paminal POLDA',
                'username' => 'kasubbid_paminal',
                'email' => 'kasubbid.paminal@arsipberkas-propam.id',
                'division_name' => 'Subbid Paminal',
            ],
            [
                'name' => 'Kasubbid Provos POLDA',
                'username' => 'kasubbid_provos',
                'email' => 'kasubbid.provos@arsipberkas-propam.id',
                'division_name' => 'Subbid Provos',
            ],
            [
                'name' => 'Kasubbid Wabprof POLDA',
                'username' => 'kasubbid_wabprof',
                'email' => 'kasubbid.wabprof@arsipberkas-propam.id',
                'division_name' => 'Subbid Wabprof',
            ],
        ];

        foreach ($kasubbidAccounts as $account) {
            // Find the division for this kasubbid
            $division = \App\Models\Division::where('name', $account['division_name'])->first();

            $kasubbid = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'institution_id' => $poldaJabar?->id,
                    'division_id' => $division?->id,
                    'username' => $account['username'],
                    'name' => $account['name'],
                    'password' => Hash::make('ArsipPropam@2025'),
                    'email_verified_at' => now(),
                ]
            );
            $kasubbid->assignRole('Kasubbid');

            $this->command->info("Created Kasubbid: {$kasubbid->name} ({$account['email']})");
        }

        $this->command->info('User accounts created successfully!');
        $this->command->info('Login Credentials:');
        $this->command->info('Admin: admin@poldajabar.go.id | Password: P@ld@J@b@r@dm1n2024!');
        $this->command->info('Kasubbid: kasubbid.*@poldajabar.go.id | Password: K@subb1d_Pold@2024!');
    }
}
