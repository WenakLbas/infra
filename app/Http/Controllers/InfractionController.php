<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Infraction;
use Illuminate\Http\Request;

class InfractionController extends Controller
{
    public function allInfractions()
    {
        $infras = DB::table('infractions')
            ->join('admins','infractions.admin_id','=','admins.id')
            ->leftjoin('lieu_infras', 'lieu_infras.infra_id', '=', 'infractions.id')
            ->leftjoin('type_infractions', 'type_infractions.id', '=', 'infractions.type_id')
            ->leftjoin('reports', 'reports.infra_id',  '=', 'infractions.id')
            ->select('infractions.*', 'lieu_infras.libelle as lieu', 'lieu_infras.id as lieu_id',
                'type_infractions.id as tinfra_id', 'type_infractions.libelle as t_infra_lib',
                'reports.id as rep_id', 'reports.link')
            ->get();
        return response()->json($infras, 200);
    }


    public function infractions()
    {
        $infras = DB::table('infractions')->get();
        return response()->json(
             $infras
        );
    }

    public function countInfras()
    {
        $cinifs = \DB::table('infractions, provinces')
            ->join('type_infractions', 'type_infractions.id', '=', 'infractions.type_id')
            ->join('etat_infra_jours', 'etat_infra_jours.id_province', '=', 'provinces.id')
            ->where(['type_infractions.libelle' => 'Crime'])
            ->count();
        return response()->json($cinifs, 200);
    }

    public function countInfrasViol()
    {
        $cinifs = \DB::table('infractions')
            ->join('type_infractions', 'type_infractions.id', '=', 'infractions.type_id')
            ->where(['type_infractions.libelle' => 'Violence'])
            ->count();
        return response()->json($cinifs, 200);
    }


    public function countInfrasDeon()
    {
        $cinifs = \DB::table('infractions')
            ->join('type_infractions', 'type_infractions.id', '=', 'infractions.type_id')
            ->where(['type_infractions.libelle' => 'Deontologie Policiare'])
            ->count();
        return response()->json($cinifs, 200);
    }


    public function infraction($id)
    {
        try{
            $infra = DB::table('infractions')
                ->join('admins','infractions.admin_id','=','admins.id')
                ->leftjoin('lieu_infras', 'lieu_infras.infra_id', '=', 'infractions.id')
                ->leftjoin('type_infractions', 'type_infractions.id', '=', 'infractions.type_id')
                ->leftjoin('reports', 'reports.infra_id',  '=', 'infractions.id')
                ->select('infractions.*', 'lieu_infras.libelle as lieu', 'lieu_infras.id as lieu_id',
                    'type_infractions.id as infra_id', 'type_infractions.libelle',
                    'reports.id as rep_id', 'reports.link',
                    'admins.name', 'admins.phone',  'admins.email',  'admins.user_profile')
                ->where(['infractions.id' => $id])
                ->get();
            if (!$infra) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }
            return response()->json( $infra[0], 200);

        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeInfra(Request $request)
    {
        $this->validate($request, [
            'libelle_inf' => 'required',
            'type_id' => 'required',
        ]);

        $infra = new Infraction();
        $infra->admin_id = $request->admin_id;
        $infra->super_id = $request->super_id;
        $infra->libelle_inf = $request->libelle_inf;
        $infra->type_id = $request->type_id;
        $infra->date = $request->date;

        if ($infra->save())
            return response()->json([
                'success' => true,
                'text' => $infra->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateInfra(Request $request, $id) {
        try {
            $infr = Infraction::where('id', $id)->first();
            $infra = array();
            $infra['libelle_inf'] = is_null($request->libelle_inf) ? $infr->libelle_inf : $request->libelle_inf;
            $infra['type_id'] = is_null($request->type_id) ? $infr->type_id : $request->type_id;
            $infra['date'] = is_null($request->date) ? $infr->date : $request->date;
            $infra['admin_id'] = $request->admin_id;
            $infra['super_id'] = $request->super_id;
            $updated = DB::table('infractions')->where('id', $id)->update($infra);
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


    public function deleteInfra ($id) {
        try {
            $infra = Infraction::where('id', $id)->get();
            if($infra) {
                Infraction::where('id', $id)->delete();
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

