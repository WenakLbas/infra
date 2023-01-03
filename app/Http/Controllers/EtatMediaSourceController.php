<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\EtatMediaSource;

class EtatMediaSourceController extends Controller
{
    public function etatMediaSources()
    {
        $etatmedias = DB::table('etat_media_sources')
            ->get();
        return response()->json($etatmedias, 200);
    }

    public function findEtatMediaSource($id)
    {
        try{
            $etatmedia = DB::table('etat_media_sources')
                ->join('admins','etat_media_sources.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'etat_media_sources.*')
                ->where(['etat_media_sources.id' => $id])
                ->get();
            if (!$etatmedia) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $etatmedia
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeEtatMediaSource(Request $request)
    {
        $this->validate($request, [
            'id_media_source' => 'required',
            'id_etat_infra' => 'required',
        ]);

        $etatmedia = new EtatMediaSource();
        $etatmedia->admin_id = $request->admin_id;
        $etatmedia->super_id = $request->super_id;
        $etatmedia->id_media_source = $request->id_media_source;
        $etatmedia->id_etat_infra = $request->id_etat_infra;

        if ($etatmedia->save())
            return response()->json( $etatmedia->toArray(), 200);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateEtatMediaSource(Request $request, $id) {
        try {
            $etatmediadb = EtatMediaSource::where('id', $request->id)->first();
            $etatmedia = array();
            $etatmedia['id_media_source'] = is_null($request->id_media_source) ? $etatmediadb->id_media_source : $request->id_media_source;
            $etatmedia['id_etat_infra'] = is_null($request->id_etat_infra) ? $etatmediadb->id_etat_infra : $request->id_etat_infra;
            $etatmedia['admin_id'] = $request->admin_id;
            $etatmedia['super_id'] = $request->super_id;
            $updated = DB::table('etat_media_sources')->where('id', $request->id)->update($etatmedia);
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


    public function deleteEtatMediaSource($id) {
        try {
            $etatmediadb = EtatMediaSource::where('id', $id)->get();
            if($etatmediadb) {
                EtatMediaSource::where('id', $id)->delete();
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
