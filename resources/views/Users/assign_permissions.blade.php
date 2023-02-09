@include('layouts.header')
@include('layouts.head')
@include('layouts.sidebar')




<main id="main" class="main">
<div class="row">
    <div class="col-12">

        <div class="card">
            <h5 class="card-header">Udate Permissions</h5>
            <div class="card-body">
                <form action="{{Route('user.update-assign-permission')}}" method="post">
              
                    {{csrf_field()}}
                    <input type="hidden" name="role_id" value="{{$role->id}}">
                    <div class="row">
                        <div class="col-12">
                            
                            <div class="form-group">
                                <label class="control-label">All Permissions <input type="checkbox" class="form-check-input" id="checkAll" class="form-control"  > </label> 
                                @if(isset($permissions))
                                    @foreach($permissions as $permission)
                                        @if(isset($permission->name))
                                            @if($role->hasPermissionTo($permission->name))
                                                <p> <input checked type="checkbox" class="form-check-input" name="permission[]" class="form-control"  value="{{$permission->name}}" > {{$permission->name}} </p> <p> <a href="{{Route('user.remove-permission',['request'=>$role->id,'permission'=>$permission->name])}}" class="edit btn btn-primary btn-sm"> Remove </a> </p>
                                            @else
                                            <p> <input type="checkbox" class="form-check-input" name="permission[]" class="form-control checkedUnchecked"  value="{{$permission->name}}" > {{$permission->name}} </p>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </div>


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




@include('layouts.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.2.1/tinymce.min.js" integrity="sha256-6Q5EaYOf1K2LsiwJmuGtmWHoT1X/kuXKnuZeGudWFB4=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<script type="text/javascript">

$("#checkAll").click(function(){
    $("input[type=checkbox]").prop('checked', $(this).prop('checked')); 
});
</script>


