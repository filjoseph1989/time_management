<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Project;
use App\Models\Timer;
use Illuminate\Http\Request;

class ProjectsTimerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Project::mine()->get()->toArray();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'name',
            'hourStopped',
            'minuteStopped',
            'secondStopped',
            'hourStarted',
            'minuteStarted',
            'secondStarted',
            'description',
            'newTimerTag',
        ]);

        $start = date('Y-m-d') . " {$data['hourStarted']}:{$data['minuteStarted']}:{$data['secondStarted']}";
        $stop  = date('Y-m-d') . " {$data['hourStopped']}:{$data['minuteStopped']}:{$data['secondStopped']}";

        $firstTime = new \DateTime($start);
        $lastTime  = new \DateTime($stop);

        $diff = $lastTime->diff($firstTime, true);
        
        $hr = $diff->h < 10 ? "0{$diff->h}" : $diff->h;
        $mn = $diff->i < 10 ? "0{$diff->i}" : $diff->i;
        $sc = $diff->s < 10 ? "0{$diff->s}" : $diff->s;

        $interval = "$hr:$mn:$sc";

        return Timer::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? $data['name'],
            'user_id'     => Auth::user()->id,
            'started_at'  => $start,
            'stopped_at'  => $stop,
            'interval'    => date('Y-m-d') . " $interval",
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $timer = Timer::find($id);

        if ($timer->delete()) {
            return response()->json([
                'success' => 'true'
            ]);
        }
    }
}
