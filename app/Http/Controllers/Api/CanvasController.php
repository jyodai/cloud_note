<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Canvas\UpdateRequest;
use App\Http\Resources\Canvas\CanvasResource;
use App\Models\Canvas;
use Illuminate\Http\Response;

class CanvasController extends Controller
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

    public function update(int $id, UpdateRequest $request): Response
    {
        $element = Canvas::findOrFail($id);
        $element->fill($request->validated())->save();
        return response()->noContent();
    }
}
