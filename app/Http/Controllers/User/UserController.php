<?php

namespace App\Http\Controllers\User;

use App\Models\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {

    
            $users = User::all();
            return response()->json([
                'users' => $users,
            ]);
     

    }


    public function addUser(Request $request) {

            $id = $request['request'];

        

            $user = User::find($request['request']);
            return view('Users.add_user', ['user' => $user]);
       

    }


    public function changePassword(Request $request) {

        if($this->canDo("User Change Password")){
            $id = $request['request'];
            $user = User::find($request['request']);
            return view('Users.change_password', ['user' => $user]);
        }else{
            $this->permissionDenied();
        }

    }



    // public function updatePassword(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status'=>401,
    //             'errors'=>$validator->errors()->all()
    //         ]);
    //     } else {
    //         try {
    //             $user = User::find($request['user_id']);
    //             $user->password = Hash::make($request['password']);
    //             if ($user->save()) {
    //                 return response()->json([
    //                     'status'=>200,
    //                     'message'=>'User Password Updated'
    //                 ]);
    //             } else {
    //                 return response()->json([
    //                     'status'=>402,
    //                     'message'=>'Something went wrong'
    //                 ]);
    //             }
    //         } catch (\Exception $ex) {

    //             $controller_name = \URL::current();
    //             $payload = $request->all();
    //             $Severity = env('SEVERITY');
    //             $exception = $ex;
    //             $message = $this->getExceptionMessage($ex, $request);
    //             return response()->json(['message'=> 'Something went wrong ' . json_encode($ex->getMessage())]);
    //         }
    //     }
    // }

    public function updatePassword(Request $request){
        $validator = Validator::make($request->all(), [
        'opassword'=>'required|string',
        'npassword' => 'required|string|min:8',
        'cpassword'=>'required|same:npassword'
        ]);
        if ($validator->fails())
        {
          return response()->json([
            'errors'=>$validator->errors()->all(),
            'status'=>400,
            'message'=>"Somthing went wrong",
           ]);
        }else{
            try {
              $user=User::find(auth()->user()->id);
                if (Hash::check($request->get('opassword'), $user->password)) {
                    // The passwords matches
                   return response()->json([
                         'status'=>401,
                         'message'=>"Your current password does not matches with the password you provided. Please try again.",
                    ]);
                }

                if(strcmp($request->get('cpassword'), $request->get('opassword')) == 0){
                       //Current password and new password are same
                      return response()->json([
                               'status'=>401,
                               'message'=>"New Password cannot be same as your current password. Please choose a different password.",
                     ]);
                } 
                $user->password=Hash::make($request['cpassword']);
                 if( $user->save()){
                         return response()->json([
                               'status'=>200,
                               'message'=>"Your password has been updated successfully",
                   ]);
                }
            } catch (\Exception $ex) {

                $controller_name = \URL::current();
                $payload = $request->all();
                $Severity = env('SEVERITY');
                $exception = $ex;
                $message = $this->getExceptionMessage($ex, $request);
                return response()->json(['message'=> 'Something went wrong ' . json_encode($ex->getMessage())]);
            }
        }
         
     }


    public function updateUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>401,
                'errors'=>$validator->errors()->all()
            ]);
        } else {
            try {
                $user = User::find($request['user_id']);
                $user->name  = $request['name'];
                $user->email = $request['email'];
                $users->assignRole(["user"]);
                if ($user->save()) {
                    return response()->json([
                        'status'=>200,
                        'message'=>'User Updated'
                    ]);
                } else {
                    return response()->json([
                        'status'=>402,
                        'message'=>'Something went wrong'
                    ]);
                }
            } catch (\Exception $ex) {

                $controller_name = \URL::current();
                $payload = $request->all();
                $Severity = env('SEVERITY');
                $exception = $ex;
                $message = $this->getExceptionMessage($ex, $request);

                return response()->json(['message'=> 'Something went wrong ' . json_encode($ex->getMessage())]);
            }
        }
    }

    public function preventingUserName($str){
        $admin = strpos($str,"admin");
        $administrator = strpos($str,"administrator");
        $ril =  strpos($str,"ril");
        $reliance = strpos($str,"reliance") ;
        $adm = strpos($str,"adm") ;
        $apache = strpos($str,"apache")  ;
        $tomcat = strpos($str,"tomcat");
        $superuser = strpos($str,"superuser");
        if( $admin !== false || $administrator !== false || $ril !== false || $adm !== false ||
        $apache !== false || $tomcat !== false || $superuser !== false){
            return 1;
        }
    }

    public function preventingUserPassword($str){
        $admin = strpos($str,"admin");
        $administrator = strpos($str,"administrator");
        $ril =  strpos($str,"ril");
        $reliance = strpos($str,"reliance") ;
        $adm = strpos($str,"adm") ;
        $apache = strpos($str,"apache")  ;
        $tomcat = strpos($str,"tomcat");
        $superuser = strpos($str,"superuser");
        $password = strpos($str,"password");

        if( $admin !== false || $administrator !== false || $ril !== false || $adm !== false ||
        $apache !== false || $tomcat !== false || $superuser !== false || $password !== false ){
            return 1;
        }
  }

    public function insertUser(Request $request) {

    	$strArray = ["admin", "administrator","ril","reliance","adm","apache","tomcat","superuser","password","ril@123","ril123","tomcat"];

    	  $userName = $this->preventingUserName($request->name);
    	  if($userName){
				return response()->json([
                    'status'=>400,
                    'message'=> "You can't enter ".implode(", ", $strArray)." in the name",
                ]);
				
			}

			$userName ?? $userPassword = $this->preventingUserPassword($request->password);

			if($userPassword){
				return response()->json([
                    'status'=>401,
                    'message'=> "You can't enter ".implode(", ", $strArray)." in the password",
                ]);
			}

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:50', 'confirmed' ,
            'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
        ],[
            'password.regex' => 'Your password must be more than 8 characters long, should contain at-least
             1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character. Eg: HelloWorld12@',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>402,
                'errors'=>$validator->errors()->all()
            ]);
            
        } else {
            try {
                $role = User::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => Hash::make($request['password']),
                ]);
                if ($role) {
                    return response()->json([
                        'status'=>200,
                        'message'=>'User Created!'
                    ]);
                } else {
                    return response()->json([
                        'status'=>403,
                        'message'=>'Something went wrong'
                    ]);
                }
            } catch (\Exception $ex) {

                $controller_name = \URL::current();
                $payload = $request->all();
                $Severity = env('SEVERITY');
                $exception = $ex;
                $message = $this->getExceptionMessage($ex, $request);

                return response()->json(['message'=> 'Something went wrong ' . json_encode($ex->getMessage())]);
            }
        }
    }

    public function assignRole(Request $request){
            $id = $request['request'];
            $user = User::find($request['request']);
            $roles = Role::all();
            return view('Users.assign_role',['user' => $user,'roles' => $roles]);
       
    }

    public function updateAssignRole(Request $request){

      
            $user = User::find($request['user_id']);
            $role_arr = array();
            foreach ($request['role'] as $key => $value) {
                $role_arr[$key] = $value;
            }
            $user->assignRole($role_arr);
            return redirect()->back()->with('status', 'success')->with('message', 'User Updated');
       
    }


    public function removeRole(Request $request){
      
            $user = User::find($request['request']);
            $user->removeRole($request['role']);
            return redirect()->back()->with('status', 'success')->with('message', 'Role Removed');
       
    }


    public function assignPermission(Request $request){
       
            $id = $request['request'];
            $role = Role::find($request['request']);
            $permissions = Permission::all();
            return view('Users.assign_permissions',['role' => $role,'permissions' => $permissions]);
        
    }



    public function removePermission(Request $request){
       
            $id = $request['request'];
            $role = Role::find($request['request']);
            $role->revokePermissionTo(['name' =>  $request['permission'] ]);
            return redirect()->back()->with('status', 'success')->with('message', 'Permission Removed');
        
    }



    public function updateAssignPermission(Request $request){
       
            $id = $request['role_id'];
            $role = Role::find($request['role_id']);
            $permission_arr = array();
            foreach ($request['permission'] as $pkey => $pvalue) {
                $permission_arr[$pkey] = $pvalue;
            }
            $role->givePermissionTo($permission_arr);
            return redirect()->back()->with('status', 'success')->with('message', 'Role Updated');
       
    }

    /*---------------------------------------- Start Role ---------------------------------------------------------*/

    public function addRole(Request $request){
       
            $id = $request['request'];
            $role = Role::find($request['request']);
            return view('Users.add',['role' => $role]);
       
    }

    public function getRole(){
       
            $roles = Role::all();
            return response()->json([
                'status'=>200,
                'roles' => $roles
            ]);
       
    }

    public function insertRole(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>401,
                'errors' => $validator->errors()->all()
            ]);
        } else {
            try {
                $role = $request['name'];
                $role = Role::create(['name' => $role]);
                if ($role) {
                    return response()->json([
                        'status'=>200,
                        'message' =>' Role Saved!'
                    ]);
                } else {
                    return response()->json([
                        'status'=>402,
                        'message' =>'Something went wrong'
                    ]);
                }
            } catch (\Exception $ex) {

                $controller_name = \URL::current();
                $payload = $request->all();
                $Severity = env('SEVERITY');
                $exception = $ex;
                $message = $this->getExceptionMessage($ex, $request);
                return response()->json(['message'=>'Something went wrong ' . json_encode($ex->getMessage())]);
            }
        }
    }



    public function insertAdminRole(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        } else {
            try {
                $role = $request['name'];
                $role = Role::create(['name' => $role]);
                if ($role) {
                    return redirect()->back()->with('status', 'success')->with('message', 'Role Saved');
                } else {
                    return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
                }
            } catch (\Exception $ex) {

                $controller_name = \URL::current();
                $payload = $request->all();
                $Severity = env('SEVERITY');
                $exception = $ex;
                $message = $this->getExceptionMessage($ex, $request);
                $this->storeLogs($controller_name, $payload, $Severity, $exception, $message);

                return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ' . json_encode($ex->getMessage()));
            }
        }
    }





    public function deleteRole(Request $request) {
        
            $validator = Validator::make($request->all(), [
                        'request' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'=>401,
                    'errors' => $validator->errors()->all()
                ]);
            } else {
                try {
                    $role = Role::find($request['request']);
                    if ($role) {
                        $role->delete();
                        return response()->json([
                            'status'=>200,
                            'message' =>' Role Deleted!'
                        ]);
                    }
                    return response()->json([
                        'status'=>200,
                        'message' =>'Something went wrong!'
                    ]);
                } catch (\Exception $ex) {

                    $controller_name = \URL::current();
                    $payload = $request->all();
                    $Severity = env('SEVERITY');
                    $exception = $ex;
                    $message = $this->getExceptionMessage($ex, $request);
                    return response()->json(['message'=>'Something went wrong ' . json_encode($ex->getMessage())]);
                }
            }
        
    }

    public function updateRole(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>401,
                'errors' => $validator->errors()->all()
            ]);
        } else {
            try {
                $role = Role::find($request['role_id']);
                $role->name = $request['name'];
                if ($role->save()) {
                    return response()->json([
                        'status'=>200,
                        'message' =>'Role Updated!'
                    ]);
                } else {
                    return response()->json([
                        'status'=>402,
                        'message' =>' Something went wrong '
                    ]);
                }
            } catch (\Exception $ex) {

                $controller_name = \URL::current();
                $payload = $request->all();
                $Severity = env('SEVERITY');
                $exception = $ex;
                $message = $this->getExceptionMessage($ex, $request);
                return response()->json(['message'=>'Something went wrong ' . json_encode($ex->getMessage())]);
            }
        }
    }

    /*---------------------------------------- End Role ---------------------------------------------------------*/



