<?php

namespace App\Http\Controllers;

use DB;
use App\Models\SourceMedia;
use Illuminate\Http\Request;

class SourceMediaController extends Controller
{
    public function sourceMedias()
    {
        $sMedias = DB::table('source_medias')
            ->get();
        return response()->json($sMedias, 200);
    }


    public function findSourceMedia($id)
    {
        try{
            $fsi = DB::table('source_medias')
                ->join('admins','type_medias.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'source_medias.*')
                ->where(['source_medias.id' => $id])
                ->get();
            if (!$fsi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $fsi
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeSourceMedia(Request $request)
    {
        $this->validate($request, [
            'tmedia_id' => 'required',
            'libelle' => 'required',
            'date' => 'required',
        ]);

        $sMedia = new SourceMedia();
        $sMedia->admin_id = $request->admin_id;
        $sMedia->super_id = $request->super_id;
        $sMedia->tmedia_id = $request->tmedia_id;
        $sMedia->libelle = $request->libelle;
        $sMedia->date = $request->date;

        if ($sMedia->save())
            return response()->json([
                'success' => true,
                'text' => $sMedia->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateSourceMedia(Request $request, $id) {
        try {
            $tsMediad = SourceMedia::where('id', $id)->first();
            $sMedia = array();
            $sMedia['tmedia_id'] = is_null($request->tmedia_id) ? $tsMediad->tmedia_id : $request->tmedia_id;
            $sMedia['libelle'] = is_null($request->libelle) ? $tsMediad->libelle : $request->libelle;
            $sMedia['date'] = is_null($request->date) ? $tsMediad->date : $request->date;
            $sMedia['admin_id'] = $request->admin_id;
            $sMedia['super_id'] = $request->super_id;
            $updated = DB::table('source_medias')->where('id', $id)->update($sMedia);
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


    public function deleteSourceMedia($id) {
        try {
            $sMedia = SourceMedia::where('id', $id)->get();
            if($sMedia) {
                SourceMedia::where('id', $id)->delete();
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

