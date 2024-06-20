<?php

namespace App\Http\Controllers\Api\V1\ContactUs;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactUsRequest;
use App\Models\ContactUs as ModelsContactUs;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactUs extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ModelsContactUs::latest()->paginate(10);

        return $this->success(
            status: 200,
            message: 'Data retrieved successfully.',
            data: $data
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactUsRequest $request)
    {
        try{
            DB::beginTransaction();
            $data = ModelsContactUs::create($request->validated());

            DB::commit();

            return $this->success(
                status: 200,
                message: 'Data Created successfully.',
                data: $data
            );

        }catch(Exception $e){
            DB::rollBack();
            return $this->error(status: 500, message: $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = ModelsContactUs::findOr($id, function () {
            return $this->error(500, 'Data not found');
        });

        return $this->success(status: 200, message: 'Data retrieved successfully.' , data:$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactUsRequest $request, string $id)
    {
        try{

            $data = ModelsContactUs::findOr($id, function () {
                return $this->error(500, 'Data not found');
            });

            DB::beginTransaction();
            $data->update($request->validated());

            DB::commit();

            return $this->success(
                status: 200,
                message: 'Data Updated successfully.',
                data: $data
            );

        }catch(Exception $e){
            DB::rollBack();
            return $this->error(status: 500, message: $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = ModelsContactUs::findOr($id, function () {
            return $this->error(500, 'Data not found');
        });

        $data->delete();

        return $this->deleted(status: 200);
    }
}
