<?php

namespace App\Http\Controllers;

use DB;
use File;
use App\Models\Url;
use App\Models\Teams;
use App\Models\Url_Path;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    public function teams()
    {
        $part = DB::table('teams')
            ->get();
        return response()->json($part);
    }


    public function findTeam($id)
    {
        try{
            $part = DB::table('teams')
                ->join('admins','teams.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'teams.*')
                ->where(['teams.id' => $id])
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

    public function storeTeam(Request $request)
    {
        $team = new Teams();

        if($request->hasFile('img')){
            $doc = $request->file('img');
            $fileName = $request->file('img')->getClientOriginalName();
            $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
            $fileExtension = $request->file('img')->getClientOriginalExtension();
            $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
            $dest = public_path('/images/teams/');
            $doc->move($dest, $input['fileName']);

            $team->imgurl = Url::URL_TEAMS.$input['fileName'];

            $team->admin_id = $request->admin_id;
            $team->super_id = $request->super_id;
            $team->name = $request->name;
            $team->fnction = $request->fnction;
            $team->description = $request->description;

            if($team->imgurl == null ){
                return response()->json([
                    'success' => false,
                    'message' => "L'Enregistrement n'a pas été soumis"
                ], 500);
            }else{
                try {
                    if ($team->save())
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

    public function updateTeam(Request $request, $id) {
        try {
            $teamdb = Teams::where('id', $id)->first();
            $team = array();
            if($request->hasFile('img')){
                $doc = $request->file('img');
                $fileName = $request->file('img')->getClientOriginalName();
                $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
                $fileExtension = $request->file('img')->getClientOriginalExtension();
                $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
                $dest = public_path('/images/teams/');
                $doc->move($dest, $input['fileName']);
                // dd($input['fileName']);
                $team['imgurl'] = Url::URL_TEAMS.$input['fileName'];
                $team['super_id'] = $request->super_id;
                $team['admin_id'] = $request->admin_id;

                $team['name'] = is_null($request->name) ? $teamdb->name : $request->name;
                $team['fnction'] = is_null($request->fnction) ? $teamdb->fnction : $request->fnction;
                $team['description'] = is_null($request->description) ? $teamdb->description : $request->description;

                if ($team['imgurl'] == null){
                    $team['imgurl'] = $teamdb->imgurl;
                }else{
                    $this->deleteImage($teamdb->imgurl);
                }

                $updated = DB::table('teams')->where('id', $request->id)->update($team);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "L'Enregistrement a ete mis a jour avec successe...!"
                    ], 200);
                }
            }else{
                $team['super_id'] = $request->super_id;
                $team['admin_id'] = $request->admin_id;

                $team['name'] = is_null($request->name) ? $teamdb->name : $request->name;
                $team['fnction'] = is_null($request->fnction) ? $teamdb->fnction : $request->fnction;
                $team['description'] = is_null($request->description) ? $teamdb->description : $request->description;
                $team['imgurl'] = is_null($request->imgurl) ? $teamdb->imgurl : $request->imgurl;

                $updated = DB::table('teams')->where('id', $request->id)->update($team);
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
        if($path !== Url_Path::URL.'teams/'){
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
        $image_path = 'images/teams/'.$imageName;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }
    }

    public function deleteTeam($id) {
        try {
            $part = Teams::where('id', $id)->get();
            if($part) {
                Teams::where('id', $id)->delete();
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
