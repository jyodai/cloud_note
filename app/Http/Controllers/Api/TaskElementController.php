<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskElement\MoveRequest;
use App\Http\Requests\TaskElement\StoreRequest;
use App\Http\Requests\TaskElement\UpdateRequest;
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

    public function update(int $id, UpdateRequest $request): TaskElementResource
    {
        $element = TaskElement::findOrFail($id);
        $element->fill($request->validated())->save();
        return new TaskElementResource($element);
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

    public function move(int $id, MoveRequest $request): TaskElementResource
    {
        $targetNoteId = $request['target_task_element_id'];
        $type         = $request['type'];
        $taskElement  = TaskElement::move($id, $targetNoteId, $type);
        return new TaskElementResource($taskElement);
    }
}