/*----------------------------------------Start Permission ---------------------------------------------------------*/


public function addPermission(Request $request){
   
        $id = $request['request'];
        $permission = Permission::find($request['request']);
        return view('Users.add_permission',['permission' => $permission]);
   
}

public function getPermission(){
   
        $permissions = Permission::all();
        return response()->json([
            'status'=>401,
            'permissions' => $permissions
        ]);

}

public function insertPermission(Request $request) {
    $validator = Validator::make($request->all(), [
                'name' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'status'=>401,
            'errors' => $validator->errors()->all()
        ]);
    } else {
        try {
            $permission = $request['name'];
            $permission = Permission::create(['name' => $permission]);
            if ($permission) {
                return response()->json([
                    'status'=>200,
                    'message' =>'Permission Saved!'
                ]);
            } else {
                return response()->json([
                    'status'=>402,
                    'message' =>' Something went wrong '
                ]);
            }
        } catch (\Exception $ex) {

            $controller_name = \URL::current();
            $payload = $request->all();
            $Severity = env('SEVERITY');
            $exception = $ex;
            $message = $this->getExceptionMessage($ex, $request);
            return response()->json(['message'=>'Something went wrong ' . json_encode($ex->getMessage())]);
        }
    }
}

public function deletePermission(Request $request) {
    
        $validator = Validator::make($request->all(), [
                    'request' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>401,
                'errors' => $validator->errors()->all()
            ]);
        } else {
            try {
                $permission = Permission::find($request['request']);
                if ($permission) {
                    $permission->delete();
                    return response()->json([
                        'status'=>200,
                        'message' =>'Permission Deleted!'
                    ]);
                }
                return response()->json([
                    'status'=>402,
                    'message' =>' Something went wrong '
                ]);
            } catch (\Exception $ex) {

                $controller_name = \URL::current();
                $payload = $request->all();
                $Severity = env('SEVERITY');
                $exception = $ex;
                $message = $this->getExceptionMessage($ex, $request);
                return response()->json(['message'=>'Something went wrong ' . json_encode($ex->getMessage())]);
            }
        }
    
}

public function updatePermission(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'status'=>401,
            'errors' => $validator->errors()->all()
        ]);
    } else {
        try {
            $permission = Permission::find($request['permission_id']);
            $permission->name = $request['name'];
            if ($permission->save()) {
                return response()->json([
                    'status'=>200,
                    'message' =>'Permission Updated!'
                ]);
            } else {
                return response()->json([
                    'status'=>402,
                    'message' =>' Something went wrong '
                ]);
            }
        } catch (\Exception $ex) {

            $controller_name = \URL::current();
            $payload = $request->all();
            $Severity = env('SEVERITY');
            $exception = $ex;
            $message = $this->getExceptionMessage($ex, $request);
            return response()->json(['message'=>'Something went wrong ' . json_encode($ex->getMessage())]);
        }
    }
}


/*----------------------------------------End Permission ---------------------------------------------------------*/
public function AuthRouteAPI(Request $request){
    return $request->user();
 }

 
 public function updateUserStatus(Request $request)
 {
     $user = User::find($request['request']);
    if($user->status=="0")
     {
         $user->status="1";
     }else{
         $user->status="0";
     }
     $user->save();
     return response()->json([
      "status"=>200,
      "message"=>"Status Updated Successfully"
    ]); 

 }
}
