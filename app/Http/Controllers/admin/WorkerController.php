<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Worker;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workers = DB::select('SELECT a.*, group_concat(c.name) AS jobs 
                               FROM (workers a, jobs c )
                               INNER JOIN worker_jobs b ON a.id=b.worker_id AND b.job_id=c.id
                               WHERE a.active=1
                               GROUP BY b.worker_id');
        $jobs = DB::select('SELECT id, name FROM jobs');

        return view('admin.worker.index', compact('workers', 'jobs'));
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
        $request->validate([
            'fullname'     => 'required|min:3|max:100',
            'address'      => 'required|max:300',
            'phone_number' => 'required'
        ]);

        $worker = Worker::create([
            'fullname'     => $request->fullname,
            'address'      => $request->address,
            'phone_number' => $request->phone_number
        ]);
        
        foreach($request->job_id as $key => $value) {
            DB::insert('INSERT INTO worker_jobs(worker_id, job_id) VALUES (?, ?)', [$worker->id, $value]);
        }
        return redirect()->route('workers.index');
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
    public function edit(Worker $worker)
    {
        $data = DB::select('SELECT job_id FROM worker_jobs WHERE worker_id=?', [$worker->id]);
        $worker_jobs = [];
        foreach ($data as $key => $value) {
            array_push($worker_jobs, $value->job_id);
        }
        $jobs = DB::select('SELECT id, name FROM jobs');

        return view('admin.worker.index', compact('worker_jobs', 'jobs', 'worker'));
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
        $request->validate([
            'fullname'     => 'required|min:3|max:100',
            'address'      => 'required|max:300',
            'phone_number' => 'required'
        ]);

        $worker = Worker::where('id', $id)->update([
            'fullname'     => $request->fullname,
            'address'      => $request->address,
            'phone_number' => $request->phone_number
        ]);
        DB::delete('DELETE FROM worker_jobs WHERE worker_id = ?', [$id]);
        foreach($request->job_id as $key => $value) {
            DB::insert('INSERT INTO worker_jobs(worker_id, job_id) VALUES (?, ?)', [$id, $value]);
        }

        return redirect()->route('workers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Worker::where('id', $id)->update([
            'active' => 0
        ]);

        return redirect()->route('workers.index');
    }
}
