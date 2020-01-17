<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

trait FileUploadTrait
{
    /**
     * File upload trait used in controllers to upload files
     */
    public function saveFiles($imageFile, $fileDirectory = '', $clientId = '')
    {
        try {
            $data = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = strtolower(str_replace(' ', '', $data)).'_'.time().'_'.$clientId.'.'.$imageFile->getClientOriginalExtension();

            /* $resizeImageFile = Image::make($imageFile)->resize(120, 120);
            $resizeImageFile->save($uploadImagePath.'/'.$filename);
            Storage::putFileAs($fileDirectory, new File($uploadImagePath.'/'.$filename), $filename); */

            Storage::putFileAs('/'.$fileDirectory, new File($imageFile), $filename);

            return $filename;
        } catch(\Exception $e){
            \Log::info('Exception msg: '.$e->getMessage().' Line no. :'.$e->getLine().'File upload traits');
        }
    }

    /**
     * File upload trait used in controllers to upload files
     */
    public function removeOldVideoFileFromStorage($imageFile, $fileDirectory){
        try{
            if($imageFile != 'no_image.png'){
                $mainExist = Storage::exists($fileDirectory.'/'.$imageFile);
                if($mainExist)
                    Storage::delete($fileDirectory.'/'.$imageFile);
                return true;                
            }else{
                return true;
            }
        }catch(\Exception $e){
             \Log::info('Exception msg: '.$e->getMessage().' Line no. :'.$e->getLine().'File upload traits');
        }
    }
}
