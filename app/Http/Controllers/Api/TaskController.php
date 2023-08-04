<?php

namespace App\Http\Controllers\Api;

use App\Consts\Common as C_Common;
use App\Http\Controllers\Controller;
use App\Http\Resources\Task\TreeResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaskController extends Controller
{
    public $user = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    public function showFinishedTree(int $id): ResourceCollection
    {
        $tree      = Task::getTree($id, C_Common::FLAG_ON);
        $resources = TreeResource::collection($tree);
        return $resources;
    }

    public function showUnfinishedTree(int $id): ResourceCollection
    {
        $tree      = Task::getTree($id, C_Common::FLAG_OFF);
        $resources = TreeResource::collection($tree);
        return $resources;
    }

    public function showTree(int $id): ResourceCollection
    {
        $tree      = Task::getTree($id);
        $resources = TreeResource::collection($tree);
        return $resources;
    }
}
