@include('layouts.header')
@include('layouts.head')
@include('layouts.sidebar')

@section('title', 'Add Page')

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
                                <input type="text" name="name" class="form-control"  required="" value="{{$role->name}}">
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
            <h5 class="card-header">Add Role</h5>
            <div class="card-body">
                <form action="{{Route("user.insert-role")}}" method="post">
                    {{csrf_field()}}

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="control-label">Title</label>
                                <input type="text" name="name" class="form-control"  required="">
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

@endif





@include('layouts.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.2.1/tinymce.min.js" integrity="sha256-6Q5EaYOf1K2LsiwJmuGtmWHoT1X/kuXKnuZeGudWFB4=" crossorigin="anonymous"></script>


