@extends('index')
@section('content')
<div class="card">
    <div class="row">
        <div class="col-7">
                <h3 class="content-header ml-2">List of Vehicle</h3>
        </div>
        <div class="col-5"><button href="#" class="btn btn-sm btn-outline-primary mr-4 mt-4 float-right"  id="add">+ Service</button></div>
    </div>
    <div class="card-body">
      <table  class="table-hover table table-bordered table-striped data-table">
        <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Plate Number</th>
          <th>Chesis Number</th>
          <th>Model</th>
          <th>Type</th>
          <th>Action</th>
        </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $serv)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $serv->name }}</td>
                <td>{{ $serv->plate_number }}</td>
                <td>{{ $serv->chesis_number }}</td>
                <td>{{ $serv->model }}</td>
                <td>{{ $serv->type }}</td>
                <td>
                     <button type="button" class="btn  btn-outline-secondary btn-sm" value="{{ $serv->uuid }}" id="edit">Edit</button>
                     <button type="button" class="btn  btn-outline-danger btn-sm" onclick="archiveFunction('<?= $serv->uuid  ?>')">Delete</button>
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
                          <h3 class="card-title">Add Service</small></h3>
                        </div>
                          <div class="card-body">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Service Name</label>
                              <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputEmail1">Service Description</label>
                              <input type="text" name="description" class="form-control" id="description" placeholder="Description">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputPassword1">Service Password</label>
                              <input type="file" name="image" class="form-control" id="image" placeholder="File">
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
                          <h3 class="card-title">Edit Service</small></h3>
                        </div>
                          <div class="card-body">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Service Name</label>
                              <input type="text" name="name" class="form-control" id="namee" placeholder="Name">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputEmail1">Service Description</label>
                              <input type="text" name="description" class="form-control" id="descriptione" placeholder="Description">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputPassword1">Service Password</label>
                              <input type="file" name="image" class="form-control" id="imagee" placeholder="File">
                            </div>
                          </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-outline-success"  id="update">Save changes</button>
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
                    name: $('#name').val(),
                    description: $('#description').val(),
                    image: $('#image').val(),
                }

                if ($('#name').val() !== ''  && $('#description').val() !== '') {
                  $.ajax({
                    url: '/services/store',
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



    $('#update').click(function(e){
  e.preventDefault();
  var id = $('#edit').attr('value');
  $.ajax({
    url: '/services/update/' + id,
    method: 'post',
    data: {
            name: $('#namee').val(),
            description: $('#descriptione').val(),
            image: $('#imagee').val(),
    },
    dataType: 'JSON',
    success: function(response){
      $('#namee').val(response.name);
    $('#descriptione').val(response.description);
      $('#edit-modal').modal('show');
    }

  })
});
</script>


<script>

$('#search').on('keyup',function(){
$value=$(this).val();
$.ajax({
type : 'get',
url : '/services/search',
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
          url: '/services/edit/' + uuid,
          method: 'get',
          success: function(response){
          $('#namee').val(response.name);
          $('#descriptione').val(response.description);
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
                url: '/services/destroy/' + uuid,
                success: function(response) {
                    toastr.success(response.success);
                    getData();
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

    // getData();
      // function getData() {  
      //   $.ajax({
      //           type: "get",
      //           url: "/services/data",
      //           success: function (res) {
      //             let htmlView = '';
      //               if(res.services.length <= 0){
      //                   htmlView+= `
      //                     <tr>
      //                         <td colspan="4">No data.</td>
      //                     </tr>`;
      //               }
      //               for(let i = 0; i < res.services.length; i++){
      //                   htmlView += `
      //                       <tr>
      //                         <td>`+ (i+1) +`</td>
      //                             <td>`+res.services[i].name+`</td>
      //                             <td>`+res.services[i].description+`</td>
      //                             <td>`+res.services[i].image+`</td>
      //                             <td><button type="button" class="btn btn-outline-secondary btn-sm" onclick="edit('${res.services[i].uuid}')" id="edit">Edit</button>
      //                               <button type="button" class="btn btn-outline-danger btn-sm" onclick="deletee('${res.services[i].uuid}')" id="delete">Delete</button></td>
      //                       </tr>`;
      //               }
      //                   $('tbody').html(htmlView);
      //                 }
      //         });
      //     }
  });
</script>
  @endsection