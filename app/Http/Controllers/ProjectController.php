<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $this->authorize('viewAny', Project::class);
        return Inertia::render('Project/Project');
    }

    /**
     * @param Project $project
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function fetch(Project $project): JsonResponse
    {
        $this->authorize('view', $project);
        $where[] = ['user_id', auth()->id()];
        $projects = $project::where($where)->orderBy('id', 'desc')->get();
        if($projects){
            return response()->json(compact('projects'), 200);
        } else {
            return response()->json(['msg' => '404'], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProjectRequest $request
     * @param Project $project
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreProjectRequest $request, Project $project): JsonResponse
    {
        $this->authorize('create', $project);
        $fields = $request->validated() + ['user_id' => auth()->id()];
        $project::create($fields);
        return response()->json(['msg' => 'Success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Project $project
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Project $project):JsonResponse
    {
        $this->authorize('view', $project);
        return response()->json(compact('project'), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProjectRequest $request
     * @param \App\Models\Project $project
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateProjectRequest $request, Project $project):JsonResponse
    {
        $this->authorize('update', $project);
        $fields = $request->validated();
        $project->update($fields);
        return response()->json(['msg' => 'Success'], 200);
    }

    /**
     * @param Project $project
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function setProjectStatus(Project $project):JsonResponse
    {
        $this->authorize('update', $project);
        $project->update([
            'active' => $project->active ? false : true
        ]);
        return response()->json(['msg' => 'Success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Project $project
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Project $project):JsonResponse
    {
        $this->authorize('delete', $project);
        $project->delete();
        return response()->json(['msg' => 'Success'], 200);
    }
}
