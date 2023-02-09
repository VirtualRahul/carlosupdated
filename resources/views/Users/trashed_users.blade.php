@include('layouts.header')
@include('layouts.head')
@include('layouts.sidebar')
<main id="main" class="main">
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-10">
                <h5 class="">Trashed Users List</h5>
            </div>
            <div class="col-md-2">
            <a href="{{Route('user.users')}}" class="edit btn btn-primary btn-sm">Add To Trash</a>
            </div>
            
        </div>
    </div>
    <div class="card-body">
        @if(count($users))
        <div class="table-responsive">
            <table class="table ">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Updated At</th>
                        
                        <!-- , "User Change Password" -->
                        <th>Action</th>
                       
                    </tr>


                </thead>
                <tbody>
                    @foreach($users as $user)
                    <?php $arr = $user->roles()->pluck('name') ?>
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}} </td>
                        <td>{{$arr}} </td>
                        <td>{{$user->updated_at}}</td>
                        
                        <td>
                           
                            <!-- <a href="{{Route('user.assign-role',['request'=>$user->id])}}" class="edit btn btn-primary btn-sm">Update Role</a> -->
                           
                           
                            <a href="{{Route('user.restore-user',['request'=>$user->id])}}" class="edit btn btn-success btn-sm">Restore</a>

                            <a href="{{Route('user.destroy-user',['request'=>$user->id])}}" class="edit btn btn-danger btn-sm">Delete</a>
                            

                           {{-- @can("User Change Password")
                            <a href="{{Route('user.change-password',['request'=>$user->id])}}" class="edit btn btn-primary btn-sm">Change Password</a>
                                <!-- <a href="{{Route('user.delete-role',['request'=>$user->id])}}" class="edit btn btn-danger btn-sm" id="deletePage">Delete</a> -->
                            @endcan --}}
                        </td>
                       
                    </tr>
                    @endforeach


                </tbody>
            </table>
            
        </div>
        @else
        <p>No data</p>
        @endif
    </div>
</div>
                           </main>
@include('layouts.footer')



<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

<script type="text/javascript">
$(function () {
 
    $(".table").DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    
});
</script>
<script>
    $(function(){
       $(document).on('click','#deletePage',function(){
var r = confirm("Are you sure ?");
if (r == true) {
  return true;
}else{
    return false;
} 
    }); 
    });
</script>

