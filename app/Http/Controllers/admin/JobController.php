<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobs = Job::all();

        return view('admin.job.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $door_attributes = DB::select('SELECT id, name, en_name FROM door_attributes ORDER BY name');
        
        return view('admin.job.create', compact('door_attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $door_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->door_job); $i++) {
            if (!empty($request->door_job[$i]) && !empty($request->door_salary[$i])) {
                $door_job[$i]['job'] = $request->door_job[$i];
                $door_job[$i]['salary'] = $request->door_salary[$i];
            }
        }

        $jamb_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->jamb_job); $i++) {
            if (!empty($request->jamb_job[$i]) && !empty($request->jamb_salary[$i])) {
                $jamb_job[$i]['job'] = $request->jamb_job[$i];
                $jamb_job[$i]['salary'] = $request->jamb_salary[$i];
            }
        }

        $transom_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->transom_job); $i++) {
            if (!empty($request->transom_job[$i]) && !empty($request->transom_salary[$i])) {
                $transom_job[$i]['job'] = $request->transom_job[$i];
                $transom_job[$i]['salary'] = $request->transom_salary[$i];
            }
        }
        
        $door_attributes = implode(",", $request->door_attributes);

        Job::create([
            'name'            => $request->name,
            'door_job'        => json_encode($door_job),
            'jamb_job'        => json_encode($jamb_job),
            'transom_job'     => json_encode($transom_job),
            'door_attributes' => $door_attributes
        ]);
        return redirect()->route('jobs.index');
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
    public function edit(Job $job)
    {
        $door_jobs = json_decode($job->door_job, true);
        $jamb_jobs = json_decode($job->jamb_job, true);
        $transom_jobs = json_decode($job->transom_job, true);
        $door_attributes = explode(",", $job->door_attributes);
        $all_door_attributes = DB::select('SELECT id, name, en_name FROM door_attributes');
        
        $in_array = [];
        $not_in_array = [];
        foreach($all_door_attributes as $key => $value) {
            if (in_array($value->en_name, $door_attributes)) {
                $in_array[$key]['id'] = $value->id;
                $in_array[$key]['name'] = $value->name;
                $in_array[$key]['en_name'] = $value->en_name;
            } else {
                $not_in_array[$key]['id'] = $value->id;
                $not_in_array[$key]['name'] = $value->name;
                $not_in_array[$key]['en_name'] = $value->en_name;
            }
        }
        
        $data = array(
            'job' => $job,
            'in_array' => $in_array,
            'not_in_array' => $not_in_array,
            'door_jobs' => $door_jobs, 
            'jamb_jobs' => $jamb_jobs, 
            'transom_jobs' => $transom_jobs
        );

        return view('admin.job.update', $data);
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
        $door_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->door_job); $i++) {
            if (!empty($request->door_job[$i]) && !empty($request->door_salary[$i])) {
                $door_job[$i]['job'] = $request->door_job[$i];
                $door_job[$i]['salary'] = $request->door_salary[$i];
            }
        }

        $jamb_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->jamb_job); $i++) {
            if (!empty($request->jamb_job[$i]) && !empty($request->jamb_salary[$i])) {
                $jamb_job[$i]['job'] = $request->jamb_job[$i];
                $jamb_job[$i]['salary'] = $request->jamb_salary[$i];
            }
        }

        $transom_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->transom_job); $i++) {
            if (!empty($request->transom_job[$i]) && !empty($request->transom_salary[$i])) {
                $transom_job[$i]['job'] = $request->transom_job[$i];
                $transom_job[$i]['salary'] = $request->transom_salary[$i];
            }
        }

        
        $door_attributes = implode(",", $request->door_attributes);

        Job::where('id', $id)->update([
            'name' => $request->name,
            'door_job' => json_encode($door_job),
            'jamb_job'        => json_encode($jamb_job),
            'transom_job'     => json_encode($transom_job),
            'door_attributes' => $door_attributes
        ]);

        return redirect()->route('jobs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        $job->delete();
        
        return redirect()->route('jobs.index');
    }
}
