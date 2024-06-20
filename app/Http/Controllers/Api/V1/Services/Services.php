<?php

namespace App\Http\Controllers\Api\V1\Services;

use App\Http\Controllers\Controller;
use App\Models\Services as ModelsServices;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class Services extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ModelsServices::with('category')->paginate(10);

        return $this->success(
            status: 200,
            message: 'Services retrieved successfully.',
            data: $data
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
