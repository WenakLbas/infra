<?php

namespace App\Http\Controllers;

use DB;
use File;
use App\Models\Url;
use App\Models\Url_Path;
use App\Models\Victime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VictimeController extends Controller
{

    public function victimes()
    {
        $user = DB::table('victimes')->get()->all();
        return response()->json($user, 200);
    }


    public function findVictime($id)
    {
        try{
            $victime = DB::table('victimes')
                ->join('admins','victimes.admin_id','=','admins.id')
                ->select('admins.id as admin_id','admins.name', 'admins.email', 'victimes.*')
                ->where(['victimes.id' => $id])
                ->get();
            if (!$victime) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $victime[0]
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function saveVictime(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255'
        ]);
        $victime = new Victime;

        if($request->hasFile('image')){
            $doc = $request->file('image');
            $fileName = $request->file('image')->getClientOriginalName();
            $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
            $fileExtension = $request->file('image')->getClientOriginalExtension();
            $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
            $dest = public_path('/images/victimes/');
            $doc->move($dest, $input['fileName']);
            // dd($input['fileName']);
            $victime->profile = Url::URL_VICTIMES.$input['fileName'];
            $victime->nom = $request->nom;
            $victime->prenom = $request->prenom;
            $victime->admin_id = $request->admin_id;
            $victime->super_id = $request->super_id;
            if($victime->profile == null ){
                $victime->profile = Url::ALL_PROFILE;
            }else{
                try {
                    if ($victime->save())
                        return response()->json([
                            'success' => true,
                            'message' => "L'Enregistrement  est soumis avec successe."
                        ], 201);
                    else
                        return response()->json([
                            'success' => false,
                            'message' => "L'Enregistrement  n'a pas été soumis"
                        ], 500);
                }catch (\Exception $e) {
                    return $e->getMessage();
                }
            }
        }else{
            $victime->nom = $request->nom;
            $victime->prenom = $request->prenom;
            $victime->admin_id = $request->admin_id;
            $victime->super_id = $request->super_id;
            try {
                if ($victime->save())
                    return response()->json([
                        'success' => true,
                        'message' => "L'Enregistrement  est soumis avec successe."
                    ], 201);
                else
                    return response()->json([
                        'success' => false,
                        'message' => "L'Enregistrement  n'a pas été soumis"
                    ], 500);
            }catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }


    public function updateVictime(Request $request, $id) {
        try {
            $vctime = Victime::where('id', $request->id)->first();
            $enq =  array();
            if($request->hasFile('image')){
                $doc = $request->file('image');
                $fileName = $request->file('image')->getClientOriginalName();
                $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
                $fileExtension = $request->file('image')->getClientOriginalExtension();
                $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
                $dest = public_path('/images/victimes/');
                $doc->move($dest, $input['fileName']);
                // dd($input['fileName']);
                $victime['profile'] = Url::URL_VICTIMES.$input['fileName'];
                $victime['super_id'] = $request->super_id;
                $victime['admin_id'] = $request->admin_id;

                $victime['nom'] = is_null($request->nom) ? $vctime->nom : $request->nom;
                $victime['prenom'] = is_null($request->prenom) ? $vctime->prenom : $request->prenom;

                $this->deleteImage($vctime->profile);
                if ($victime['profile'] == null){
                    $victime['profile'] = $vctime->imgurl;
                }
                $updated = DB::table('victimes')->where('id', $request->id)->update($victime);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "L'Article a ete mis a jour avec successe...!"
                    ], 200);
                }
            }else{
                $victime['super_id'] = $request->super_id;
                $victime['admin_id'] = $request->admin_id;

                $victime['nom'] = is_null($request->nom) ? $vctime->nom : $request->nom;
                $victime['prenom'] = is_null($request->prenom) ? $vctime->prenom : $request->prenom;

                $updated = DB::table('victimes')->where('id', $request->id)->update($victime);
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
        if($path !== Url_Path::URL."victimes/"){
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
        $image_path = 'images/victimes/'.$imageName;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }
    }



    public function deleteVictime ($id) {
        try {
            $admin = Victime::where('id', $id)->get();
            if($admin) {
                $this->deleteImage($admin[0]->user_profile);
                Victime::where('id', $id)->delete();
                return response()->json([
                    "message" => 'Cet Administrateur est supprimé définitivement'
                ], 202);
            } else {
                return response()->json([
                    "message" => 'Cet Administrateur n\'existe pas'
                    // "message" => $team
                ], 404);
            }
        }catch (\Exception $e){
            return response()->json([
                // "message" => 'Cet Administrateur n\'existe pas'
                'message' => $e->getMessage()
                // "message" => $team
            ], 404);
        }
    }
}
