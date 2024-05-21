<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTaskAPIRequest;
use App\Http\Requests\API\UpdateTaskAPIRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class TaskAPIController
 */
class TaskAPIController extends AppBaseController
{
    private TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepo)
    {
        $this->taskRepository = $taskRepo;
    }

    /**
     * Display a listing of the Tasks.
     * GET|HEAD /tasks
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = $this->taskRepository->all(
            ['*'],
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($tasks->toArray(), 'Tasks retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Store a newly created Task in storage",
     *     operationId="storeTask",
     *     tags={"Tasks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "assigned_to", "priority", "status", "start_date", "due_date"},
     *             @OA\Property(property="name", type="string", example="Design new logo"),
     *             @OA\Property(property="description", type="string", example="Create a new logo for the website"),
     *             @OA\Property(property="assigned_to", type="integer", example="2"),
     *             @OA\Property(property="status", type="string", example="1"),
     *             @OA\Property(property="start", type="date", format="date", example="2024-06-01"),
     *             @OA\Property(property="end", type="date", format="date", example="2024-06-01"),
     *             @OA\Property(property="category_id", type="integer", example="1"),
     *             @OA\Property(property="last_updated_by", type="string", example="John Doe"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task saved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/Task"),
     *             @OA\Property(property="message", type="string", example="Task saved successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     ),
     * )
     * @OA\Schema(
     *     schema="Task",
     *     type="object",
     *     description="Task Model",
     *     @OA\Property(property="name", type="string", example="Design new logo"),
     *     @OA\Property(property="description", type="string", example="Create a new logo for the website"),
     *     @OA\Property(property="assigned_to", type="integer", example="2"),
     *     @OA\Property(property="status", type="integer", example=1),
     *     @OA\Property(property="start", type="date", format="date", example="2024-06-01"),
     *     @OA\Property(property="end", type="date", format="date", example="2024-06-01"),
     *     @OA\Property(property="category_id", type="integer", example="1"),
     *     @OA\Property(property="last_updated_by", type="string", example="John Doe"),
     * )
     */
    public function store(CreateTaskAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $task = $this->taskRepository->create($input);

        return $this->sendResponse($task->toArray(), 'Task saved successfully');
    }

    /**
     * Display the specified Task.
     * GET|HEAD /tasks/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var Task $task */
        $task = $this->taskRepository->find($id);

        if (empty($task)) {
            return $this->sendError('Task not found');
        }

        return $this->sendResponse($task->toArray(), 'Task retrieved successfully');
    }

    /**
     * Update the specified Task in storage.
     * PUT/PATCH /tasks/{id}
     */
    public function update($id, UpdateTaskAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Task $task */
        $task = $this->taskRepository->find($id);

        if (empty($task)) {
            return $this->sendError('Task not found');
        }

        $task = $this->taskRepository->update($input, $id);

        return $this->sendResponse($task->toArray(), 'Task updated successfully');
    }

    /**
     * Remove the specified Task from storage.
     * DELETE /tasks/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Task $task */
        $task = $this->taskRepository->find($id);

        if (empty($task)) {
            return $this->sendError('Task not found');
        }

        $task->delete();

        return $this->sendSuccess('Task deleted successfully');
    }
}
