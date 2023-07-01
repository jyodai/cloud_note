<?php

namespace App\Console\Commands;

use App\Consts\User as C_User;
use App\Models\User;
use App\Models\NoteSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
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
        $userModel = new User();
        $password  = Str::random(8);
        $data      = [
            'name'      => 'admin',
            'user_type' => C_User::USER_TYPE_ADMIN,
            'email'     => 'admin@example.com',
            'password'  => Hash::make($password),
            'api_token' => Str::random(60),
        ];

        if ($userModel->existsUser($data['email'])) {
            $this->error('User with the same email already exists.');
            return;
        }

        $user = $userModel->create($data);

        NoteSetting::create([
            'user_id'       => $user->id,
            'editor_option' => '{}',
        ]);

        $this->info('User name: ' . $user->name);
        $this->info('User password: ' . $password);
    }
}
