<?php

use Illuminate\Database\Seeder;

use App\Models\Note;

class NotesTableSeeder extends Seeder
{
    protected $note;
    public function __construct(){
        $this->note = new Note();
    }
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notes')->truncate();
        DB::table('notes_contents')->truncate();
        // DB::table('notes')->insert([
        //   [
        //     'name' => 'admin',
        //     'email' => 'admin@exsample.com',
        //     'password' => Hash::make('admin'),
        //   ]
        // ]);
    }
}
