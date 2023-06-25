<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NoteContent;

class ConvertLibrary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:library';

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
        echo "\n";
        $noteContents = NoteContent::get();
        $domain       = config('app.xserver_domain');
        foreach ($noteContents as $noteContent) {
            $search         = $domain . '/memo-web/backend/public/api/libraries/files';
            $replace        = $domain . '/cloud_note/public/api/libraries/files';
            $replaceContent = str_replace($search, $replace, $noteContent->content, $num);

            $noteContent->content = $replaceContent;
            $noteContent->save();
            echo "ID:{$noteContent->id} {$num}回置き換えられました \n";
        }
        echo "\n";
    }
}
