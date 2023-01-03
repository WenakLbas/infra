<?php

namespace App\Http\Controllers;

use DB;
use App\Models\OSC;
use Illuminate\Http\Request;

class OSCController extends Controller
{
    public function typeOSCs()
    {
        $oscs = DB::table('oscs')
            ->get();
        return response()->json($oscs, 200);
    }

    public function findOSC($id)
    {
        try{
            $osc = DB::table('oscs')
                ->join('admins','oscs.admin_id','=','admins.id')
                ->select('admins.name', 'admins.phone',  'admins.email',  'admins.user_profile', 'oscs.*')
                ->where(['oscs.id' => $id])
                ->get();
            if (!$osc) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet enregistrement n\'existe pas'
                ], 400);
            }else{
                return response()->json( $osc[0], 200);
            }
        }catch (\Exception $exception){
            return response()->json($exception, 400);
        }
    }

    public function storeOSC(Request $request)
    {
        $this->validate($request, [
            'nom' => 'required',
            'description' => 'required',
            'date' => 'required',
        ]);

        $osc = new OSC();
        $osc->admin_id = $request->admin_id;
        $osc->super_id = $request->super_id;
        $osc->nom = $request->nom;
        $osc->description = $request->description;
        $osc->date = $request->date;

        if ($osc->save())
            return response()->json([
                'success' => true,
                'text' => $osc->toArray()
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => 'Cet enregistrement ne peut pas etre enregistrer!'
            ], 500);
    }

    public function updateOSC(Request $request, $id) {
        try {
            $oscd = OSC::where('id', $id)->first();
            $osc = array();
            $osc['nom'] = is_null($request->nom) ? $oscd->nom : $request->nom;
            $osc['description'] = is_null($request->description) ? $oscd->description : $request->description;
            $osc['date'] = is_null($request->date) ? $oscd->date : $request->date;
            $osc['admin_id'] = $request->admin_id;
            $osc['super_id'] = $request->super_id;
            $updated = DB::table('oscs')->where('id', $id)->update($osc);
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


    public function deleteOSC($id) {
        try {
            $osc = OSC::where('id', $id)->get();
            if($osc) {
                OSC::where('id', $id)->delete();
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

