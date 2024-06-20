<?php

namespace App\Http\Controllers\Api\V1\Applicant;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicantRequest;
use App\Models\Applicant as ModelsApplicant;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Applicant extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $applicant = ModelsApplicant::with('city', 'applies')->latest()->paginate(10);


        return $this->success(status: 200, message: 'Applicant retrieved successfully.', data: $applicant);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ApplicantRequest $request)
    {

        try {

            $image =  $this->FileUploade($request->file('images'), 'applicant');
            $cv =  $this->FileUploade($request->file('cv'), 'applicant');

            DB::beginTransaction();

            $applicant = ModelsApplicant::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'citys_id' => $request->citys_id,
                'area' => $request->area,
                'birthYear' => $request->birthYear,
                'gender' => $request->gender,
                'images' => $image,
                'cv' => $cv
            ]);

            DB::commit();

            return $this->success(status: 200, message: 'Applicant created successfully.', data: $applicant);
        } catch (Exception $e) {
            DB::rollBack();
            $this->unlinkFile(fileSystem: 'applicant', files: [$image, $cv]);
            return $this->error(status: 400, message: $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $applicant = ModelsApplicant::with('city', 'applies')->find($id);

        if(!$applicant){
            return $this->error(400, 'applicant not found');
        }

        return $this->success(status: 200, message: 'Applicant retrieved successfully.' , data:$applicant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ApplicantRequest $request, string $id)
    {

        try{

            $applicant = ModelsApplicant::find($id);

            if(!$applicant){
                return $this->error(400, 'applicant not found');
            }

            if($request->hasFile('images') && $request->hasFile('cv')){
                $this->unlinkFile(fileSystem:'applicant' , files:[$applicant->cv , $applicant->images]);
                $image =  $this->FileUploade($request->file('images'), 'applicant');
                $cv =  $this->FileUploade($request->file('cv'), 'applicant');
            }

            DB::beginTransaction();


            $applicant->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'phone' => $request->phone,
                'citys_id' => $request->citys_id,
                'area' => $request->area,
                'birthYear' => $request->birthYear,
                'gender' => $request->gender,
                'images' => $image,
                'cv' => $cv
            ]);

            DB::commit();

            return $this->success(status: 200, message: 'Applicant updated successfully.', data:$applicant);
        }catch(Exception $e){

            DB::rollBack();
            return $this->error(400, $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $applicant = ModelsApplicant::find($id);

        if(!$applicant){
            return $this->error(400, 'applicant not found');
        }

        $this->unlinkFile(fileSystem:'applicant' , files:[$applicant->cv , $applicant->images]);

        $applicant->delete();

        return $this->deleted(status: 200);

    }
}
