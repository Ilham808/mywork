<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function search(Request $request): ProjectCollection
    {
        $page = $request->query('page', 1);
        $size = $request->query('size', 10);

        $userId = Auth::user()->id;
        $projectsQuery = Project::where('user_id', $userId);

        $projectsQuery = $projectsQuery->where(function ($query) use ($request) {
            $name = $request->query('name');
            if ($name) {
                $query->where('name', 'like', '%' . $request->query('name') . '%');
            }
        });


        $project = $projectsQuery->paginate($size, ['*'], 'page', $page);

        return new ProjectCollection($project);
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $data = $request->validated();
        $userId = Auth::user()->id;

        $project = new Project($data);
        $project->user_id = $userId;
        $project->save();

        return response()->json([
            'success' => true,
            'message' => 'Project berhasil ditambahkan',
            'data' => new ProjectResource($project)
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $userId = Auth::user()->id;

        $project = Project::where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$project) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'errors' => [
                    'message' => 'Project tidak ditemukan'
                ]
            ], 404));
        }

        return response()->json([
            'success' => true,
            'data' => new ProjectResource($project)
        ]);
    }

    public function update(ProjectRequest $request, int $id): JsonResponse
    {   
        $userId = Auth::user()->id;

        $project = Project::where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$project) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'errors' => [
                    'message' => 'Project tidak ditemukan'
                ]
            ], 404));
        }

        $data = $request->validated();
        $project->update([
            'name' => $data['name'],
            'description' => isset($data['description']) ? $data['description'] : $project->description
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project berhasil diperbarui',
            'data' => new ProjectResource($project)
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $userId = Auth::user()->id;

        $project = Project::where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$project) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'errors' => [
                    'message' => 'Project tidak ditemukan'
                ]
            ], 404));
        }

        $project->delete();
        return response()->json([
            'success' => true,
            'message' => 'Project berhasil dihapus'
        ]);
    }
}
