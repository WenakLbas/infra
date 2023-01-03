<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\EtatInfraJour;

class EtatInfraJourController extends Controller
{
    public function etat_infrjrs()
    {
        $etat_infrjrs = DB::table('etat_infra_jours')
            ->get();
        return response()->json([
            'success' => true,
            '$etat_infrjrs' => $etat_infrjrs,
        ]);
    }

    public function etatInfras()
    {
        $infras = DB::table('etat_infra_jours')
            ->join('infractions', 'etat_infra_jours.id_infra', '=', 'infractions.id')
            ->join('type_infractions', 'etat_infra_jours.id_tinfra', '=', 'type_infractions.id')
            ->join('tranche_ages', 'etat_infra_jours.id_age', '=', 'tranche_ages.id')
            ->join('autoritesaisies', 'etat_infra_jours.id_autho', '=', 'autoritesaisies.id')
            ->join('gerdeavues', 'etat_infra_jours.id_gravue', '=', 'gerdeavues.id')
            ->join('lieu_infras', 'etat_infra_jours.id_lieu_infra', '=', 'lieu_infras.id')
            ->join('type_enquetes', 'etat_infra_jours.id_enqt', '=', 'type_enquetes.id')
            ->join('rsnonenqtes', 'etat_infra_jours.id_neqte', '=', 'rsnonenqtes.id')
            ->join('source_judiciaires', 'etat_infra_jours.id_judiciaire', '=', 'source_judiciaires.id')
            ->join('oscs', 'etat_infra_jours.id_osc', '=', 'oscs.id')
            ->join('provinces', 'etat_infra_jours.id_province', '=', 'provinces.id')
            ->join('decision_finals', 'etat_infra_jours.id_decision', '=', 'decision_finals.id')
            ->select('etat_infra_jours.id', 'infractions.date', 'infractions.libelle_inf', 'type_infractions.libelle as type_infra',
                'tranche_ages.libelle as tranche_age',  'autoritesaisies.titre as aut_saisies',
                'gerdeavues.libelle as gerdeavue', 'lieu_infras.libelle as lieu_infra',
                'type_enquetes.libelle as type_enquete', 'source_judiciaires.fonction as source_judiciaires', 'oscs.nom as oscs',
                'rsnonenqtes.libelle as rsnonenqte', 'provinces.description as province', 'decision_finals.libelle as decision_final', )
            ->get();

        return response()->json($infras);
    }

    public function etatInfra($id)
    {
        $infras = DB::table('etat_infra_jours')
            ->join('infractions', 'etat_infra_jours.id_infra', '=', 'infractions.id')
            ->join('type_infractions', 'etat_infra_jours.id_tinfra', '=', 'type_infractions.id')
            ->join('tranche_ages', 'etat_infra_jours.id_age', '=', 'tranche_ages.id')
            ->join('autoritesaisies', 'etat_infra_jours.id_autho', '=', 'autoritesaisies.id')
            ->join('gerdeavues', 'etat_infra_jours.id_gravue', '=', 'gerdeavues.id')
            ->join('lieu_infras', 'etat_infra_jours.id_lieu_infra', '=', 'lieu_infras.id')
            ->join('type_enquetes', 'etat_infra_jours.id_enqt', '=', 'type_enquetes.id')
            ->join('rsnonenqtes', 'etat_infra_jours.id_neqte', '=', 'rsnonenqtes.id')
            ->join('source_judiciaires', 'etat_infra_jours.id_judiciaire', '=', 'source_judiciaires.id')
            ->join('oscs', 'etat_infra_jours.id_osc', '=', 'oscs.id')
            ->join('provinces', 'etat_infra_jours.id_province', '=', 'provinces.id')
            ->join('decision_finals', 'etat_infra_jours.id_decision', '=', 'decision_finals.id')
            ->select('etat_infra_jours.id', 'infractions.date', 'infractions.libelle_inf', 'type_infractions.libelle as type_infra',
                'tranche_ages.libelle as tranche_age',  'autoritesaisies.titre as aut_saisies',
                'gerdeavues.libelle as gerdeavue', 'lieu_infras.libelle as lieu_infra',
                'type_enquetes.libelle as type_enquete', 'source_judiciaires.fonction as source_judiciaires', 'oscs.nom as oscs',
                'rsnonenqtes.libelle as rsnonenqte', 'provinces.description as province', 'decision_finals.libelle as decision_final', )
            ->where(['etat_infra_jours.id' => $id])
            ->get();

        return response()->json($infras[0]);
    }

