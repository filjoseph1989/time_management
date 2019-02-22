<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return all projects owned or assigned to the
     * use
     *
     * @return Illuminate\Response
     */
    public function index()
    {
        return Project::with(['timers', 'team', 'user'])
            ->paginate(15);
    }

    /**
     * Create a project
     *
     * @param  Request $request
     * @return boolean|array
     */
    public function store(Request $request)
    {
        // Return validated data as an array
        $data = $request->validate([
            'name' => 'required|between:2,20'
        ]);

        $data['name'] = ucfirst($data['name']);
        $data['created_by'] = Auth::user()->id;

        // Store to db
        return Project::create($data);
    }

    /**
     * Destroy project
     *
     * @param  int $id
     * @return json
     */
    public function destroy($id)
    {
        $project = Project::find($id);

        if ($project->delete()) {
            return response()->json([
                'success' => 'true'
            ]);
        }
    }

    /**
     * Update project name
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|between:2,20'
        ]);

        return Project::whereId($id)->update([
            'name' => $request->input('name'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Project::find($id);
    }

    /**
     * Return result of the filtering
     *
     * @param  Request $request
     * @return Illuminate\Response
     */
    public function filter(Request $request)
    {
        $project = Project::with(['timers', 'team']);

        if (!is_null($request->input('status'))) {
            $project = $project->whereStatus($request->input('status'));
        }

        if (!is_null($request->input('team'))) {
            $id = $request->input('team');

            $project = $project->whereHas('team', function($query) use ($id) {
                $query->whereId($id);
            });
        }

        if (!is_null($request->input('keyword'))) {
            $key = $request->input('keyword');
            $project = $project->where('name', 'like', "%{$key}%");
        }

        return $project->paginate(15);

    }
}
