<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Requests\TaskStatusRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TaskController extends Controller
{
    public function create(TaskRequest $request, int $idProject): JsonResponse
    {
        $user = Auth::user();
        $project = $this->getProject($user, $idProject);

        $data = $request->validated();

        $task = new Task($data);
        $task->project_id = $project->id;
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil ditambahkan',
            'data' => new TaskResource($task)
        ], 201);
    }

    public function search(Request $request, int $idProject) : TaskCollection 
    {
        $user = Auth::user();
        $project = $this->getProject($user, $idProject);

        $page = $request->query('page', 1);
        $size = $request->query('size', 10);

        $task = Task::where('project_id', $project->id);

        $task = $task->where(function ($query) use ($request) {
            $title = $request->query('title');
            if ($title) {
                $query->where('title', 'like', '%' . $title . '%');
            }

            $status = $request->query('status');
            if ($status) {
                $query->where('status', $status);
            }
        });

        $task = $task->paginate($size, ['*'], 'page', $page);

        return new TaskCollection($task);
    }

    public function update(TaskRequest $request, int $idProject, int $idTask): JsonResponse
    {
        $user = Auth::user();
        $project = $this->getProject($user, $idProject);
        $task = $this->getTask($idProject, $idTask);

        $data = $request->validated();
        $task->update([
            'title' => $data['title'],
            'description' => isset($data['description']) ? $data['description'] : $task->description,
            'status' => $data['status']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task berhasil diperbarui',
            'data' => new TaskResource($task)
        ], 200);
    }

    public function show(int $idProject, int $idTask): JsonResponse 
    {
        $user = Auth::user();
        $project = $this->getProject($user, $idProject);
        $task = $this->getTask($idProject, $idTask);
        return response()->json([
            'success' => true,
            'data' => new TaskResource($task)
        ], 200);
    }

    public function updateStatus(TaskStatusRequest $request, int $idProject, int $idTask): JsonResponse
    {
        $user = Auth::user();
        $project = $this->getProject($user, $idProject);
        $task = $this->getTask($idProject, $idTask);

        $data = $request->validated();
        $task->update([
            'status' => $data['status']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status task berhasil diperbarui',
            'data' => new TaskResource($task)
        ], 200);
    }

    public function destroy(int $idProject, int $idTask): JsonResponse
    {
        $user = Auth::user();
        $project = $this->getProject($user, $idProject);
        $task = $this->getTask($idProject, $idTask);

        $task->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Task berhasil dihapus'
        ], 200);
    }
    public function getProject(User $user, int $idProject): Project 
    {
        $project = Project::where('user_id', $user->id)->where('id', $idProject)->first();
        if(!$project) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'errors' => [
                    'message' => 'Project tidak ditemukan'
                ]
            ], 404));
        }
        return $project;
    }

    public function getTask(int $idProject, int $idTask): Task
    {
        $task = Task::where('project_id', $idProject)->where('id', $idTask)->first();
        if(!$task) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'errors' => [
                    'message' => 'Task tidak ditemukan'
                ]
            ], 404));
        }
        return $task;
    }
}