    public function etatInfrCount()
    {
        $count = DB::table('etat_infra_jours')
            ->select(array('provinces.id', 'provinces.libelle', DB::raw('COUNT(provinces.id) as infras')))
            ->join('provinces', 'etat_infra_jours.id_province', '=', 'provinces.id')
            ->groupBy('provinces.id')
            ->groupBy('provinces.libelle')
            ->get();

        return response()->json($count->toArray());
    }

    public function etatInfrCount_3()
    {
        $count = DB::table('infras_imports')
            ->select('province', DB::raw('count(*) as infras'))
            ->groupBy('province')
            ->orderBy('infras', 'DESC')
            ->get()
            ->take(4);

        return response()->json($count->toArray());
    }

    public function infraCount()
    {
        $count = DB::table('infras_imports')
            ->select('province', DB::raw('count(*) as infras'))
            ->groupBy('province')
            ->get();

        return response()->json($count->toArray());
    }

    public function infraCount_4()
    {
        $count = DB::table('infras_imports')
            ->select(array('id', 'province', DB::raw('COUNT(province) as infras')))
            ->groupBy('id')
            ->groupBy('province')
            ->orderBy('infras', 'ASC')
            ->get()
            ->take(4);

        return response()->json($count->toArray());
    }


    public function findautorite($id)
    {
        try{
            $autoritesaisie = DB::table('autoritesaisie')
                ->join('admins','autoritesaisie.admin_id','=','admin_id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'autoritesaisie.*')
                ->where(['autoritesaisie.id' => $id])
                ->get();
            if (!$autoritesaisie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json(
                    $autoritesaisie
                    , 200);
            }
        }catch (\Exception $exception){
            return response()->json([
                'message' => $exception
            ], 400);
        }
    }

    public function storeEtatInfraJrs(Request $request)
    {
        $this->validate($request, [
            'date_infra' => 'required'
        ]);
        $etat_infrajrs = new EtatInfraJour();
        $etat_infrajrs->admin_id = $request->admin_id;
        $etat_infrajrs->super_id = $request->super_id;
        $etat_infrajrs->date_infra = $request->date_infra;
        $etat_infrajrs->id_infra = $request->id_infra;
        $etat_infrajrs->etat_denonciation = $request->etat_denonciation;
        $etat_infrajrs->id_autho = $request->id_autho;
        $etat_infrajrs->etat_enquette = $request->etat_enquette;
        $etat_infrajrs->id_enqt = $request->id_enqt;
        $etat_infrajrs->etat_garde_avue = $request->etat_garde_avue;
        $etat_infrajrs->id_gravue = $request->id_gravue;
        $etat_infrajrs->etat_non_enqte = $request->etat_non_enqte;
        $etat_infrajrs->id_neqte = $request->id_neqte;
        $etat_infrajrs->etat_satisfaction = $request->etat_satisfaction;
        $etat_infrajrs->id_satisfact = $request->id_satisfact;
        $etat_infrajrs->etat_decision_final = $request->etat_decision_final;
        $etat_infrajrs->id_decision = $request->id_decision;
        $etat_infrajrs->etat_source_judiciaire = $request->etat_source_judiciaire;
        $etat_infrajrs->id_judiciaire = $request->id_judiciaire;
        $etat_infrajrs->etat_source_osc = $request->etat_source_osc;
        $etat_infrajrs->id_osc = $request->id_osc;
        $etat_infrajrs->etat_media_source = $request->etat_media_source;
        $etat_infrajrs->id_media = $request->id_media;

        $etat_infrajrs->id_tinfra = $request->id_tinfra;
        $etat_infrajrs->id_victime = $request->id_victime;
        $etat_infrajrs->id_age = $request->id_age;
        $etat_infrajrs->id_province = $request->id_province;
        $etat_infrajrs->id_lieu_infra = $request->id_lieu_infra;

         if ($etat_infrajrs->save())
             return response()->json( $etat_infrajrs->toArray() , 201);
         else
             return response()->json([
                 'success' => false,
                 'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
             ], 500);
    }

    public function updateEtatInfraJrs(Request $request, $id) {
        try {
            $etat_infrajrdb = EtatInfraJour::where('id', $id)->first();
            $etat_infra = array();
            $etat_infra['date_infra'] = is_null($request->date_infra) ? $etat_infrajrdb->date_infra : $request->date_infra;
            $etat_infra['id_infra'] = is_null($request->id_infra) ? $etat_infrajrdb->id_infra : $request->id_infra;
            $etat_infra['etat_denonciation'] = is_null($request->etat_denonciation) ? $etat_infrajrdb->etat_denonciation : $request->etat_denonciation;
            $etat_infra['id_autho'] = is_null($request->id_autho) ? $etat_infrajrdb->id_autho : $request->id_autho;
            $etat_infra['etat_enquette'] = is_null($request->etat_enquette) ? $etat_infrajrdb->etat_enquette : $request->etat_enquette;
            $etat_infra['id_enqt'] = is_null($request->id_enqt) ? $etat_infrajrdb->id_enqt : $request->id_enqt;
            $etat_infra['etat_garde_avue'] = is_null($request->etat_garde_avue) ? $etat_infrajrdb->etat_garde_avue : $request->etat_garde_avue;
            $etat_infra['id_gravue'] = is_null($request->id_gravue) ? $etat_infrajrdb->id_gravue : $request->id_gravue;
            $etat_infra['etat_non_enqte'] = is_null($request->etat_non_enqte) ? $etat_infrajrdb->etat_non_enqte : $request->etat_non_enqte;
            $etat_infra['id_neqte'] = is_null($request->id_neqte) ? $etat_infrajrdb->id_neqte : $request->id_neqte;
            $etat_infra['etat_satisfaction'] = is_null($request->etat_satisfaction) ? $etat_infrajrdb->etat_satisfaction : $request->etat_satisfaction;
            $etat_infra['id_satisfact'] = is_null($request->id_satisfact) ? $etat_infrajrdb->id_satisfact : $request->id_satisfact;
            $etat_infra['etat_decision_final'] = is_null($request->etat_decision_final) ? $etat_infrajrdb->etat_decision_final : $request->etat_decision_final;
            $etat_infra['id_decision'] = is_null($request->id_decision) ? $etat_infrajrdb->id_decision : $request->id_decision;
            $etat_infra['etat_source_judiciaire'] = is_null($request->etat_source_judiciaire) ? $etat_infrajrdb->etat_source_judiciaire : $request->etat_source_judiciaire;
            $etat_infra['id_judiciaire'] = is_null($request->id_judiciaire) ? $etat_infrajrdb->id_judiciaire : $request->id_judiciaire;
            $etat_infra['etat_source_osc'] = is_null($request->etat_source_osc) ? $etat_infrajrdb->etat_source_osc : $request->etat_source_osc;
            $etat_infra['id_osc'] = is_null($request->id_osc) ? $etat_infrajrdb->id_osc : $request->id_osc;
            $etat_infra['etat_media_source'] = is_null($request->etat_media_source) ? $etat_infrajrdb->etat_media_source : $request->etat_media_source;
            $etat_infra['id_media'] = is_null($request->id_media) ? $etat_infrajrdb->id_media : $request->id_media;
            $etat_infra['id_tinfra'] = is_null($request->id_tinfra) ? $etat_infrajrdb->id_tinfra : $request->id_tinfra;
            $etat_infra['id_victime'] = is_null($request->id_victime) ? $etat_infrajrdb->id_victime : $request->id_victime;
            $etat_infra['id_age'] = is_null($request->id_age) ? $etat_infrajrdb->id_age : $request->id_age;
            $etat_infra['id_province'] = is_null($request->id_province) ? $etat_infrajrdb->id_province : $request->id_province;
            $etat_infra['date_infra'] = is_null($request->date_infra) ? $etat_infrajrdb->date_infra : $request->date_infra;
            $etat_infra['id_infra'] = is_null($request->id_infra) ? $etat_infrajrdb->id_infra : $request->id_infra;
            $etat_infra['etat_denonciation'] = is_null($request->etat_denonciation) ? $etat_infrajrdb->etat_denonciation : $request->etat_denonciation;
            $etat_infra['id_autho'] = is_null($request->id_autho) ? $etat_infrajrdb->id_autho : $request->id_autho;
            $etat_infra['etat_enquette'] = is_null($request->etat_enquette) ? $etat_infrajrdb->etat_enquette : $request->etat_enquette;
            $etat_infra['id_enqt'] = is_null($request->id_enqt) ? $etat_infrajrdb->id_enqt : $request->id_enqt;
            $etat_infra['etat_garde_avue'] = is_null($request->etat_garde_avue) ? $etat_infrajrdb->etat_garde_avue : $request->etat_garde_avue;
            $etat_infra['id_gravue'] = is_null($request->id_gravue) ? $etat_infrajrdb->id_gravue : $request->id_gravue;
            $etat_infra['etat_non_enqte'] = is_null($request->etat_non_enqte) ? $etat_infrajrdb->etat_non_enqte : $request->etat_non_enqte;
            $etat_infra['id_neqte'] = is_null($request->id_neqte) ? $etat_infrajrdb->id_neqte : $request->id_neqte;
            $etat_infra['etat_satisfaction'] = is_null($request->etat_satisfaction) ? $etat_infrajrdb->etat_satisfaction : $request->etat_satisfaction;
            $etat_infra['id_satisfact'] = is_null($request->id_satisfact) ? $etat_infrajrdb->id_satisfact : $request->id_satisfact;
            $etat_infra['etat_decision_final'] = is_null($request->etat_decision_final) ? $etat_infrajrdb->etat_decision_final : $request->etat_decision_final;
            $etat_infra['id_decision'] = is_null($request->id_decision) ? $etat_infrajrdb->id_decision : $request->id_decision;
            $etat_infra['etat_source_judiciaire'] = is_null($request->etat_source_judiciaire) ? $etat_infrajrdb->etat_source_judiciaire : $request->etat_source_judiciaire;
            $etat_infra['id_judiciaire'] = is_null($request->id_judiciaire) ? $etat_infrajrdb->id_judiciaire : $request->id_judiciaire;
            $etat_infra['etat_source_osc'] = is_null($request->etat_source_osc) ? $etat_infrajrdb->etat_source_osc : $request->etat_source_osc;
            $etat_infra['id_osc'] = is_null($request->id_osc) ? $etat_infrajrdb->id_osc : $request->id_osc;
            $etat_infra['etat_media_source'] = is_null($request->etat_media_source) ? $etat_infrajrdb->etat_media_source : $request->etat_media_source;
            $etat_infra['id_media'] = is_null($request->id_media) ? $etat_infrajrdb->id_media : $request->id_media;
            $etat_infra['id_tinfra'] = is_null($request->id_tinfra) ? $etat_infrajrdb->id_tinfra : $request->id_tinfra;
            $etat_infra['id_victime'] = is_null($request->id_victime) ? $etat_infrajrdb->id_victime : $request->id_victime;
            $etat_infra['id_age'] = is_null($request->id_age) ? $etat_infrajrdb->id_age : $request->id_age;
            $etat_infra['id_province'] = is_null($request->id_province) ? $etat_infrajrdb->id_province : $request->id_province;
            $etat_infra['id_lieu_infra'] = is_null($request->id_lieu_infra) ? $etat_infrajrdb->id_lieu_infra : $request->id_lieu_infra;

            $etat_infra['admin_id'] = $request->admin_id;
            $etat_infra['super_id'] = $request->super_id;

            $updated = DB::table('etat_infra_jours')->where('id', $id)->update($etat_infra);
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


    public function deleteEtatInfraJrs($id) {
        try {
            $tinfra = EtatInfraJour::where('id', $id)->get();
            if($tinfra) {
                EtatInfraJour::where('id', $id)->delete();
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

