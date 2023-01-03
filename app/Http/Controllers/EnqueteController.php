<?php

namespace App\Http\Controllers;

use DB;
use File;
use App\Models\Url;
use App\Models\Enquete;
use App\Models\Url_Path;
use Illuminate\Http\Request;

class EnqueteController extends Controller
{
    public function enquetes()
    {
        $enquetes = DB::table('enquetes')
            ->join('admins','enquetes.admin_id','=','admins.id')
            ->leftjoin('reports', 'reports.enq_id',  '=', 'enquetes.id')
            ->select('admins.id as admin_id','admins.name', 'reports.id as rep_id', 'reports.link', 'admins.email', 'enquetes.*')
            ->get();
        return response()->json($enquetes, 200);
    }

    public function findEnquete($id)
    {
        try{
            $enq = DB::table('enquetes')
                ->join('admins','enquetes.admin_id','=','admins.id')
                ->leftjoin('reports', 'reports.enq_id',  '=', 'enquetes.id')
                ->select('admins.id as admin_id','admins.name', 'reports.id as rep_id', 'reports.link', 'admins.email', 'enquetes.*')
                ->where(['enquetes.id' => $id])
                ->get();
            if (!$enq) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json($enq[0], 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }


    public function storEnquete(Request $request){

        $this->validate($request, [
            'titre' => 'required',
            'slag' => 'required',
            'description' => 'required'
        ]);

        $enq = new Enquete;
        if($request->hasFile('link')){
            $doc = $request->file('link');
            $fileName = $request->file('link')->getClientOriginalName();
            $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
            $fileExtension = $request->file('link')->getClientOriginalExtension();
            $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
            $dest = public_path('/images/enquetes/');
            $doc->move($dest, $input['fileName']);
            // dd($input['fileName']);
            $enq->imgurl = Url::URL_ENQUETES.$input['fileName'];
            $enq->titre = $request->titre;
            $enq->slag = $request->slag;
            $enq->description = $request->description;
            $enq->admin_id = $request->admin_id;
            $enq->super_id = $request->super_id;

            if($enq->imgurl == null ){
                return response()->json([
                    'success' => false,
                    'message' => "Le Rapport n'a pas été soumis"
                ], 500);
            }else{
                try {
                    if ($enq->save())
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


    public function updateEnquete(Request $request) {
        try {
            $enque = Enquete::where('id', $request->id)->first();
            $enq =  array();
            if($request->hasFile('link')){
                $doc = $request->file('link');
                $fileName = $request->file('link')->getClientOriginalName();
                $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
                $fileExtension = $request->file('link')->getClientOriginalExtension();
                $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
                $dest = public_path('/images/enquetes/');
                $doc->move($dest, $input['fileName']);
                // dd($input['fileName']);
                $enq['imgurl'] = Url::URL_ENQUETES.$input['fileName'];
                $enq['super_id'] = $request->super_id;
                $enq['admin_id'] = $request->admin_id;

                $enq['titre'] = is_null($request->titre) ? $enque->titre : $request->titre;
                $enq['slag'] = is_null($request->slag) ? $enque->slag : $request->slag;
                $enq['description'] = is_null($request->description) ? $enque->description : $request->description;

                $this->deleteImage($enque->imgurl);
                if ($enq['imgurl'] == null){
                    $enq['imgurl'] = $enque->imgurl;
                }
                $updated = DB::table('enquetes')->where('id', $request->id)->update($enq);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "L'Article a ete mis a jour avec successe...!"
                    ], 200);
                }
            }else{
                $enq['super_id'] = $request->super_id;
                $enq['admin_id'] = $request->admin_id;

                $enq['titre'] = is_null($request->titre) ? $enque->titre : $request->titre;
                $enq['slag'] = is_null($request->slag) ? $enque->slag : $request->slag;
                $enq['description'] = is_null($request->description) ? $enque->description : $request->description;

                $updated = DB::table('enquetes')->where('id', $request->id)->update($enq);
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
        if($path !== Url_Path::URL.'enquetes/'){
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
        $image_path = 'images/enquetes/'.$imageName;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }
    }

    public function deleteEnquete($id) {
        try {
            $enq = Enquete::where('id', $id)->get();
            if($enq) {
                Enquete::where('id', $id)->delete();
                $this->deleteImage($enq[0]->imgurl);
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
