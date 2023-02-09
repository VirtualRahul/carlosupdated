<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class BackendUserController extends Controller
    
{
    public function index() {

    
            $users = User::all();
            return view('Users.user_list', ['users' => $users]);
     

    }
    public function indexApi() {

    
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



    public function updatePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        } else {
            try {
                $user = User::find($request['user_id']);
                $user->password = Hash::make($request['password']);
                if ($user->save()) {
                    return redirect()->back()->with('status', 'success')->with('message', 'User Password Updated');
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


    public function updateUser(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        } else {
            try {
                $user = User::find($request['user_id']);
                $user->name  = $request['name'];
                $user->email = $request['email'];
                $user->assignRole("user");
                if ($user->save()) {
                    return redirect()->back()->with('status', 'success')->with('message', 'User Updated');
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

    public function deleteUser(Request $request){
        $user = User::find($request['request']);
        if ($user->delete()) {
             return redirect()->back()->with('status', 'success')->with('message', 'User Moved To Trash');
         } else {
             return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
         }
    }

    public function trashedUsers(Request $request){
        $users=User::onlyTrashed()->get();
        return view('Users.trashed_users',['users'=>$users]);
    }

    public function destroyUser(Request $request){
        $user=User::withTrashed()->find($request['request']);

        if ($user->forcedelete()) {
            return redirect()->back()->with('status', 'success')->with('message', 'User deleted');
        } else {
            return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
        }        
        
    }

    public function restoreUser(Request $request){
        $user=User::withTrashed()->find($request['request']);
        if ($user->restore()) {
            return redirect()->back()->with('status', 'success')->with('message', 'User Restored Successfully');
        } else {
            return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
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
				return redirect()->back()->with('status', 'danger')
				->with('message', "You can't enter ".implode(", ", $strArray)." in the name")->withInput();
			}

			$userName ?? $userPassword = $this->preventingUserPassword($request->password);

			if($userPassword){
				return redirect()->back()->with('status', 'danger')
				->with('message', "You can't enter ".implode(", ", $strArray)." in the password")->withInput();
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
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        } else {
            try {
                $role = User::create([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => Hash::make($request['password']),
                    'status'=>'0'
                ]);
                if ($role) {
                    return redirect()->back()->with('status', 'success')->with('message', 'User Created!');
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

    public function assignRole(Request $request){
        $id = $request['request'];
        
            $user = User::find($request['request']);
            $roles = Role::all();
            $permissions = Permission::all();
            return view('Users.assign_role',['user' => $user,'roles' => $roles,'permissions' => $permissions]);
       
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
            return view('Users.list',['roles' => $roles]);
       
    }

    public function insertRole(Request $request) {
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
                return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
            } else {
                try {
                    $role = Role::find($request['request']);
                    if ($role) {
                        $role->delete();
                        return redirect()->back()->with('status', 'success')->with('message', 'Role Deleted');
                    }
                    return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
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

    public function updateRole(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        } else {
            try {
                $role = Role::find($request['role_id']);
                $role->name = $request['name'];
                if ($role->save()) {
                    return redirect()->back()->with('status', 'success')->with('message', 'Role Updated');
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

    /*---------------------------------------- End Role ---------------------------------------------------------*/



/*----------------------------------------Start Permission ---------------------------------------------------------*/


public function addPermission(Request $request){
   
        $id = $request['request'];
        $permission = Permission::find($request['request']);
        return view('Users.add_permission',['permission' => $permission]);
   
}

public function getPermission(){
   
        $permissions = Permission::all();
        return view('Users.list_permission',['permissions' => $permissions]);
    

}

public function insertPermission(Request $request) {
    $validator = Validator::make($request->all(), [
                'name' => 'required',
    ]);
    if ($validator->fails()) {
        return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
    } else {
        try {
            $permission = $request['name'];
            $permission = Permission::create(['name' => $permission]);
            if ($permission) {
                return redirect()->back()->with('status', 'success')->with('message', 'Permission Saved');
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

public function deletePermission(Request $request) {
    
        $validator = Validator::make($request->all(), [
                    'request' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        } else {
            try {
                $permission = Permission::find($request['request']);
                if ($permission) {
                    $permission->delete();
                    return redirect()->back()->with('status', 'success')->with('message', 'Permission Deleted');
                }
                return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
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

public function updatePermission(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);
    if ($validator->fails()) {
        return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
    } else {
        try {
            $permission = Permission::find($request['permission_id']);
            $permission->name = $request['name'];
            if ($permission->save()) {
                return redirect()->back()->with('status', 'success')->with('message', 'Permission Updated');
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
         $data['body']="You can now login with your credentials as admin have approved your registration";
         $data['name']=$user->name;
         $data['url']="http://192.168.50.42:8000/api/login";
        //  Mail::to($user['email'])->send(new \App\Mail\ApprovalMail($data));
     }else{
         $user->status="0";
         $data['body']="You can not login with your credentials as registration have been declined by the admin";
         $data['name']=$user->name;
         $data['url']="http://192.168.50.42:8000/api/register";
        //  Mail::to($user['email'])->send(new \App\Mail\ApprovalMail($data));
     }
     if ($user->save()) {
        return redirect()->back()->with('status', 'success')->with('message', 'Status Updated');
    } else {
        return redirect()->back()->with('status', 'danger')->with('message', 'Something went wrong ');
    }
     

 }
}
