<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:users.create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $firstName = text('First Name', required: true);
        $lastName = text('Last Name', required: true);
        $username = text('Username', required: true, validate: ['unique:users,username']);
        $email = text('Email', required: true, validate: ['email', 'unique:users,email']);
        $password = password('Password', required: true);
        $admin = confirm('Is this user an administrator?', default: false);

        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'admin' => $admin,
        ]);

        $user->generateTwoFASecret();

        $this->info('User created successfully');
    }
}
