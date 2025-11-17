<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Division;
use App\Models\Institution;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GenerateUserUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all divisions
        $divisions = Division::all();

        // Get Polda Jawa Barat institution for assignment
        $poldaJabar = Institution::where('name', 'Polda Jawa Barat')->first();

        // Generate users for each division
        foreach ($divisions as $division) {
            // Skip Polda Jawa Barat parent division (type: satwil)
            if ($division->type === 'satwil') {
                continue;
            }

            // Generate username based on division name
            $baseUsername = Str::slug($division->name, '_');
            $username = 'user_' . $baseUsername;
            $email = $baseUsername . '@arsipberkas-propam.id';

            // Create user with Operator role
            $user = User::updateOrCreate(
                ['username' => $username],
                [
                    'institution_id' => $poldaJabar?->id,
                    'division_id' => $division->id,
                    'name' => 'Operator ' . $division->name,
                    'email' => $email,
                    'password' => Hash::make('ArsipPropam@2025!!'),
                    'email_verified_at' => now(),
                ]
            );

            // Assign Operator role
            $user->assignRole('Operator');

            $this->command->info("Created user: {$user->name} (username: {$username}) for division: {$division->name}");
        }

        $this->command->info('User generation completed successfully!');
        $this->command->info('All users use password: ArsipPropam@2025!!');
    }
}
