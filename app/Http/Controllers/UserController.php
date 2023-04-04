<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Http\Controllers\Controller;

use Validator;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
class UserController extends Controller
{
    public function register(Request $request){
        
        $this->validate($request,[
       
            'firstname'=>'required',
            'lastname'=>'required|string|min:2|max:100',
            'email'=>'required|string|email',
            'password'=>'required|String',
            'address'=>'required|string',
            'phone'=>'required|string',
            //'role'=>'required|string',
            'role' => 'required|in:client,provider,admin'
 
        ]);

       $user = User::create([
         'firstname'=>$request->firstname,
         'lastname'=>$request->lastname,
         'email'=>$request->email,
         'password'=>Hash::make($request->password),
         'address'=>$request->address,
         'phone'=>$request->phone,
         'role'=>$request->role

          
       ]);
       $user->save();
       
       $token = JWTAuth::fromUser($user);

       return response()->json([
           'success' => true,
           'user' => $user,
           'token' => $token
       ], 201);
      

    
      
    }

    //login api method call
    public function login(Request $request){

        $validator= Validator::make( $request->all(),[
            'email'=>'required|string|email',
            'password'=>'required|string|min:6',
            
         ]);
         if($validator->fails())
         {
             return response()->json($validator->errors());
         }
         if(!$token=auth()->setTTL(120)->attempt($validator->validated()))
         {
             return response()->json(['success'=>false,'msg'=>'Username & Password is incorrect']);
         }
          return  $this->respondWithToken($token);
        }
    protected function respondWithToken($token)
    {
        return response()->json([
            'success'=>True,
            'access_token'=>$token,
            'role'=>auth()->user()->role,
            'token_type'=>'Bearer',
            'expires_in'=>auth()->factory()->getTTL()*60,
            'user' =>auth()->user(),
            'token' => $token,
            
           
        ])
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        ;
            
    }
    
    
    //logout Api method
    public function logout(){
        try{
            auth()->logout();
            return response()->json([
                'success'=>'true',
                'msg'=>'User logged out !'
            ]);

        }catch(\Exception $e){
            return response()->json([
                'success'=>'false',
                'msg'=>$e->getMessage()
            ]);
        }
     
    }
    //create Profile Api 
    public function profile(){
        $user = auth()->user();// récupérer l'utilisateur actuellement authentifié
      try{
        return response()->json(['success'=>true,'data'=>$user]);//retourne ensuite les informations de l'utilisateur dans une réponse JSON avec un indicateur de succès.
      }
      catch (\Exception $e){
         return response()->json([
            'success'=>false,
            'msg'=>$e->getMessage()
         ]);
      }
}
 //Profile update and check user is authanticated or not
 public function updateProfile(Request $request)
{
    // Vérifier si l'utilisateur est authentifié
    if (auth()->check()) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'firstname' => 'required|string',
            'lastname' => 'required|string|min:2|max:100',
            'password' => 'nullable|string|min:6',
            'address' => 'required|string',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = auth()->user();
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès',
            'user' => $user
        ], 200);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Utilisateur non authentifié'
        ], 401);
    }
}
 /*
 //Ce code vérifie si l'utilisateur est authentifié en utilisant auth()->user(). 
 Si l'utilisateur n'est pas authentifié, il renvoie une réponse JSON avec un message d'erreur.

Ensuite, le code valide les données de la requête en utilisant le validateur de Laravel et en spécifiant les règles 
de validation pour chaque champ. Si la validation échoue, il renvoie une réponse JSON avec les erreurs de validation.

Si la validation réussit, le code met à jour le profil de l'utilisateur en utilisant les données de la requête et enregistre les modifications dans la base de données. Enfin, il renvoie une réponse JSON avec un message de réussite et les données de l'utilisateur mises à jour.
        /*
    //create email verification Api
    //use SMTP for send email
    //Routes setup for email verfication 
    public function  sendVerifyMail($email){
      if(auth()->user()){
         $user=User::Where('email',$email)->get();
         if(count($user)>0){
            $random = Str::random(40);
            $domain =URL::to('/');
            $url=$domain.'/'.$random;
            $data['url']=$url;
            $data['email']=$email;
            $data['title']="Email Verification";
            $data['body'] ="Please click here to below to  verify your mail";
            Mail::send('verifyMail',['data'=>$data],function($message)use($data)
            {
                $message->to($data['email'])->subject($data['title']);
            });
           
          $user= User::find($user[0]['id']);
           $user->remember_token=$random;
           $user->save();
           
       return response()->json(['success'=>true,'msg'=>'Mail sent successfuly.']);
         
         }
      }else{
        return response()->json(['success'=>false,'msg'=>'User is not found']);
      }

    }
    public function verificationMail($token)
    {
        $user= User::Where('remember_token',$token)->get();
        if(count($user)>0){
        $datetime=Carbon::now()->format('Y-m-d H:i:s');
        $user=User::find($user[0]['id']);
        $user->remember_token='';
        $user->is_verified=1;
        $user->email_verified_at= $datetime;
        $user->save();
        
        return "<h1>Email verified  successfully";
        }
        else
        {
            return "not Found";
        }

    }
    //create API for refresh token 
    //Note:-if new token generate the old token should not work 
    public function refresh()
    {
        if(auth()->user()){
          return $this->respondWithToken(auth()->refresh());
        }else{
            return response()->json(['success'=>false,'msg'=>'User is not Authenticated']);
        }
    }
    //forget password api method
    public function forgetPassword(Request $request)
    {
        try{
           $user= User::where('email',$request->email)->get();
            if(count($user)>0){
               $token=Str::random(40);
               $domain=URL::to('/');
               $url=$domain.'/rest-password?token='.$token;
               $data['url']=$url;
               $data['email']=$request->email;
               $data['title']="Password Reset";
               $data['body']="Please click on below link to rest your password.";
                Mail::send('forgetPasswordMail',['data'=>$data],function($message) use ($data){
                     $message->to($data['email'])->subject($data['title']);
                });//put the url of frontinterface
                $datetime=Carbon::now()->format('Y-m-d H:i:s');
                PasswordRest::updateDrCreate(
                    ['email'=>$request->email],
                    [
                        'email'=>$request->email,
                        'token'=>$token,
                        'created_at'=>$datetime
                    ]
                    );
           return response()->json(['success'=>true,'msg'=>'Please check your mail to reset your password.']);

            }else{
           return response()->json(['success'=>false,'msg'=>'User not found!']);
            }
        } catch(\Exception $e) {
            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);
        }
    }
//reset password  view load
public function resetPasswordLoad(Request $request)
{
    $restData=PasswordRest::where('token',$request->token)->get();
    if(isset($request->token) && count($restData) >0){
         $user=User::where('email',$restData[0]['email']->get());
         return view('resetPassword',compact('user'));
    }else{
       return view(404);
    }
}
public function resetPassword(Request $request)
{
   $request->validate([
    'password'=>'required|string|min:6|confirmed'
   ]);
   $user=User::find($request->id);
   $user->password=Hash::make($request->password);
   $user->save();
   PasswordRest::Where('email',$user->email)->delete();
   return "<h1> Your password has been reset successfully . </h1>";

}
*/
}
