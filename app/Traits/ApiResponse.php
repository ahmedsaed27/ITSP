<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ApiResponse{

    public function success(int $status = 200 , string $message , array|object $data){
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ] , $status);
    }


    public function error(int $status = 400 , string $message){
        return response()->json([
            'status' => $status,
            'message' => $message,
        ] , $status);
    }


    public function deleted(int $status = 200){
        return response()->json([
            'status' => $status,
            'message' => 'Record deleted successfully.',
        ] , $status);
    }


    public function FileUploade($file , $fileSystem){

        $imagePath = Storage::disk($fileSystem)->put('/', $file);

        $imageName = basename($imagePath);

        return $imageName;
        // return $imagePath;
    }

    public function unlinkFile($fileSystem , array $files)
    {
        foreach ($files as $file) {

            $relativePath = parse_url($file, PHP_URL_PATH);

            // Use basename to get the filename
            $filename = basename($relativePath);

            // Check if the file exists and delete it
            if (Storage::disk($fileSystem)->exists($filename)) {
                Storage::disk($fileSystem)->delete($filename);
            }
        }

    }


}
