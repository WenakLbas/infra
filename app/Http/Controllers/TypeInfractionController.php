<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\TypeInfraction;

class TypeInfractionController extends Controller
{
    public function type_infractions()
    {
        $type_infras = DB::table('type_infractions')->get();
        return $type_infras->toArray();
    }

    public function type_infras()
    {
        $type_infras = DB::table('type_infractions')
            ->join('admins','type_infractions.admin_id','=','admins.id')
            ->select('admins.id as admins_id','admins.name',  'admins.email',  'type_infractions.*')
            ->get();
        return $type_infras->toArray();
    }

    public function type_infra($id)
    {
        try{
            $type_infras = DB::table('type_infractions')
                ->join('admins','type_infractions.admin_id','=','admins.id')
                ->join('superadmins','type_infractions.super_id','=','superadmins.id')
                ->select('admins.id as admins_id','admins.name',  'admins.email',  'type_infractions.*')
                ->where(['type_infractions.id' => $id])
                ->get();
            if (!$type_infras) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }
            return $type_infras->toArray();
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeTypeInfra(Request $request)
    {
        $this->validate($request, [
            'libelle' => 'required',
        ]);

        $tinfra = new TypeInfraction();
        $tinfra->admin_id = $request->admin_id;
        $tinfra->super_id = $request->super_id;
        $tinfra->libelle = $request->libelle;

        if ($tinfra->save())
            return response()->json([
                'success' => true,
                'text' => $tinfra->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateInfraction(Request $request, $id) {
        try {
            $tnfra = TypeInfraction::where('id', $request->$id)->first();
            $tinfra = array();
            $tinfra['libelle'] = is_null($request->libelle) ? $tnfra->libelle : $request->libelle;
            $tinfra['admin_id'] = $request->admin_id;
            $tinfra['super_id'] = $request->super_id;
            $updated = DB::table('type_infractions')->where('id', $request->$id)->update($tinfra);
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

    public function deleteInfraction($id) {
        try {
            $tinfra = TypeInfraction::where('id', $id)->get();
            if($tinfra) {
                TypeInfraction::where('id', $id)->delete();
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

