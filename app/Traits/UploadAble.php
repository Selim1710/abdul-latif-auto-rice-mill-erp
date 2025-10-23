<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


trait UploadAble
{
    public function upload_file(UploadedFile $file, $folder = null,  $file_name = null, $disk = 'public'){
        if (!Storage::directories($disk.'/'.$folder)) {
			Storage::makeDirectory($disk.'/'.$folder,0777, true); //if directory not exist then make the directory
        }
        $filenameWithExt = $file->getClientOriginalName(); // Get filename with extension like index.jpg
        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME); // Get just filename  like index
        $extension       = $file->getClientOriginalExtension(); // Get just extension like .jpg
        $fileNameToStore = !is_null($file_name) ? str_replace(' ', '-', $file_name).'.'.$extension : str_replace(' ', '-', $filename).'-'.rand(111111,999999).'.'.$extension; //Filename to store  like index1545gfh5465.jpg
        $file->storeAs($folder,$fileNameToStore,$disk); //store file in targetted folder
        return $fileNameToStore;
    }
    public function delete_file($filename,$folder,$disk = 'public'){
        if(Storage::exists($disk.'/'.$folder.$filename)) {
            Storage::disk($disk)->delete($folder.$filename);
            return TRUE;
        }
        return false;
    }
}
