<?php

namespace App\Http\Controllers;

use App\Models\Report;
use DB;
use File;
use App\Models\Url;
use App\Models\Slider01;
use App\Models\Url_Path;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Slider01Controller extends Controller
{
    public function slider01s()
    {
        $slider01s = DB::table('slider01s')
            ->join('admins','slider01s.admin_id','=','admins.id')
            ->select('admins.id as admin_id','admins.name',  'admins.email',  'slider01s.*')
            ->get();
        return $slider01s->toArray();
    }

    public function latestLider01s() {
        $slider01 = Slider01::latest()->take(1)->get();
        return response()->json([
            'success' => true,
            'slider01' => $slider01,
        ]);
        return response($slider01, 200);
    }


    public function nSlider01s()
    {
        $slider01s = DB::table('slider01s')
            ->join('subadmins','slider01s.admin_id','=','subadmins.id')
            ->select('subadmins.id as subadmin_id','subadmins.name',  'subadmins.email',  'slider01s.*')
            ->latest()
            ->take(3)
            ->get();
        return $slider01s->toArray();
    }


    public function findSlider01($id)
    {
        try{
            $slider01 = DB::table('slider01s')
                ->join('admins','slider01s.admin_id','=','admins.id')
                ->select('admins.id as admin_id','admins.name',  'admins.email',  'slider01s.*')
                ->where(['slider01s.id' => $id])
                ->get();
            if (!$slider01) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(

                    $slider01[0]
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function imgName($path){
        if($path){
            $path = str_replace(' ', '-', $path);
            $name = explode('\\', $path);
            $length = count($name);
            return $name[$length-1];
        }
    }

    public function upload($photo, $path){
        if($photo){
            $name = rand().'_'.$this->imgName($path);
            \Image::make($photo)->save(public_path('images/slider01s/').$name);
            return $name;
        }else
        {
            null;
        }
    }

    public function saveSlider(Request $request){
        $slider01 = new Slider01;
        if($request->hasFile('image')){
            $doc = $request->file('image');
            $fileName = $request->file('image')->getClientOriginalName();
            $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
            $fileExtension = $request->file('image')->getClientOriginalExtension();
            $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
            $dest = public_path('/images/sliders/');
            $doc->move($dest, $input['fileName']);
            // dd($input['fileName']);
            $slider01->imgurl = Url::URL_SLIDERS.$input['fileName'];
            $slider01->admin_id = $request->admin_id;
            $slider01->super_id = $request->super_id;

            if($slider01->imgurl == null ){
                return response()->json([
                    'success' => false,
                    'message' => "L'Image n'a pas été soumis"
                ], 500);
            }else{
                try {
                    if ($slider01->save())
                        return response()->json([
                            'success' => true,
                            'message' => "L'Image  est soumis avec successe."
                        ], 201);
                    else
                        return response()->json([
                            'success' => false,
                            'message' => "L'Image  n'a pas été soumis"
                        ], 500);
                }catch (\Exception $e) {
                    return $e->getMessage();
                }
            }
        }
    }


    public function updateSlider01(Request $request) {

        try {
            $slider = Slider01::where('id', $request->id)->first();
            $slider01 = array();
            if($request->hasFile('image')){
                $doc = $request->file('image');
                $fileName = $request->file('image')->getClientOriginalName();
                $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
                $fileExtension = $request->file('image')->getClientOriginalExtension();
                $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
                $dest = public_path('/images/sliders/');
                $doc->move($dest, $input['fileName']);
                // dd($input['fileName']);
                $slider01['imgurl'] = Url::URL_SLIDERS.$input['fileName'];
                $slider02['super_id'] = $request->super_id;;
                $slider02['admin_id'] = $request->admin_id;
                $this->deleteImage($slider->imgurl);
                if ($slider01['imgurl'] == null){
                    $slider01['imgurl'] = $slider->imgurl;
                }
                $updated = DB::table('slider01s')->where('id', $request->id)->update($slider01);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "L'Enregistrement a ete mis a jour avec successe...!"
                    ], 200);
                }
            }

        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                // "message" => 'Votre Profile ne peut pas etre mis a jour...!'
                "message" => $e->getMessage()
            ], 404);
        }
    }



    public function dbImgName($path){
        if($path !== Url_Path::URL."sliders/"){
            $name = is_string($path) ? explode('/', $path) : $path;
            $length = is_array($name) ? count($name) : 0;
            if($length !== 0){
                return $name[$length-1];
            }
        }else{
            return $name = null;
        }
    }

    public function deleteImage($path)
    {
        $imageName = $this->dbImgName($path);
        $image_path = 'images/sliders/'.$imageName;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }
    }

    public function deleteSlider01($id) {
        try {
            $slider01 = Slider01::where('id', $id)->get();
            if($slider01) {
                Slider01::where('id', $id)->delete();
                $this->deleteImage($slider01[0]->imgurl);
                return response()->json([
                    "message" => "L'Article est supprimé définitivement"
                ], 202);
            } else {
                return response()->json([
                    "message" => 'Cet enregistrement n\'existe pas'
                    // "message" => $blog
                ], 404);
            }
        }catch (\Exception $e){
            return response()->json([
                "message" => "L'Article ne peux pas etre supprimer"
                // 'message' => $e->getMessage()
            ], 404);
        }
    }
}
