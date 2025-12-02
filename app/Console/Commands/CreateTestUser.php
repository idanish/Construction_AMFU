<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-test {email=test@example.com} {name=Test User}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user and send welcome email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->argument('name');

        $this->info("Creating test user: {$name} ({$email})...");

        // Check if user already exists
        $existing = User::where('email', $email)->first();
        if ($existing) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        // Get or create a test department
        $department = Department::first() ?? Department::create([
            'name' => 'Default Department',
            'code' => 'DEFAULT'
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'username' => str_replace('@', '_', explode('@', $email)[0]),
                'password' => Hash::make('password123'),
                'department_id' => $department->id,
                'status' => 1 // 1 = active
            ]);

            // UserObserver will automatically send welcome email on create event

            $this->info("✓ User created successfully!");
            $this->info("Email: {$email}");
            $this->info("Name: {$name}");
            $this->info("Welcome email has been sent to: {$email}");
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to create user: ' . $e->getMessage());
            return 1;
        }
    }
}
