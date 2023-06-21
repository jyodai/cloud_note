<?php

namespace App\Console\Commands;

use App\Consts\User as C_User;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

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
        $user = [
            'name'      => 'admin',
            'user_type' => C_User::USER_TYPE_ADMIN,
            'email'     => 'admin@example.com',
            'password'  => Str::random(8),
        ];
        User::create([
            'name'      => $user['name'],
            'user_type' => $user['user_type'],
            'email'     => $user['email'],
            'password'  => Hash::make($user['password']),
            'api_token' => Str::random(60),
        ]);

        $this->info('User name: ' . $user['name']);
        $this->info('User password: ' . $user['password']);
    }
}
