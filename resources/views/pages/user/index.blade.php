@extends('index')
@section('content')
<div class="card">
    <div class="row">
        <div class="col-7">
                <h3 class="content-header ml-2">List of Users</h3>
        </div>
        <div class="col-5"><button href="#" class="btn btn-sm btn-outline-primary mr-4 mt-4 float-right"  id="add">+ User</button></div>
    </div>
    <div class="card-body">
      <table  class="table-hover table table-bordered table-striped data-table">
        <thead>
        <tr>
          <th>#</th>
          <th>Full Name</th>
          <th>Location</th>
          <th>Phone</th>
          <th>Email</th>
          <th>Role</th>
          <th>Action</th>
        </tr>
        </thead>
        <tbody>
            @foreach($users as $serv)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $serv->fname ." ". $serv->lname }}</td>
                <td>{{ $serv->location }}</td>
                <td>{{ $serv->phone }}</td>
                <td>{{ $serv->email }}</td>
                <td>{{ $serv->roleId == 1 ? "Admin" : ($serv->roleId == 2 ? "Owner" : "Police")  }}</td>
                <td>
                     <button type="button" class="btn  btn-outline-secondary btn-sm edit" value="{{ $serv->id }}" id="edit">Edit</button>
                     <button type="button" class="btn  btn-outline-danger btn-sm delete" value="{{ $serv->id }}">Delete</button>
                     {{-- <a href="{{ url('map') }}" class="btn  btn-outline-secondary btn-sm ">Track</a> --}}
                </td>
              </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  </div>


        <!-- /.modal -->

        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg" >
              <div class="modal-content">
                <div class="modal-body">
                    @if ($errors->any())
                    @foreach ($errors as $error)
                    <li class="alert-success">{{ $error }}</li>
                    @endforeach
                @endif
                 <form action="/services/store" method="post"  enctype="multipart/form-data" id="formData">
                   @csrf
                    <div class="card card-dark">
                        <div class="card-header">
                          <h3 class="card-title">Add User</small></h3>
                        </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">First Name</label>
                                  <input type="text" name="fname" class="form-control" id="fname" placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Last Name</label>
                                  <input type="text" name="lname" class="form-control" id="lname" placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Location </label>
                                  <input type="text" name="location" class="form-control" id="location" placeholder="">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputPassword1">Phone</label>
                                  <input type="number" name="phone" class="form-control" id="phone" placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputPassword1">Email</label>
                                  <input type="email" name="email" class="form-control" id="email" placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputPassword1">Role</label>
                                  <input type="text" name="roleId" class="form-control" id="roleId" placeholder="">
                                </div>
                              </div>
                            </div>
                          </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-outline-success"  id="sendData">Save changes</button>
                      </div>
                    </form>
                </div>
 
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->



          {{-- edit modal --}}
          <div class="modal fade" id="edit-modal">
            <div class="modal-dialog edit-modal" >
              <div class="modal-content">
                <div class="modal-body">
                 <form  method="post" enctype="multipart/form-data" id="formDataUpdate">
                   @csrf
                    <div class="card card-dark">
                        <div class="card-header">
                          <h3 class="card-title">Edit User</small></h3>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="exampleInputEmail1">First Name</label>
                              <input type="text" name="fnamee" class="form-control" id="fnamee" placeholder="">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputEmail1">Last Name</label>
                              <input type="text" name="lnamee" class="form-control" id="lnamee" placeholder="">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputEmail1">Location </label>
                              <input type="text" name="locationn" class="form-control" id="locationn" placeholder="">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="exampleInputPassword1">Phone</label>
                              <input type="number" name="phonee" class="form-control" id="phonee" placeholder="">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputPassword1">Email</label>
                              <input type="email" name="emaill" class="form-control" id="emaill" placeholder="">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputPassword1">Role</label>
                              <input type="text" name="roleIdd" class="form-control" id="roleIdd" placeholder="">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-outline-success update"  id="update">Save changes</button>
                      </div>
                    </form>
                </div>
 
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          {{-- end edit --}}
  @endsection

  
  @section('scripts')
  <script type="text/javascript">
    $(function () {
      getData();
      function getData(){
        $.ajax({
            url: '/services',
            type: 'get',
            success: function(data){
             $('tbody').html(data);
            }
        });
      }
    });
  </script>


<script>
 


$('#sendData').click(function(event){
            event.preventDefault();
            var  form = {
                    fname: $('#fname').val(),
                    lname: $('#lname').val(),
                    location: $('#location').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    roleId: $('#roleId').val(),
                }

                if ($('#fname').val() !== ''  && $('#email').val() !== '') {
                  $.ajax({
                    url: '/users/store',
                    method: 'post',
                    dataType: 'JSON',
                    data: form,
                    success: function (response) {
                      $('#modal-lg').modal('hide');
                      toastr.success(response.success);
                      form.reset();
                    },
                    error: function (errors) {
                      $.each(errors, function(key, value) {
                            // $('#description').after('<p>' + value.message + '</p>');
                           console.log(value.message);
                        });
                    },
                });            
              } else {
                alert('Please fill in all the form fields except image.');
            }
        });


        
    $('#add').click(function(){
              $('#modal-lg').modal('show');
            });



    $('.update').click(function(e){
  e.preventDefault();
  var  form = {
    fname: $('#fnamee').val(),
    lname: $('#lnamee').val(),
    location: $('#locationn').val(),
    phone: $('#phonee').val(),
    email: $('#emaill').val(),
    roleId: $('#roleIdd').val(),
    }
  var id = $('#edit').attr('value');
  $.ajax({
    url: '/users/update/' + id,
    method: 'post',
    data: form,
   dataType: 'JSON',
   success: function(response){
    toastr.success(response.success);
    location.reload();     
   },
   error: function(response) {
                    toastr.error(response.error);
                }

  })
});
</script>


<script>

$('#search').on('keyup',function(){
$value=$(this).val();
$.ajax({
type : 'get',
url : '/users/search',
data:{'search':$value},
success:function(data){
$('tbody').html(data);
}
});
})

  $(document).ready(function(){
    // edit
  $(document).on('click', '.edit', function () {
          var uuid = $(this).attr('value');
          $.ajax({
          url: '/users/edit/' + uuid,
          method: 'get',
          success: function(response){
            console.log(response);
          $('#fnamee').val(response.fname);
          $('#lnamee').val(response.lname);
          $('#locationn').val(response.location);
          $('#phonee').val(response.phone);
          $('#emaill').val(response.email);
          $('#roleIdd').val(response.roleId);
          $('#edit-modal').modal('show');
          }
  });
});


//delete
$(document).on('click', '.delete', function () {
    var uuid = $(this).attr('value');
    Swal.fire({
    title: "Are you sure?",
    text: "You will not be able to retrieve it again",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Yes, Delete it!",
    cancelButtonText: "No, cancel please!",
    closeOnConfirm: true,
    closeOnCancel: true
  }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                method: 'delete',
                url: '/vehicles/destroy/' + uuid,
                success: function(response) {
                    toastr.success(response.success);
                    // getData();
                    location.reload();
                },
                error: function(response) {
                    toastr.error('An error occured!');
                }
            });
        } 
    });
});

    $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

  });
</script>
  @endsection