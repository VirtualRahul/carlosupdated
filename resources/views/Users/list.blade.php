@include('layouts.header')
@include('layouts.head')
@include('layouts.sidebar')
<main id="main" class="main">
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-12">
                <h5 class="">Roles List</h5>
            </div>
            
        </div>
    </div>
    <div class="card-body">
        @if(count($roles))
        <div class="table-responsive">
            <table class="table ">
                <thead>
                    <tr>
                         <th>Role</th>
                         <th>Permissions</th> 
                         <th>Updated At</th>
                         <th>Action</th>
                        
                    </tr>


                </thead>
                <tbody>

                    @foreach($roles as $role)
                    <tr>
                        <td>{{$role->name}}</td>
                        <td>{{$role->permissions()->pluck('name')}} </td>
                        <td>{{$role->updated_at}}</td>
                        
                        <td>

                          
                                <a href="{{Route('user.add-role',['request'=>$role->id])}}" class="edit btn btn-primary btn-sm">Edit</a>
                                

                                
                                <a href="{{Route('user.assign-permission',['request'=>$role->id])}}" class="edit btn btn-primary btn-sm">Update Permission</a>
                               

                              
                                <a href="{{Route('user.delete-role',['request'=>$role->id])}}" class="edit btn btn-danger btn-sm" id="deletePage">Delete</a>
                                

                            
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
</main >


@include('layouts.footer')

<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>


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

