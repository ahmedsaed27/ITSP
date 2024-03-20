<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ApiResponse{

    public function success($status = 200 , $message , $data){
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ] , $status);
    }


    public function error($status = 400 , $message , $data){
        return response()->json([
            'status' => $status,
            'message' => $message,
            'error' => $data
        ] , $status);
    }


    public function FileUploade($file , $fileSystem){
       
        $imagePath = Storage::disk($fileSystem)->put('/', $file);
    
        return $imagePath;
    }

    public function unlinkFile($fileSystem , array $files)
    {
        foreach ($files as $file) {
            if (Storage::disk($fileSystem)->exists($file)) {
                Storage::disk($fileSystem)->delete($file);
            }
        }

    }


}
