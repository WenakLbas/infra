<?php

namespace App\Http\Controllers;

use DB;
use App\Models\AutoriteSaisie;
use Illuminate\Http\Request;

class AutoriteSaisieController extends Controller
{
    public function autoriteSaisies()
    {
        $autos = DB::table('autoriteSaisies')
            ->get();
        return response()->json($autos);
    }

    public function findAutoriteSaisie($id)
    {
        try{
            $auto = DB::table('autoriteSaisies')
                ->join('admins','autoriteSaisies.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'autoriteSaisies.*')
                ->where(['autoriteSaisies.id' => $id])
                ->get();
            if (!$auto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $auto
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeAutoriteSaisie(Request $request)
    {
        $this->validate($request, [
            'titre' => 'required',
            'abilite_recevoire_plainte' => 'required',
            'abilite_recevoire_denociation' => 'required',
            'abilite_ouvrir_enquete' => 'required',
            'sys_judiciare_appartenance' => 'required',
        ]);

        $auto = new AutoriteSaisie();
        $auto->admin_id = $request->admin_id;
        $auto->super_id = $request->super_id;
        $auto->titre = $request->titre;
        $auto->abilite_recevoire_plainte = $request->abilite_recevoire_plainte;
        $auto->abilite_recevoire_denociation = $request->abilite_recevoire_denociation;
        $auto->abilite_ouvrir_enquete = $request->abilite_ouvrir_enquete;
        $auto->sys_judiciare_appartenance = $request->sys_judiciare_appartenance;

        if ($auto->save())
            return response()->json([
                'success' => true,
                'text' => $auto->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }



     public function updateAutoriteSaisie(Request $request, $id) {
           try {
               $autodb = AutoriteSaisie::where('id', $id)->first();

               $auto = array();
               $auto['titre'] = is_null($request->titre) ? $autodb->titre : $request->titre;
               $auto['abilite_recevoire_plainte'] = is_null($request->abilite_recevoire_plainte) ? $autodb->abilite_recevoire_plainte : $request->abilite_recevoire_plainte;
               $auto['abilite_recevoire_denociation'] = is_null($request->abilite_recevoire_denociation) ? $autodb->abilite_recevoire_denociation : $request->abilite_recevoire_denociation;
               $auto['abilite_ouvrir_enquete'] = is_null($request->abilite_ouvrir_enquete) ? $autodb->abilite_ouvrir_enquete : $request->abilite_ouvrir_enquete;
               $auto['sys_judiciare_appartenance'] = is_null($request->sys_judiciare_appartenance) ? $autodb->sys_judiciare_appartenance : $request->sys_judiciare_appartenance;

               $auto['admin_id'] = $request->admin_id;
               $auto['super_id'] = $request->super_id;


                $updated = DB::table('autoriteSaisies')->where('id', $request->id)->update($auto);

                if ($updated){
                    return response()->json([
                        'success' => true,
                        'message' => "L'enregistrement a ete modifier avec successe...!"
                    ], 201);
                }

        }catch (\Exception $e){
            return response()->json([
                "message" => $e->getMessage()
                //"message" => 'Cet enregistrement n\'existe pas'
            ], 404);
        }
    }


    public function deleteAutoriteSaisie($id) {
        try {
            $autodb = AutoriteSaisie::where('id', $id)->get();
            if($autodb) {
                AutoriteSaisie::where('id', $id)->delete();
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

