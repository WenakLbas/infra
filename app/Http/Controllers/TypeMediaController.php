<?php

namespace App\Http\Controllers;

use DB;
use App\Models\TypeMedia;
use Illuminate\Http\Request;

class TypeMediaController extends Controller
{
    public function typeMedias()
    {
        $tmedias = DB::table('type_medias')
            ->get();
        return response()->json($tmedias);
    }


    public function findTypeMedia($id)
    {
        try{
            $tmedia = DB::table('type_medias')
                ->join('admins','type_medias.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'type_medias.*')
                ->where(['type_medias.id' => $id])
                ->get();
            if (!$tmedia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $tmedia
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeTypeMedia(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',
            'description' => 'required',
        ]);

        $tMedia = new TypeMedia();
        $tMedia->admin_id = $request->admin_id;
        $tMedia->super_id = $request->super_id;
        $tMedia->libelle = $request->libelle;
        $tMedia->description = $request->description;

        if ($tMedia->save())
            return response()->json([
                'success' => true,
                'Media' => $tMedia->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateTypeMedia(Request $request, $id) {
        try {
            $tMediad = TypeMedia::where('id', $id)->first();
            $tMedia = array();
            $tMedia['libelle'] = is_null($request->libelle) ? $tMediad->libelle : $request->libelle;
            $tMedia['description'] = is_null($request->description) ? $tMediad->description : $request->description;
            $tMedia['admin_id'] = $request->admin_id;
            $tMedia['super_id'] = $request->super_id;
            $updated = DB::table('type_medias')->where('id', $id)->update($tMedia);
            if ($updated){
                return response()->json([
                    'success' => true,
                    'message' => "L'enregistrement a ete modifier avec successe...!"
                ], 200);
            }
        }catch (\Exception $e){
            return response()->json([
                "message" => $e->getMessage()
                //"message" => 'Cet enregistrement n\'existe pas'
            ], 404);
        }
    }


    public function deleteTypeMedia($id) {
        try {
            $infra = TypeMedia::where('id', $id)->get();
            if($infra) {
                TypeMedia::where('id', $id)->delete();
                return response()->json([
                    "message" => 'Cet enregistrement est supprimé définitivement'
                ], 202);

            } else {
                return response()->json([
                    "message" => 'Cet enregistrement n\'existe pas'
                    // "message" => $blog
                ], 404);
            }
        }catch (\Exception $e){
            return response()->json([
                "message" => 'Cet enregistrement ne pas etre supprimer'
                //'message' => $e->getMessage()
            ], 400);
        }
    }
}

