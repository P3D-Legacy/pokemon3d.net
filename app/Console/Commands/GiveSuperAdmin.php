<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GiveSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p3d:givesa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gives Super-Admin rights to the user specifed in the env file.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = config('services.super_admin.email');

        if (! is_string($email) || $email === '') {
            $this->error('Super admin email is not set.');

            return Command::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error('No user found for the configured super admin email.');

            return Command::FAILURE;
        }

        $user->assignRole('super-admin');
        $this->info('Super-Admin rights granted to '.$user->email);

        return Command::SUCCESS;
    }
}
