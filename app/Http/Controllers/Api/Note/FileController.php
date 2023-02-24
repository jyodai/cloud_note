<?php

namespace App\Http\Controllers\Api\Note;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use Response;
use FInfo;
use File;
use Storage;
use URL;

class FileController extends Controller
{
    public $user = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user= auth()->user();
            return $next($request);
        });
        $this->checkDir('fileToFile');
    }

    public function getFile(Request $request)
    {
        $ret = null;
        $type = $request->type;
        $noteId = (int) $request->noteId;
        switch($type) {
            case 'list':
                $this->checkDir('fileToFile/' . $this->user->id);
                $this->checkDir('fileToFile/' . $this->user->id . '/' . $noteId);
        
                $path = storage_path('fileToFile/' . $this->user->id . '/' . $noteId . '/' .'*');
                $fileList = glob($path);
                $ret = [];
                $i = 0;
                foreach ($fileList as $file) {
                    $fileName = explode('/', $file);
                    $fileName = end($fileName);
                    $baseUrl = URL::current();
                    $baseUrl = str_replace('file-to-file-list', '', $baseUrl);
                    $fileUrl = $baseUrl . $fileName;
                    $ret[$i]['fileName'] = $fileName;
                    $ret[$i]['fileUrl'] = $fileUrl;
                    $i++;
                }
                $ret = response()->json($ret);
                break;
            case 'download':
                $fileName = $request->fileName;
                $pathToFile = storage_path('fileToFile/' . $this->user->id . '/' . $noteId . '/' . $fileName);
                $info = new FInfo(FILEINFO_MIME_TYPE);
                $mine_type = $info->file($pathToFile);
                $contents = File::get($pathToFile);
                $ret = Response::make($contents, 200);
                $ret->header('Content-Type', $mine_type);
                break;
        }
        return $ret;
    }

    public function addFile(Request $request)
    {
        $noteId = (int) $request->noteId;

        $ret = [];
        $ret['message'] = '';
        $path = storage_path('fileToFile/' . $this->user->id . '/' . $noteId . '/');
        if (!file_exists($path)) {
            mkdir($path, 0777);
            chmod($path, 0777);
        }
        
        for ($i = 0; $i < count($_FILES['file']['tmp_name']); $i++) {
            $fileName = $_FILES['file']['name'][$i];
            if (is_uploaded_file($_FILES['file']['tmp_name'][$i])){
                if (file_exists($path . $_FILES['file']['name'][$i])) {
                    $ret['message'] .= $fileName . "：アップロード失敗。同名のファイルが存在します\n";
                } elseif (preg_match('#[\\\:?<>|]|\.{1,2}/#', $_FILES['file']['tmp_name'][$i])){
                    $ret['message'] .= $fileName . "：ファイルに使用できな文字が含まれています。「\,:,?,<,>,|」、「./」、「../」\n";
                    unlink( $_FILES['file']['tmp_name'][$i]);
                } elseif (move_uploaded_file($_FILES['file']['tmp_name'][$i], $path . $_FILES['file']['name'][$i])) {
                    $ret['message'] .= $fileName. "：ファイルのアップロードに成功しました\n";
                    chmod($path . $_FILES['file']['name'][$i], 0777);
                } else {
                    $ret['message'] .= $fileName . "：ファイルのアップロードに失敗しました\n";
                }
            } else {
                $ret['message'] .= $fileName . "：ファイルのアップロードに失敗しました。管理者にご連絡してください。\n";
            }
        }
        return response()->json($ret);
    }

    public function editFile(Request $request)
    {
        $ret = [];
        $newFileName = $request->newFileName;
        $originFileName = $request->originFileName;
        $noteId = (int) $request->noteId;

        $path = storage_path('fileToFile/' . $this->user->id . '/' . $noteId . '/');
        if (file_exists($path . $newFileName)) {
            $ret['message'] = '編集失敗。同名のファイルが存在します';
        } elseif (preg_match('#[\\\:?<>|]|\.{1,2}/#', $newFileName)){
            $ret['message'] = 'ファイルに使用できな文字が含まれています。「\,:,?,<,>,|」、「./」、「../」';
        } elseif (rename( $path . $originFileName, $path . $newFileName)) {
            $ret['message'] = "ファイル名を {$originFileName} から {$newFileName} に変更しました";
        } else {
            $ret['message'] = 'ファイルの編集に失敗しました。管理者にご連絡してください。';
        }
        return response()->json($ret);
    }

    public function deleteFile(Request $request)
    {
        $ret = [];
        $originFileName = $request->originFileName;
        $noteId = $request->noteId;

        $path = storage_path('fileToFile/' . $this->user->id . '/' . $noteId . '/');
        if (file_exists($path . $originFileName)) {
            unlink($path . $originFileName);
            $ret['message'] = "{$originFileName}を削除しました";
        } else {
            $ret['message'] = "{$originFileName}は存在しません";
        }
        return response()->json($ret);
    }

    protected function checkDir($fileName)
    {
        $path = storage_path($fileName);
        if (!file_exists($path)) {
            mkdir($path, 0777);
            chmod($path, 0777);
        }
    }
}
