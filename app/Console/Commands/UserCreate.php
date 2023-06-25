<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $users = [
            [
                'name'     => 'hoge',
                'email'    => 'hoge@example.com',
                'password' => 'hoge',
            ],
            [
                'name'     => 'fuga',
                'email'    => 'fuga@example.com',
                'password' => 'fuga',
            ],
            [
                'name'     => 'piyo',
                'email'    => 'piyo@example.com',
                'password' => 'piyo',
            ],
        ];

        echo "\n";
        foreach ($users as $user) {
            User::create([
                'name'      => $user['name'],
                'email'     => $user['email'],
                'password'  => Hash::make($user['password']),
                'api_token' => Str::random(60),
            ]);

            echo $user['name'] . 'を作成しました' . "\n";
            echo 'user email    : ' . $user['email'] . "\n";
            echo 'user password : ' . $user['password'] . "\n";
            echo "\n";
        }
        echo "\n";
    }
}
