<?php

namespace App\Http\Controllers;

use App\Http\Resources\Task as TaskResource;
use App\Http\Services\JsonResponseService;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * @var JsonResponseService $responseService
     */
    private $responseService;

    /**
     * TaskController constructor.
     */
    public function __construct()
    {
        $this->responseService = new JsonResponseService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tasks = Task::with(['User'])->paginate(12);
        return $this
            ->responseService
            ->response(
                TaskResource::collection($tasks)
            );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $task = isset($request->id) ? Task::with(['User'])->findOrFail($request->id) : new Task();
            $data = $request->all();
            $task = $task->fill($data);
            $task->save();
            return $this->responseService
                ->response(
                    new TaskResource($task)
                );
        } catch (\Exception $e) {
            return $this->responseService
                ->response(
                    [],
                    [$e->getCode() . ' : ' . $e->getMessage()]
                );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $task = Task::with(['User'])->find($id);
        if ($task != null) {
            return $this
                ->responseService
                ->response(
                    new TaskResource($task)
                );
        } else {
            return $this->responseService
                ->response(
                    [],
                    ["Couldn't find task."]
                );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $task = Task::with(['User'])->findOrFail($id);
            if ($task->delete()) {
                return $this->responseService
                    ->response(new TaskResource($task));
            }
        } catch (\Exception $e) {
            return $this->responseService
                ->response(
                    [],
                    [$e->getcode() . ' : ' . $e->getmessage()]
                );
        }
    }
}
