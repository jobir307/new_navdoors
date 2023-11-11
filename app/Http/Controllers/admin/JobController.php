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
        // eshik
        $door_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->door_job); $i++) {
            if (!empty($request->door_job[$i]) && !empty($request->door_salary[$i])) {
                $door_job[$i]['job'] = $request->door_job[$i];
                $door_job[$i]['salary'] = $request->door_salary[$i];
            }
        }

        // nalichnik
        $jamb_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->jamb_job); $i++) {
            if (!empty($request->jamb_job[$i]) && !empty($request->jamb_salary[$i])) {
                $jamb_job[$i]['job'] = $request->jamb_job[$i];
                $jamb_job[$i]['salary'] = $request->jamb_salary[$i];
            }
        }

        // nostandart nalichnik
        $nsjamb_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->nsjamb_job); $i++) {
            if (!empty($request->nsjamb_job[$i]) && !empty($request->nsjamb_salary[$i])) {
                $nsjamb_job[$i]['job'] = $request->nsjamb_job[$i];
                $nsjamb_job[$i]['salary'] = $request->nsjamb_salary[$i];
            }
        }

        // dobor
        $transom_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->transom_job); $i++) {
            if (!empty($request->transom_job[$i]) && !empty($request->transom_salary[$i])) {
                $transom_job[$i]['job'] = $request->transom_job[$i];
                $transom_job[$i]['salary'] = $request->transom_salary[$i];
            }
        }
        
        // korona 
        $crown_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->crown_job); $i++) {
            if (!empty($request->crown_job[$i]) && !empty($request->crown_salary[$i])) {
                $crown_job[$i]['job'] = $request->crown_job[$i];
                $crown_job[$i]['salary'] = $request->crown_salary[$i];
            }
        }

        // sapog
        $boot_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->boot_job); $i++) {
            if (!empty($request->boot_job[$i]) && !empty($request->boot_salary[$i])) {
                $boot_job[$i]['job'] = $request->boot_job[$i];
                $boot_job[$i]['salary'] = $request->boot_salary[$i];
            }
        }

        // kubik
        $cube_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->cube_job); $i++) {
            if (!empty($request->cube_job[$i]) && !empty($request->cube_salary[$i])) {
                $cube_job[$i]['job'] = $request->cube_job[$i];
                $cube_job[$i]['salary'] = $request->cube_salary[$i];
            }
        }
        
        $door_attributes = implode(",", $request->door_attributes);

        Job::create([
            'name'            => $request->name,
            'door_job'        => json_encode($door_job),
            'jamb_job'        => json_encode($jamb_job),
            'nsjamb_job'      => json_encode($nsjamb_job),
            'transom_job'     => json_encode($transom_job),
            'crown_job'       => json_encode($crown_job),
            'boot_job'        => json_encode($boot_job),
            'cube_job'        => json_encode($cube_job),
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
    public function edit($id)
    {
        $job = Job::find($id);
        $door_jobs = json_decode($job->door_job, true);
        $jamb_jobs = json_decode($job->jamb_job, true);
        $nsjamb_jobs = json_decode($job->nsjamb_job, true);
        $transom_jobs = json_decode($job->transom_job, true);
        $crown_jobs = json_decode($job->crown_job, true);
        $boot_jobs = json_decode($job->boot_job, true);
        $cube_jobs = json_decode($job->cube_job, true);

        $door_attributes = explode(",", $job->door_attributes);
        $all_door_attributes = DB::select('SELECT id, name, en_name FROM door_attributes ORDER BY name');
        $result = array_column($all_door_attributes, 'en_name', 'name');
        $diff_array = array_diff($result, $door_attributes);
        $in_array = [];
        foreach($door_attributes as $key => $value) {
            foreach($all_door_attributes as $k => $v){
                if ($v->en_name == $value) {
                    $in_array[$key]['id'] = $v->id;
                    $in_array[$key]['name'] = $v->name;
                    $in_array[$key]['en_name'] = $v->en_name;
                }
            }
        }
        
        $data = array(
            'job' => $job,
            'in_array' => $in_array,
            'diff_array' => $diff_array,
            'door_jobs' => $door_jobs, 
            'jamb_jobs' => $jamb_jobs, 
            'nsjamb_jobs' => $nsjamb_jobs, 
            'transom_jobs' => $transom_jobs,
            'crown_jobs' => $crown_jobs,
            'boot_jobs' => $boot_jobs,
            'cube_jobs' => $cube_jobs
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
        // eshik
        $door_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->door_job); $i++) {
            if (!empty($request->door_job[$i]) && !empty($request->door_salary[$i])) {
                $door_job[$i]['job'] = $request->door_job[$i];
                $door_job[$i]['salary'] = $request->door_salary[$i];
            }
        }

        // nalichnik
        $jamb_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->jamb_job); $i++) {
            if (!empty($request->jamb_job[$i]) && !empty($request->jamb_salary[$i])) {
                $jamb_job[$i]['job'] = $request->jamb_job[$i];
                $jamb_job[$i]['salary'] = $request->jamb_salary[$i];
            }
        }   

        // nostandart nalichnik
        $nsjamb_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->nsjamb_job); $i++) {
            if (!empty($request->nsjamb_job[$i]) && !empty($request->nsjamb_salary[$i])) {
                $nsjamb_job[$i]['job'] = $request->nsjamb_job[$i];
                $nsjamb_job[$i]['salary'] = $request->nsjamb_salary[$i];
            }
        }

        // dobor
        $transom_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->transom_job); $i++) {
            if (!empty($request->transom_job[$i]) && !empty($request->transom_salary[$i])) {
                $transom_job[$i]['job'] = $request->transom_job[$i];
                $transom_job[$i]['salary'] = $request->transom_salary[$i];
            }
        }
        
        // korona 
        $crown_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->crown_job); $i++) {
            if (!empty($request->crown_job[$i]) && !empty($request->crown_salary[$i])) {
                $crown_job[$i]['job'] = $request->crown_job[$i];
                $crown_job[$i]['salary'] = $request->crown_salary[$i];
            }
        }

        // sapog
        $boot_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->boot_job); $i++) {
            if (!empty($request->boot_job[$i]) && !empty($request->boot_salary[$i])) {
                $boot_job[$i]['job'] = $request->boot_job[$i];
                $boot_job[$i]['salary'] = $request->boot_salary[$i];
            }
        }

        // kubik
        $cube_job = array(array('job' => '', 'salary' => 0));
        for ($i = 0; $i < count($request->cube_job); $i++) {
            if (!empty($request->cube_job[$i]) && !empty($request->cube_salary[$i])) {
                $cube_job[$i]['job'] = $request->cube_job[$i];
                $cube_job[$i]['salary'] = $request->cube_salary[$i];
            }
        }

        
        $door_attributes = implode(",", $request->door_attributes);

        Job::where('id', $id)->update([
            'name'            => $request->name,
            'door_job'        => json_encode($door_job),
            'jamb_job'        => json_encode($jamb_job),
            'nsjamb_job'      => json_encode($nsjamb_job),
            'transom_job'     => json_encode($transom_job),
            'crown_job'       => json_encode($crown_job),
            'boot_job'        => json_encode($boot_job),
            'cube_job'        => json_encode($cube_job),
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
    public function destroy($id)
    {
        $job = Job::find($id);
        $job->delete();
        
        return redirect()->route('jobs.index');
    }
}
