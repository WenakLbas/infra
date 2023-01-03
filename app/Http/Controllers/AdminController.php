<?php

namespace App\Http\Controllers;


use DB;
use File;
use JWTAuth;
use App\Models\Url;
use App\Models\Admin;
use App\Models\Url_Path;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->admin = new Admin;
    }

    public function getAuthenticatedAdmin()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (! $user) {
                return response()->json(['Cet Utilisateur n\'existe pas...'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(
            $user
            , 200);
    }

    public function getAdmin($id) {
        if (Admin::where('id', $id)->exists()) {
            $user = DB::table('admins')
                ->where('admins.id', $id)
                ->get();
            return response()->json( $user[0], 200);
        } else {
            return response()->json([
                "message" => "Cet Utilisateur n'est inscrit...!"
            ], 404);
        }
    }

    public function admins()
    {
        $user = DB::table('admins')->get()->all();
        return response()->json($user, 200);
    }

    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        config()->set( 'auth.defaults.guard', 'admins' );
        \Config::set('jwt.user', 'App\Models\Admin');
        \Config::set('auth.providers.users.model', \Admin::class);
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Le Nom d\'Utilisateur ou le Mot Passe est Icorrecte...!'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // return response()->json(compact('token'));
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
            'success' => '1'
        ]);
    }


    public function imgName($path){
        if($path){
            $name = explode('\\', $path);
            $length = count($name);
            return $name[$length-1];
        }
    }

    public function upload($photo, $path){
        if($photo){
            $name = time().'_'.$this->imgName($path);
            \Image::make($photo)->save(public_path('images/users/').$name);
            return $name;
        }else
        {
            null;
        }
    }

    public function adminRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|max:2',
            'super_id' => 'required',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:25',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:4|confirmed'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $fileName = $this->upload($request->strfile, $request->user_profile);
        $user = new Admin();
        $user->name= $request->name;
        $user->gender= $request->gender;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->role_id = $request->role_id;
        $user->super_id = $request->super_id;
        $user->password = Hash::make($request->password);

        if($request->user_profile == null ){
            $user->user_profile = Url::ALL_PROFILE;
        }
        if ($user->save())
            return response()->json([
                'success' => true,
                'message' => 'L\' Utilisateur a ete creer avec succes...!'
            ], 201);
        else
            return response()->json([
                'success' => '0',
                'message' => 'Cet enregistrement a echoué...!'
            ], 500);

    }

    public function updateAdmin(Request $request) {
        try {
            $usr = Admin::where('id', $request->id)->first();
            $user =  array();
            if($request->hasFile('image')){
                $doc = $request->file('image');
                $fileName = $request->file('image')->getClientOriginalName();
                $fileNameOnly = pathinfo($fileName, PATHINFO_FILENAME);
                $fileExtension = $request->file('image')->getClientOriginalExtension();
                $input['fileName'] = str_replace(' ', '_', $fileNameOnly).'-'.rand().'_'.time().'.'.$fileExtension;
                $dest = public_path('/images/users/');
                $doc->move($dest, $input['fileName']);
                // dd($input['fileName']);
                $user['user_profile'] = Url::URL_USERS.$input['fileName'];
                $user['super_id'] =  is_null($request->super_id) ? $usr->super_id : $request->super_id;

                $user['name'] = is_null($request->name) ? $usr->name : $request->name;
                $user['gender'] = is_null($request->gender) ? $usr->gender : $request->gender;
                $user['phone'] = is_null($request->phone) ? $usr->phone : $request->phone;
                $user['email'] = is_null($request->email) ? $usr->email : $request->email;
                $user['email'] = is_null($request->email) ? $usr->email : $request->email;
                $user['role_id'] = is_null($request->role_id) ? $usr->role_id : $request->role_id;
                $user['status'] = is_null($request->status) ? $usr->role_id : $request->status;

                $this->deleteImage($usr->user_profile);
                if ($user['user_profile'] == null){
                    $user['user_profile'] = $usr->user_profile;
                }
                $updated = DB::table('admins')->where('id', $request->id)->update($user);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "Votre Profile a ete mis a jour avec successe...!"
                    ], 200);
                }
            }else{

                $user['super_id'] =  is_null($request->super_id) ? $usr->super_id : $request->super_id;
                $user['name'] = is_null($request->name) ? $usr->name : $request->name;
                $user['gender'] = is_null($request->gender) ? $usr->gender : $request->gender;
                $user['phone'] = is_null($request->phone) ? $usr->phone : $request->phone;
                $user['email'] = is_null($request->email) ? $usr->email : $request->email;
                $user['role_id'] = is_null($request->role_id) ? $usr->role_id : $request->role_id;
                $user['status'] = is_null($request->status) ? $usr->role_id : $request->status;

                $updated = DB::table('admins')->where('id', $request->id)->update($user);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "Votre Profile a ete mis a jour avec successe...!"
                    ], 200);
                }
            }

        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                // "message" => 'Votre Profile ne peut pas etre mis a jour...!'
                "message" => $e->getMessage()
            ], 404);
        }
    }


    public function changePwd(Request $request, $id)
    {
        $this->validate($request, [
            'oldpassword' => 'required',
            'newpassword' => 'required|string|min:6|confirmed',
        ]);
        $hashedPassword = Auth::user()->password;
        if (\Hash::check($request->oldpassword , $hashedPassword )) {
            if (!\Hash::check($request->newpassword , $hashedPassword)) {

                $users = Admin::find(Auth::user()->id);
                $users->password = Hash::make($request->newpassword);
                Admin::where( 'id' , Auth::user()->id)->update( array( 'password' =>  $users->password));

                return response()->json([
                    'message' => 'Le Mot de passe a ete change avec succes...'
                ], 200);
            }
            else{
                return response()->json([
                    'message' => 'Le mot de passe ne peut pas être changer !!!'
                ], 304);
            }
        }
        else{
            return response()->json([
                'message' => 'Le nouveau mot de passe et l\'ancien mot de passe ne se correspondent pas !!!'
            ], 304);
        }
    }

    public function dbImgName($path){
        if($path !== Url_Path::URL."users/"){
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
        $image_path = 'images/users/'.$imageName;
        if(File::exists($image_path))
        {
            File::delete($image_path);
        }
    }


    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function deleteAdmin ($id) {
        try {
            $admin = Admin::where('id', $id)->get();
            if($admin) {
                $this->deleteImage($admin[0]->user_profile);
                Admin::where('id', $id)->delete();
                return response()->json([
                    "message" => 'Cet Administrateur est supprimé définitivement'
                ], 202);
            } else {
                return response()->json([
                    "message" => 'Cet Administrateur n\'existe pas'
                    // "message" => $team
                ], 404);
            }
        }catch (\Exception $e){
            return response()->json([
                // "message" => 'Cet Administrateur n\'existe pas'
                'message' => $e->getMessage()
                // "message" => $team
            ], 404);
        }
    }
}
