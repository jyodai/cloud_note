<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LibraryFile\UpdateRequest;
use App\Http\Requests\LibraryFile\StoreRequest;
use FInfo;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;
use Storage;
use URL;

class LibraryFileController extends Controller
{
    public $user = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });

        // Todo : ユーザー作成時に作っておくべき
        $this->checkDir();
    }

    public function index()
    {
        $path     = storage_path('userLibrary/' . $this->user->id . '/' . '*');
        $fileList = glob($path);
        $ret      = [];
        $i        = 0;
        foreach ($fileList as $file) {
            $fileName            = explode('/', $file);
            $fileName            = end($fileName);
            $baseUrl             = URL::current();
            $fileUrl             = $baseUrl . '/image?path=' . $fileName . '&type=show' . '&token=%cn_api_token%';
            $ret[$i]['fileName'] = $fileName;
            $ret[$i]['fileUrl']  = $fileUrl;
            $ret[$i]['fileHtml'] = "<img style=\"max-width:500px\" src=\"$fileUrl\">";
            $i++;
        }
        return response()->json($ret);
    }

    public function image(Request $request)
    {
        $pathToFile = storage_path('userLibrary/' . $this->user->id . '/' . $request->path);
        $info       = new FInfo(FILEINFO_MIME_TYPE);
        $mine_type  = $info->file($pathToFile);
        $contents   = File::get($pathToFile);
        $ret        = Response::make($contents, 200);
        $ret->header('Content-Type', $mine_type);
        return $ret;
    }

    public function store(StoreRequest $request)
    {
        $ret            = [];
        $ret['message'] = '';
        $userId         = $this->user->id;
        $path           = storage_path('userLibrary/' . $userId . '/');
        if (!file_exists($path)) {
            mkdir($path, 0777);
            chmod($path, 0777);
        }

        $files = $request->file;
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $path     = Storage::disk('userLibrary')->putFileAs($userId, $file, $fileName);
        }

        return response()->noContent();
    }

    public function update(UpdateRequest $request)
    {
        $ret            = [];
        $newFileName    = $request->newFileName;
        $originFileName = $request->originFileName;
        $path           = storage_path('userLibrary/' . $this->user->id . '/');
        rename($path . $originFileName, $path . $newFileName);
        return response()->noContent();
    }

    public function destroy(Request $request)
    {
        $ret            = [];
        $originFileName = $request->originFileName;
        $path           = storage_path('userLibrary/' . $this->user->id . '/');
        if (file_exists($path . $originFileName)) {
            unlink($path . $originFileName);
            $ret['message'] = "{$originFileName}を削除しました";
        } else {
            $ret['message'] = "{$originFileName}は存在しません";
        }
        return response()->json($ret);
    }

    protected function checkDir()
    {
        $path = storage_path('userLibrary');
        if (!file_exists($path)) {
            mkdir($path, 0777);
            chmod($path, 0777);
        }
    }
}
