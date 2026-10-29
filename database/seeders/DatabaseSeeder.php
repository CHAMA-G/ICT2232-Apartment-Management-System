<?php

namespace Database\Seeders;

use App\Models\Flat;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(FlatSeeder::class);

        // User::factory(10)->create();

        if (! User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'resident',
            ]);
        }

        if (! User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        if (! User::where('email', 'security@example.com')->exists()) {
            User::create([
                'name' => 'Security Guard',
                'email' => 'security@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'security',
                'email_verified_at' => now(),
            ]);
        }

        $flatNumbers = Flat::pluck('flat_number')->toArray();

        if ($flatNumbers !== []) {
            DB::table('users')
                ->where(function ($query) {
                    $query->whereNull('flat_number')->orWhere('flat_number', '');
                })
                ->select('id')
                ->orderBy('id')
                ->get()
                ->each(function ($user) use ($flatNumbers) {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update([
                            'flat_number' => $flatNumbers[array_rand($flatNumbers)],
                            'updated_at' => now(),
                        ]);
                });
        }
    }
}
