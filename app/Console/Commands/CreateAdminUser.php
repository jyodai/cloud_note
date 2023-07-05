<?php

namespace App\Console\Commands;

use App\Consts\User as C_User;
use App\Models\NoteSetting;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
{
    private $userService;

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
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
        $attrs     = [
            'name'      => 'admin',
            'user_type' => C_User::USER_TYPE_ADMIN,
            'email'     => 'admin@example.com',
            'password'  => $password,
        ];

        if ($userModel->existsUser($attrs['email'])) {
            $this->error('User with the same email already exists.');
            return;
        }

        $user = $this->userService->create($attrs);

        $this->info('User name: ' . $user->name);
        $this->info('User password: ' . $password);
    }
}
