<?php
namespace App\Models\Traits;

use App\Models\Document;

trait DocumentUploadTrait {

    public function saveDocument($file, $urlprefix, $data=[]){
        $name = $file->getClientOriginalName();
        $contents = file_get_contents($file);
        $path = $urlprefix.'/' . $this->id . '/' . rand(111, 999) . '_' . str_replace(' ','_', $name);
        \Storage::put($path, $contents, 'public');
        $document=new Document(['file_path'=>$path]);
        $this->gallery()->save($document);
    }

    public function gallery(){
        return $this->morphMany('App\Models\Document', 'entity');
    }

    public function saveImage($file, $urlprefix){
        $name = $file->getClientOriginalName();
        $contents = file_get_contents($file);
        $path = $urlprefix.'/' . $this->id . '/' . rand(111, 999) . '_' . str_replace(' ','_', $name);
        \Storage::put($path, $contents, 'public');
        $this->image=$path;
        $this->save();
    }

    public function saveFile($file, $urlprefix){
        $name = $file->getClientOriginalName();
        $contents = file_get_contents($file);
        $path = $urlprefix.'/' . $this->id . '/' . rand(111, 999) . '_' . str_replace(' ','_', $name);
        \Storage::put($path, $contents, 'public');
        $this->file_path=$path;
        $this->save();
    }

    public function savePanCard($file, $urlprefix){
        $name = $file->getClientOriginalName();
        $contents = file_get_contents($file);
        $path = $urlprefix.'/' . $this->id . '/' . rand(111, 999) . '_' . str_replace(' ','_', $name);
        \Storage::put($path, $contents, 'public');
        $this->pan_card=$path;
        $this->save();
    }
    public function saveFrontAadhaarCard($file, $urlprefix){
        $name = $file->getClientOriginalName();
        $contents = file_get_contents($file);
        $path = $urlprefix.'/' . $this->id . '/' . rand(111, 999) . '_' . str_replace(' ','_', $name);
        \Storage::put($path, $contents, 'public');
        $this->front_aadhaar_card=$path;
        $this->save();
    }
    public function saveBackAadhaarCard($file, $urlprefix){
        $name = $file->getClientOriginalName();
        $contents = file_get_contents($file);
        $path = $urlprefix.'/' . $this->id . '/' . rand(111, 999) . '_' . str_replace(' ','_', $name);
        \Storage::put($path, $contents, 'public');
        $this->back_aadhaar_card=$path;
        $this->save();
    }
    public function saveFrontDlNo($file, $urlprefix){
        $name = $file->getClientOriginalName();
        $contents = file_get_contents($file);
        $path = $urlprefix.'/' . $this->id . '/' . rand(111, 999) . '_' . str_replace(' ','_', $name);
        \Storage::put($path, $contents, 'public');
        $this->front_dl_no=$path;
        $this->save();
    }
    public function saveBackDlNo($file, $urlprefix){
        $name = $file->getClientOriginalName();
        $contents = file_get_contents($file);
        $path = $urlprefix.'/' . $this->id . '/' . rand(111, 999) . '_' . str_replace(' ','_', $name);
        \Storage::put($path, $contents, 'public');
        $this->back_dl_no=$path;
        $this->save();
    }

    public function saveBikeFront($file, $urlprefix){
        $name = $file->getClientOriginalName();
        $contents = file_get_contents($file);
        $path = $urlprefix.'/' . $this->id . '/' . rand(111, 999) . '_' . str_replace(' ','_', $name);
        \Storage::put($path, $contents, 'public');
        $this->bike_front=$path;
        $this->save();
    }

    public function saveBikeBack($file, $urlprefix){
        $name = $file->getClientOriginalName();
        $contents = file_get_contents($file);
        $path = $urlprefix.'/' . $this->id . '/' . rand(111, 999) . '_' . str_replace(' ','_', $name);
        \Storage::put($path, $contents, 'public');
        $this->bike_back=$path;
        $this->save();
    }

}
