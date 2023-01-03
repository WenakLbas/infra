<?php

namespace App\Http\Controllers;

use DB;
use File;
use App\Models\Url;
use App\Models\Url_Path;
use App\Models\Galleries;
use Illuminate\Http\Request;

class GalleriesController extends Controller
{
    public function galleries()
    {
        $part = DB::table('galleries')
            ->get();
        return response()->json($part);
    }

    public function oneGallerie()
    {
        $part = DB::table('galleries')
            ->get();
        // return $part[0];
        return response()->json(
            $part[0]
        );
    }

    public function findGallerie($id)
    {
        try{
            $part = DB::table('galleries')
                ->join('admins','galleries.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'galleries.*')
                ->where(['galleries.id' => $id])
                ->get();
            if (!$part) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $part[0]
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeGallerie(Request $request)
    {
        $gallerie = new Galleries();

        if($request->hasFile('img')){
            $doc = $request->file('img');
            $fileName = $request->file('img')->getClientOriginalName();
            $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
            $fileExtension = $request->file('img')->getClientOriginalExtension();
            $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
            $dest = public_path('/images/galleries/');
            $doc->move($dest, $input['fileName']);

            $gallerie->imgurl = Url::URL_GALLERIES.$input['fileName'];

            $gallerie->admin_id = $request->admin_id;
            $gallerie->super_id = $request->super_id;
            $gallerie->imgalt = $request->imgalt;

            if($gallerie->imgurl == null ){
                return response()->json([
                    'success' => false,
                    'message' => "L'enregistrement n'a pas été soumis"
                ], 500);
            }else{
                try {
                    if ($gallerie->save())
                        return response()->json([
                            'success' => true,
                            'message' => "L'enregistrement est soumis avec successe."
                        ], 201);

                    else
                        return response()->json([
                            'success' => false,
                            'message' => "L'enregistrement n'a pas été soumis"
                        ], 500);

                }catch (\Exception $e) {
                    return $e->getMessage();
                }
            }
        }

    }

    public function updateGallerie(Request $request, $id) {
        try {
            $galleriedb = Galleries::where('id', $id)->first();
            $gallerie = array();
            if($request->hasFile('img')){
                $doc = $request->file('img');
                $fileName = $request->file('img')->getClientOriginalName();
                $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
                $fileExtension = $request->file('img')->getClientOriginalExtension();
                $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
                $dest = public_path('/images/galleries/');
                $doc->move($dest, $input['fileName']);
                // dd($input['fileName']);
                $gallerie['imgurl'] = Url::URL_GALLERIES.$input['fileName'];
                $gallerie['super_id'] = $request->super_id;
                $gallerie['admin_id'] = $request->admin_id;

                $gallerie['imgalt'] = is_null($request->imgalt) ? $galleriedb->imgalt : $request->imgalt;

                if ($gallerie['imgurl'] == null){
                    $gallerie['imgurl'] = $galleriedb->imgurl;
                }else{
                    $this->deleteImage($galleriedb->imgurl);
                }

                $updated = DB::table('galleries')->where('id', $request->id)->update($gallerie);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "L'enregistrement a ete mis a jour avec successe...!"
                    ], 200);
                }
            }else{
                $gallerie['super_id'] = $request->super_id;
                $gallerie['admin_id'] = $request->admin_id;

                $gallerie['imgalt'] = is_null($request->imgalt) ? $galleriedb->imgalt : $request->imgalt;

                $updated = DB::table('galleries')->where('id', $request->id)->update($gallerie);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "L'enregistrement a ete modifier avec successe...!"
                    ], 200);
                }
            }
        }catch (\Exception $e){
            return response()->json([
                "message" => $e->getMessage()
                //"message" => 'Cet enregistrement n\'existe pas'
            ], 404);
        }
    }


    public function dbImgName($path){
        if($path !== Url_Path::URL.'galleries/'){
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
        $image_path = 'images/galleries/'.$imageName;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }
    }

    public function deleteGallerie($id) {
        try {
            $part = Galleries::where('id', $id)->get();
            if($part) {
                Galleries::where('id', $id)->delete();
                $this->deleteImage($part[0]->imgurl);
                return response()->json([
                    "message" => "L'enregistrement est supprimé définitivement"
                ], 202);
            } else {
                return response()->json([
                    "message" => 'Cet enregistrement n\'existe pas'
                    // "message" => $blog
                ], 404);
            }
        }catch (\Exception $e){
            return response()->json([
                "message" => "L'enregistrement ne peux pas etre supprimer"
                // 'message' => $e->getMessage()
            ], 404);
        }
    }
}
