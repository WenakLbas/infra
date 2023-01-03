<?php

namespace App\Http\Controllers;

use DB;
use File;
use App\Models\Url;
use App\Models\Url_Path;
use App\Models\Partner02;
use Illuminate\Http\Request;

class Partner02Controller extends Controller
{
    public function partner02s()
    {
        $part = DB::table('partener02s')
            ->get();
        return response()->json( $part);
    }

    public function findPartner02($id)
    {
        try{
            $part = DB::table('partener02s')
                ->join('admins','partener02s.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'partener02s.*')
                ->where(['partener02s.id' => $id])
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
            return response()->json($exception, 400);
        }
    }

    public function storePartner02(Request $request)
    {
        $partenaire = new Partner02();

        if($request->hasFile('img')){
            $doc = $request->file('img');
            $fileName = $request->file('img')->getClientOriginalName();
            $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
            $fileExtension = $request->file('img')->getClientOriginalExtension();
            $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
            $dest = public_path('/images/partenaires/');
            $doc->move($dest, $input['fileName']);

            $partenaire->imgurl = Url::URL_PARTENAIRES.$input['fileName'];

            $partenaire->admin_id = $request->admin_id;
            $partenaire->super_id = $request->super_id;
            $partenaire->name = $request->name;
            $partenaire->description = $request->description;
            $partenaire->link = $request->link;

            if($partenaire->link == null ){
                return response()->json([
                    'success' => false,
                    'message' => "L'Enregistrement n'a pas été soumis"
                ], 500);
            }else{
                try {
                    if ($partenaire->save())
                        return response()->json([
                            'success' => true,
                            'message' => "L'Enregistrement est soumis avec successe."
                        ], 201);
                    else
                        return response()->json([
                            'success' => false,
                            'message' => "L'Enregistrement n'a pas été soumis"
                        ], 500);
                }catch (\Exception $e) {
                    return $e->getMessage();
                }
            }
        }

    }

    public function updatePartner02(Request $request, $id) {
        try {
            $partdb = Partner02::where('id', $id)->first();
            $partenaire = array();
            if($request->hasFile('img')){
                $doc = $request->file('img');
                $fileName = $request->file('img')->getClientOriginalName();
                $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
                $fileExtension = $request->file('img')->getClientOriginalExtension();
                $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
                $dest = public_path('/images/partenaires/');
                $doc->move($dest, $input['fileName']);
                // dd($input['fileName']);
                $partenaire['imgurl'] = Url::URL_PARTENAIRES.$input['fileName'];
                $partenaire['super_id'] = $request->super_id;
                $partenaire['admin_id'] = $request->admin_id;

                $partenaire['name'] = is_null($request->name) ? $partdb->name : $request->name;
                $partenaire['description'] = is_null($request->description) ? $partdb->description : $request->description;
                $partenaire['link'] = is_null($request->link) ? $partdb->link : $request->link;

                if ($partenaire['imgurl'] == null){
                    $partenaire['imgurl'] = $partdb->imgurl;
                }else{
                    $this->deleteImage($partdb->imgurl);
                }

                $updated = DB::table('partener02s')->where('id', $request->id)->update($partenaire);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "L'Enregistrement a ete mis a jour avec successe...!"
                    ], 200);
                }
            }else{
                $partenaire['super_id'] = $request->super_id;
                $partenaire['admin_id'] = $request->admin_id;

                $partenaire['name'] = is_null($request->name) ? $partdb->name : $request->name;
                $partenaire['description'] = is_null($request->description) ? $partdb->description : $request->description;
                $partenaire['link'] = is_null($request->link) ? $partdb->link : $request->link;


                $updated = DB::table('partener02s')->where('id', $request->id)->update($partenaire);
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
        if($path !== Url_Path::URL.'partenaires/'){
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
        $image_path = 'images/partenaires/'.$imageName;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }
    }

    public function deletePartner02($id) {
        try {
            $part = Partner02::where('id', $id)->get();
            if($part) {
                Partner02::where('id', $id)->delete();
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
