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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::where('email', getenv('SUPER_ADMIN_EMAIL'))->first();
        if ($user) {
            $user->assignRole('super-admin');
            $this->info('Super-Admin rights granted to '.$user->email);
        }
    }
}
