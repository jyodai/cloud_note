<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChangeUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:change-password {id : The ID of the user} {password : The new password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change user password';

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
     * @return mixed
     */
    public function handle()
    {
        $id       = $this->argument('id');
        $password = $this->argument('password');

        $user = User::find($id);
        if (!$user) {
            $this->error('User not found.');
            return;
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info('Password updated successfully for User ID: ' . $id);
    }
}
