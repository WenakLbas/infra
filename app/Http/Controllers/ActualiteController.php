<?php

namespace App\Http\Controllers;

use DB;
use File;
use App\Models\Url;
use App\Models\Url_Path;
use App\Models\Actualite;
use Illuminate\Http\Request;


class ActualiteController extends Controller
{
    public function actualites()
    {
        $actualites = DB::table('actualites')
            ->join('admins','actualites.admin_id','=','admins.id')
            ->leftjoin('reports', 'reports.actus_id',  '=', 'actualites.id')
            ->select('admins.id as admin_id','admins.name', 'reports.id as rep_id', 'reports.link', 'admins.email', 'actualites.*')
            ->get();
        return response()->json($actualites, 200);
    }

    public function findActualite($id)
    {
        try{
            $actualite = DB::table('actualites')
                ->join('admins','actualites.admin_id','=','admins.id')
                ->leftjoin('reports', 'reports.actus_id',  '=', 'actualites.id')
                ->select('admins.id as admin_id','admins.name', 'reports.id as rep_id', 'reports.link', 'admins.email', 'actualites.*')
                ->where(['actualites.id' => $id])
                ->get();
            if (!$actualite) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json($actualite[0], 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }


    public function storeActualite(Request $request){

        $this->validate($request, [
            'titre' => 'required',
            'slag' => 'required',
            'description' => 'required'
        ]);

        $actus = new Actualite;
        if($request->hasFile('link')){
            $doc = $request->file('link');
            $fileName = $request->file('link')->getClientOriginalName();
            $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
            $fileExtension = $request->file('link')->getClientOriginalExtension();
            $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
            $dest = public_path('/images/actualites/');
            $doc->move($dest, $input['fileName']);
            // dd($input['fileName']);
            $actus->imgurl = Url::URL_ACTUALITES.$input['fileName'];
            $actus->titre = $request->titre;
            $actus->slag = $request->slag;
            $actus->description = $request->description;
            $actus->admin_id = $request->admin_id;
            $actus->super_id = $request->super_id;

            if($actus->imgurl == null ){
                return response()->json([
                    'success' => false,
                    'message' => "Le Rapport n'a pas été soumis"
                ], 500);
            }else{
                try {
                    if ($actus->save())
                        return response()->json([
                            'success' => true,
                            'message' => "Le Rapport est soumis avec successe."
                        ], 201);
                    else
                        return response()->json([
                            'success' => false,
                            'message' => "Le Rapport n'a pas été soumis"
                        ], 500);
                }catch (\Exception $e) {
                    return $e->getMessage();
                }
            }
        }
    }


    public function updateActualite(Request $request) {
        try {
            $actua = Actualite::where('id', $request->id)->first();
            $actus =  array();
            if($request->hasFile('link')){
                $doc = $request->file('link');
                $fileName = $request->file('link')->getClientOriginalName();
                $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
                $fileExtension = $request->file('link')->getClientOriginalExtension();
                $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
                $dest = public_path('/images/actualites/');
                $doc->move($dest, $input['fileName']);
                // dd($input['fileName']);
                $actus['imgurl'] = Url::URL_ACTUALITES.$input['fileName'];
                $actus['super_id'] = $request->super_id;
                $actus['admin_id'] = $request->admin_id;

                $actus['titre'] = is_null($request->titre) ? $actua->titre : $request->titre;
                $actus['slag'] = is_null($request->slag) ? $actua->slag : $request->slag;
                $actus['description'] = is_null($request->description) ? $actua->description : $request->description;

                $this->deleteImage($actua->imgurl);
                if ($actus['imgurl'] == null){
                    $actus['imgurl'] = $actua->imgurl;
                }
                $updated = DB::table('actualites')->where('id', $request->id)->update($actus);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "L'Article a ete mis a jour avec successe...!"
                    ], 200);
                }
            }else{
                $actus['super_id'] = $request->super_id;
                $actus['admin_id'] = $request->admin_id;

                $actus['titre'] = is_null($request->titre) ? $actua->titre : $request->titre;
                $actus['slag'] = is_null($request->slag) ? $actua->slag : $request->slag;
                $actus['description'] = is_null($request->description) ? $actua->description : $request->description;

                $updated = DB::table('actualites')->where('id', $request->id)->update($actus);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "L'Article a ete mis a jour avec successe...!"
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
        if($path !== Url_Path::URL.'actualites/'){
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
        $image_path = 'images/actualites/'.$imageName;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }
    }

    public function deleteActualite($id) {
        try {
            $actus = Actualite::where('id', $id)->get();
            if($actus) {
                Actualite::where('id', $id)->delete();
                $this->deleteImage($actus[0]->imgurl);
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
