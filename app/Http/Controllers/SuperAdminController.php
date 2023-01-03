<?php

namespace App\Http\Controllers;

use DB;
use File;
use JWTAuth;
use App\Models\Url;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->user = new SuperAdmin;
    }

    public function getAuthenticatedUser()
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

    public function getUser($id) {
        if (SuperAdmin::where('id', $id)->exists()) {
            $user = DB::table('users')
                ->where('users.id', $id)
                ->get();
            return response()->json($user, 200);
        } else {
            return response()->json([
                "message" => "Cet Utilisateur n'est inscrit...!"
            ], 404);
        }
    }

    public function users()
    {
        $user = DB::table('users')->get()->all();
        return response()->json($user, 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        config()->set( 'auth.defaults.guard', 'superadmins');
        \Config::set('jwt.user', 'App\Models\SuperAdmin');
        \Config::set('auth.providers.users.model', \SuperAdmin::class);
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'error' => 'Le Nom d\'Utilisateur ou le Mot Passe est Icorrecte...!',
                    'success' => '0',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
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
            $name = rand().'_'.$this->imgName($path);
            \Image::make($photo)->save(public_path('images/users/').$name);
            return $name;
        }else
        {
            null;
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|max:2',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:25',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:4|confirmed',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $fileName = $this->upload($request->strfile, $request->user_profile);
        $user = new SuperAdmin();
        $user->name= $request->name;
        $user->gender= $request->gender;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->user_profile = $request->user_profile;
        $user->role_id = $request->role_id;
        $user->password = Hash::make($request->password);
        if($user->user_profile == null){
            $user->user_profile = Url::ALL_PROFILE;
        }else{
            $user->user_profile = Url::URL_USERS.$fileName;
        }
        if ($user->save())
            return response()->json([
                'success' => true,
                'message' => "L'Utilisateur a ete enregistrer avec successe."
            ], 201);
        else
            return response()->json([
                'success' => false,
                'message' => "L'Utilisateur n'a pas ete enregistrer"
            ], 500);
    }


    public function updateUser(Request $request, $id) {
        try {
            $usr = SuperAdmin::where('id', $id)->first();
            if ($usr) {
                $user = array();
                $user['name'] = is_null($request->name) ? $usr->name : $request->name;
                $user['phone'] = is_null($request->phone) ? $usr->phone : $request->phone;
                $user['email'] = is_null($request->email) ? $usr->email : $request->email;
                $user['user_profile'] = $request->user_profile;
                if($user['user_profile'] !== null){
                    $fileName = $this->upload($request->strfile, $request->user_profile);
                    $user['user_profile'] = Url::URL_USERS.$fileName;
                    $this->deleteImage($usr->user_profile);
                }
                if ($user['user_profile'] == null){
                    $user['user_profile'] = $usr->user_profile;
                }
                $updated = DB::table('users')->where('id', $id)->update($user);
                if ($updated){
                    return response()->json([
                        'status' => true,
                        'message' => "L'Article a ete mis a jour avec successe...!"
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


    public function changePwd(Request $request)
    {
        $this->validate($request, [
            'oldpassword' => 'required',
            'newpassword' => 'required|string|min:4|confirmed',
        ]);
        $hashedPassword = Auth::user()->password;
        if (\Hash::check($request->oldpassword , $hashedPassword )) {
            if (!\Hash::check($request->newpassword , $hashedPassword)) {
                $users =SuperAdmin::find(Auth::user()->id);
                $users->password = Hash::make($request->newpassword);
                SuperAdmin::where( 'id' , Auth::user()->id)->update( array( 'password' =>  $users->password));

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


    public function deactiveAccounte($id)
    {
        try {
            $usr = SuperAdmin::where('id', $id)->first();
            //$me = auth()->user()->find($request->id);
            if ($usr) {
                $user = array();
                $user['status'] = 'desactive';
                $user['password'] = Hash::make('desactivedUserkellou123**');
                $user['pass_disact'] = $usr->password;
                $updated = DB::table('users')->where('id', $id)->update($user);
                if ($updated){
                    return response()->json([
                        'success' => true,
                        // 'success' => $request->id,
                        'message' => "Ce compte a ete deasctive avec successe...!"
                    ], 200);
                }
            }
        }catch (\Exception $e){
            return response()->json([
                "message" => $e->getMessage()
                //"message" => 'Cet enregistrement n\'existe pas'
            ], 404);
        }
    }

    public function activeAccounte($id)
    {
        try {
            $usr = SuperAdmin::where('id', $id)->first();
            //$me = auth()->user()->find($request->id);
            if ($usr) {
                $user = array();
                $user['status'] = 'active';
                $user['password'] = $usr->pass_disact;
                $user['pass_disact'] = '(NULL)';
                $updated = DB::table('users')->where('id', $id)->update($user);
                if ($updated){
                    return response()->json([
                        'success' => true,
                        // 'success' => $request->id,
                        'message' => "Ce compte a ete active avec successe...!"
                    ], 200);
                }
            }
        }catch (\Exception $e){
            return response()->json([
                "message" => $e->getMessage()
                //"message" => 'Cet enregistrement n\'existe pas'
            ], 404);
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

    public function deleteUser ($id) {
        try {
            $user = SuperAdmin::where('id', $id)->get();
            if($user) {
                $this->deleteImage($user[0]->user_profile);
                User::where('id', $id)->delete();
                return response()->json([
                    "message" => 'Cet Utilisateur est supprimé définitivement'
                ], 202);
            } else {
                return response()->json([
                    "message" => 'Cet Utilisateur n\'existe pas'
                    // "message" => $blog
                ], 404);
            }
        }catch (\Exception $e){
            return response()->json([
                "message" => 'Cet Utilisateur n\'existe pas'
                // 'message' => $e->getMessage()
                // "message" => $blog
            ], 404);
        }
    }
}
