<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\Request;

class AuthUserController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
              'name' => 'required|regex:/^[a-zA-Z ]*$/|max:255',
              'email' => 'required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/|max:255|unique:users',
              'password' => 'required|string|min:6'
           
         ]);
       if ($validator->fails())
       {
        return response()->json([
          'errors'=>$validator->errors()->all(),
          'status'=>402,
          'message'=>"Something went wrong",
         ]);
       }else{
            $users = new User;
            $users->name=$request->name;
            $users->email=$request->email;
            $users->password=Hash::make($request['password']);
            $users->status="0";
            $users->assignRole($request['role']);
           if($users->save()){
                return response()->json([
                    'status'=>200,
                    'users'=>$users,
                    'message'=>"Registered Successfully",
                 ]);
           }else{
            return response()->json([
                   'status'=>401,
                   'message'=>"Something went wrong",
             ]);
          }
        }
       }


      public function login(Request $userdata){
        try {
          if($userdata){
             $validator = Validator::make($userdata->all(), [
                 'email' => 'required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/|email|max:255',
                 'password' => 'required|string|min:6',
                 ]);
                  if($validator->fails())
                  {
                      return response()->json([
                        'status'=>400,
                        'errors'=>$validator->errors()->all()
                      ]);
                  }else{
                   $data=[
                      'email'=>$userdata['email'],
                      'password'=>$userdata['password']
                       ];
                       $user=User::where('email',$userdata['email'])->first();
                       if($user){
                        if($user->status == "0"){
                            return response()->json([
                                "status"=>405,
                                "message"=>"Please wait while admin accept your approval!"
                            ]);
                          }
                          if(auth()->attempt($data)){
                              $roles=$user->roles()->pluck('name');
                              $permission=[];
                              $roledata=$user->roles()->get();
                              if($roledata){
                                  foreach($roledata as $role){
                                       $permission[] = $role->permissions()->pluck('name');
                                    }
                                }
                              $token= auth()->user()->createToken('Token')->accessToken;
                           return response()->json([
                                 "status"=>200,
                                 "token"=>$token,
                                 "user"=>$user,
                                 "roles"=> $roles,
                                 "permissions"=> $permission,
                                 "message"=>"login sucessful"
                          ]);
                     }else{
                         return response()->json([
                                 "status"=>401,
                                 "message"=>"Invalid Credentials"
                          ]);
                       }
                    }else{
                        return response()->json([
                            "status"=>402,
                            "message"=>"User Does Not Exist"
                     ]); 
                    }
              }
          }else{
               return response()->json([
                   "status"=>401,
                   "message"=>"Please Fill the required fields"
                  ]);
          }
        } catch (\Exception $e) {
            return response()->json([
              'message'=>$e->getMessage()
            ]);
          }
      }
      public function logout(Request $request){
                   $token = auth()->user()->token();
                   if($token){
                   $token->revoke();
                   $response = ['message' => 'You have been successfully logged out!'];
                   return response($response, 200);
                   }else{
                    $response = ['message' => 'Somthing went wrong'];
                    return response($response, 401);
                  }
        }


    public function forgetPassword(){
        try {
                $request = json_decode(file_get_contents('php://input'), true);
                $validator = Validator::make($request, [
                    'email' => 'required|regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/|email|max:255',
                 ]);
            if ($validator->fails())
                {
                  return response()->json([
                      'status'=>400,
                      'errors'=>$validator->errors()->all(),
                   ]);
                 }
                $user=User::where('email',$request['email'])->first();
            if($user){
                 $token=Str::random(90);
                 $url='http://192.168.50.38:3000/authentication/reset?token='.$token;
                 $data['url']=$url;
                 $data['body']="Please click on the below link to reset your Password.";
                 $updateApiToken=User::find($user->id);
                 $updateApiToken->api_token= $token;
                 $updateApiToken->save();
                 $data['name']=$updateApiToken->name;
                 Mail::to($request['email'])->send(new \App\Mail\PasswordMail($data));
                 return response()->json([
                  'status'=>200,
                  'id'=> $user->id,
                  'token'=> $updateApiToken->api_token,
                  'message'=>'Please check your email to reset your password.'
                 ]);
             }else{
              return response()->json([
                'status'=>401,
                'message'=>"User Does Not Exist."
              ]);
             }
            } catch (\Exception $e) {
              return response()->json([
                'message'=>$e->getMessage()
              ]);
            }
          }
      
          public function resetPassword(){
            $request = json_decode(file_get_contents('php://input'), true);
            try {
              $validator = Validator::make($request, [
                'npassword' => 'required|string|min:6',
                'ncpassword' => 'required|same:npassword',
                'reset_token'=>'required|min:90|max:90',
                // 'id'=>'required'
             ]);
            if ($validator->fails())
            {
              return response()->json([
                'errors'=>$validator->errors()->all(),
                'status'=>400,
                'message'=>"Somthing went wrong",
               ]);
             }
              
            $user=User::where('api_token',$request['reset_token'])->first();

            if(isset($user)){
                        if(Hash::check($request['ncpassword'], $user->password)){
                            return response()->json([
                                'status'=>403,
                                'message'=>"Password cannot be same as old password."
                               ]);
                         }
                         $user->password=Hash::make($request['ncpassword']);
                         $user->api_token=null;

                        if($user->save())  {
                            return response()->json([
                             'status'=>200,
                             'message'=>"Your Password has been updated successfully."
                            ]);
                        }else{
                           return response()->json([
                             'status'=>401,
                             'message'=>"User Does Not Exist."
                            ]);
                       }
             }else{
                 return response()->json([
                      'status'=>401,
                      'message'=>"You cannot use the same link to reset your password again."
                  ]);
                 
             }
          
             } catch (\Exception $e) {
               return response()->json([
                 'message'=>$e->getMessage()
               ]);
             }
          }

          
}
