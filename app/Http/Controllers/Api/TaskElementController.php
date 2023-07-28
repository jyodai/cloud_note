<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskElement\StoreRequest;
use App\Http\Resources\TaskElement\TaskElementResource;
use App\Models\TaskElement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class TaskElementController extends Controller
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

    public function show(int $id): TaskElementResource
    {
        $element = TaskElement::find($id);
        return new TaskElementResource($element);
    }

    public function store(StoreRequest $request): TaskElementResource
    {
        $element = TaskElement::createCustom($request->all());
        return new TaskElementResource($element);
    }

    public function update()
    {
    }

    public function destroy(int $id): Response
    {
        $children = TaskElement::getChildren($id);
        foreach ($children as $child) {
            TaskElement::destroy($child->id);
        }
        TaskElement::destroy($id);
        return response()->noContent();
    }
}
