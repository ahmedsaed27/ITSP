<?php

namespace App\Http\Controllers\Api\V1\Jobs;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobRequest;
use App\Models\Jobs as JobsModel;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Response;

class Jobs extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = JobsModel::with('category', 'department')->latest()->paginate(10);

        return $this->success(
            status: 200,
            message: 'Jobs retrieved successfully.',
            data: $jobs
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobRequest $request)
    {
        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $image = $this->FileUploade($request->file('image'), 'jobs');
            }

            $job = JobsModel::create([
                'postion' => $request->postion,
                'discription' => $request->discription,
                'job_level' => $request->job_level,
                'job_type' => $request->job_type,
                'job_place' => $request->job_place,
                'range_salary' => $request->range_salary,
                'skills' => $request->skills,
                'requirments' => $request->requirments,
                'categories_id' => $request->categories_id,
                'departments_id' => $request->departments_id,
                'image' => $image,
            ]);

            DB::commit();

            return $this->success(
                status: 200,
                message: 'Job created successfully.',
                data: $job
            );
        } catch (Exception $e) {
            DB::rollBack();

            if (isset($image)) {
                $this->unlinkFile(fileSystem: 'jobs', files: [$image]);
            }

            return $this->error(
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
                message: $e->getMessage()
            );
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = JobsModel::with('category', 'department')->find($id);

        if(!$job){
            return $this->error(400, 'job not found');
        }

        return $this->success(status: 200, message: 'job retrieved successfully.' , data:$job);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        try{

            $job = JobsModel::find($id);

            if(!$job){
                return $this->error(400, 'job not found');
            }

            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $this->unlinkFile('jobs' , [$job->image]);
                $image = $this->FileUploade($request->file('image'), 'jobs');
            }

            $job->update([
                'postion' => $request->postion,
                'discription' => $request->discription,
                'job_level' => $request->job_level,
                'job_type' => $request->job_type,
                'job_place' => $request->job_place,
                'range_salary' => $request->range_salary,
                'skills' => $request->skills,
                'requirments' => $request->requirments,
                'categories_id' => $request->categories_id,
                'departments_id' => $request->departments_id,
                'image' => $image,
            ]);


            DB::commit();

            return $this->success(status: 200, message: 'Job updated successfully.', data:$job);
        }catch(Exception $e){
            DB::rollBack();
            $this->unlinkFile(fileSystem:'jobs' , files:[$job->image]);
            return $this->error(500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job = JobsModel::find($id);

        if(!$job){
            return $this->error(400, 'job not found');
        }

        $this->unlinkFile(fileSystem:'jobs' , files:[$job->image]);

        $job->delete();

        return $this->deleted(status: 200);
    }

    public function jobLevel(){
        $data = [
            0 => 'junior',
            1 => 'Senior',
            2 => 'Mid level'
        ];

        return $this->success(status: 200, message: 'Job Levels Retrived successfully.', data:$data);
    }

    public function jobType(){
        $data = [
            0 => 'Full Time',
            1 => 'Part Time',
        ];

        return $this->success(status: 200, message: 'Job Type Retrived successfully.', data:$data);
    }

    public function jobPlace(){
        $data = [
            0 => 'remotly',
            1 => 'on site',
            2 => 'Hybrid'
        ];

        return $this->success(status: 200, message: 'Job Place Retrived successfully.', data:$data);
    }
}
