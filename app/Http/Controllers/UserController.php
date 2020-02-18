<?php

namespace App\Http\Controllers;

use App\Http\Resources\Task as TaskResource;
use App\Http\Services\JsonResponseService;
use App\Task;
use App\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /**
     * @var JsonResponseService $responseService
     */
    private $responseService;

    /**
     * UserController constructor.
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
        $users = User::with(['tasks'])->paginate(5);
        return $this
            ->responseService
            ->response(
                UserResource::collection($users)
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
            $user = isset($request->id) ? User::with(['tasks'])->findOrFail($request->id) : new User();
            $data = $request->all();
            $user = $user->fill($data);
            $user->save();
            if (array_key_exists('tasks', $data) && is_array($data['tasks']) && $data != []) {
                $tasks = $data['tasks'];
                foreach ($tasks as $t) {
                    $task = new Task($t);
                    $user->tasks()->save($task);
                }
            }
            return $this->responseService
                ->response(
                    new UserResource($user)
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
        $user = User::with(['tasks'])->find($id);
        if ($user != null) {
            return $this
                ->responseService
                ->response(
                    new UserResource($user)
                );
        } else {
            return $this->responseService
                ->response(
                    [],
                    ["Couldn't find user."]
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
            $user = User::with(['tasks'])->findOrFail($id);
            if ($user->delete()) {
                return $this->responseService
                    ->response(new UserResource($user));
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
