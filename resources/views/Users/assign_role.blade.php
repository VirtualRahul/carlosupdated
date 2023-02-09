@include('layouts.header')
@include('layouts.head')
@include('layouts.sidebar')

@section('title', 'Update User')

@if(isset($role))
<main id="main" class="main">
<div class="row">
    <div class="col-12">

        <div class="card">
            <h5 class="card-header">Edit Role</h5>
            <div class="card-body">
                <form action="{{Route("user.update-role")}}" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="role_id" value="{{$role->id}}">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">Name</label>
                                <input type="checkbox" name="name" class="form-control"  required="" value="{{$role->name}}">
                            </div>
                        </div>
                        
                    </div>

        
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg">Update</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

</main>
@else
<main id="main" class="main">
<div class="row">
    <div class="col-12">

        <div class="card">
            <h5 class="card-header">Update Role</h5>
            <div class="card-body">
                <form action="{{Route('user.update-assign-role')}}" method="post">
              
                    {{csrf_field()}}
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">Roles</label>
                                
                                @if(isset($roles))
                                    @foreach($roles as $role)
                                      
                                        @if($user->hasRole($role->name))
                                            <p> <input  checked type="checkbox"  class="form-check-input" name="role[]" class="form-control"  value="{{$role->name}}" > {{$role->name}} </p> <p> <a href="{{Route('user.remove-role',['request'=>$user->id,'role'=>$role->name])}}" class="edit btn btn-primary btn-sm"> Remove </a> </p>
                                      
                                        @else
                                            <p> <input type="checkbox" class="form-check-input" name="role[]" class="form-control"   value="{{$role->name}}" > {{$role->name}} </p>
                                        @endif
                                       
                                    @endforeach
                                @endif
                            </div>

                            <!-- <div class="form-group">
                                <label class="control-label">Permissions</label>
                                @if(isset($permissions))
                                    @foreach($permissions as $permission)
                                        @if(isset($permission->name))
                                            @if($user->hasPermissionTo($permission->name))
                                                <p> <input checked type="checkbox" name="permission[]" class="form-control"  value="{{$permission->name}}" > {{$permission->name}} </p>
                                            @else
                                            <p> <input type="checkbox" name="permission[]" class="form-control"  value="{{$permission->name}}" > {{$permission->name}} </p>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </div> -->


                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg">Save</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

</main>
@endif

@include('layouts.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.2.1/tinymce.min.js" integrity="sha256-6Q5EaYOf1K2LsiwJmuGtmWHoT1X/kuXKnuZeGudWFB4=" crossorigin="anonymous"></script>


