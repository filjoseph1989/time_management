<?php

namespace App\Http\Controllers;

use App\Models\Timer;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimerController extends Controller
{
    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        return Timer::mine()
            ->whereDate('started_at', '<', Carbon::today())
            ->limit(5)
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->started_at)->format('M d, Y');
            });
    }

    /**
     * return timer of today
     *
     * @return Illuminate\Response
     */
    public function getToday()
    {
        // get the previous hour in today
        // get the time interval of the current hour

        return Timer::mine()
            ->whereDate('started_at', Carbon::today())
            ->get();
    }

    public function store(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => 'required|between:3,100'
        ]);

        $timer = Project::mine()->findOrFail($id)
            ->timers()
            ->save(new Timer([
                'name'       => $data['name'],
                'user_id'    => Auth::user()->id,
                'started_at' => new Carbon,
            ]));

        return $timer->with('project')->find($timer->id);
    }

    public function running()
    {
        return Timer::with('project')
            ->mine()
            ->running()
            ->first() ?? [];
    }

    public function stopRunning()
    {
        if ($timer = Timer::mine()->running()->first()) {
            $timer->update(['stopped_at' => new Carbon]);
        }

        return $timer;
    }
}
