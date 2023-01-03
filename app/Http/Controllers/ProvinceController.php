<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function provinces()
    {
        $provinces = DB::table('provinces')
            ->get();
        return response()->json($provinces, 200);
    }


    public function findprovinces($id)
    {
        try{
            $provinces = DB::table('provinces')
                ->join('admins','provinces.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'provinces.*')
                ->where(['provinces.id' => $id])
                ->get();
            if (!$provinces) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $provinces
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeProvince(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',
        ]);

        $provinc = new Province();
        $provinc->admin_id = $request->admin_id;
        $provinc->super_id = $request->super_id;
        $provinc->libelle = $request->libelle;

        if ($provinc->save())
            return response()->json([
                'success' => true,
                'text' => $provinc->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateProvince(Request $request, $id) {
        try {
            $provincdb = Province::where('id', $request->id)->first();
            $provin = array();
            $provin['libelle'] = is_null($request->libelle) ? $provincdb->libelle : $request->libelle;
            $provin['admin_id'] = $request->admin_id;
            $provin['super_id'] = $request->super_id;
            $updated = DB::table('provinces')->where('id', $request->id)->update($provin);
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


    public function deleteProvince ($id) {
        try {
            $provincdb = Province::where('id', $id)->get();
            if($provincdb) {
                Province::where('id', $id)->delete();
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


