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
                     <button type="button" class="btn  btn-outline-secondary btn-sm edit" value="{{ $serv->uuid }}" id="edit">Edit</button>
                     <button type="button" class="btn  btn-outline-danger btn-sm delete" value="{{ $serv->uuid }}">Delete</button>
                     <a href="{{ url('map') }}" class="btn  btn-outline-secondary btn-sm ">Track</a>
                     <a href="{{ url('geofence') }}" class="btn  btn-outline-secondary btn-sm ">Geofence</a>
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
                          <h3 class="card-title">Add Vehicle</small></h3>
                        </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Vehicle Name</label>
                                  <input type="text" name="name" class="form-control" id="name" placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Vehicle Model </label>
                                  <input type="text" name="model" class="form-control" id="model" placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputPassword1">Vehicle Type</label>
                                  <input type="text" name="type" class="form-control" id="type" placeholder="">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="exampleInputPassword1">Plate Number</label>
                                  <input type="text" name="plate_number" class="form-control" id="plate_number" placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputPassword1">Chesis Number</label>
                                  <input type="text" name="chesis_number" class="form-control" id="chesis_number" placeholder="">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputPassword1">Vehicle Image</label>
                                  <input type="file" name="image" class="form-control" id="image" placeholder="">
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
                          <h3 class="card-title">Edit Vehicle</small></h3>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Vehicle Name</label>
                              <input type="text" name="namee" class="form-control" id="namee" placeholder="">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputEmail1">Vehicle Model </label>
                              <input type="text" name="modell" class="form-control" id="modell" placeholder="">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputPassword1">Vehicle Type</label>
                              <input type="text" name="typee" class="form-control" id="typee" placeholder="">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="exampleInputPassword1">Plate Number</label>
                              <input type="text" name="plate_numberr" class="form-control" id="plate_numberr" placeholder="">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputPassword1">Vehicle Type</label>
                              <input type="text" name="chesis_numberr" class="form-control" id="chesis_numberr" placeholder="">
                            </div>
                            <div class="form-group">
                              <label for="exampleInputPassword1">Vehicle Image</label>
                              <input type="file" name="imagee" class="form-control" id="image" placeholder="">
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
                    name: $('#name').val(),
                    model: $('#model').val(),
                    type: $('#type').val(),
                    plate_number: $('#plate_number').val(),
                    chesis_number: $('#chesis_number').val(),
                    image: $('#image').val(),
                }

                if ($('#name').val() !== ''  && $('#plate_number').val() !== '') {
                  $.ajax({
                    url: '/vehicles/store',
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
                    name: $('#namee').val(),
                    model: $('#modell').val(),
                    type: $('#typee').val(),
                    plate_number: $('#plate_numberr').val(),
                    chesis_number: $('#chesis_numberr').val(),
                    image: $('#imagee').val(),
                }
  var id = $('#edit').attr('value');
  $.ajax({
    url: '/vehicles/update/' + id,
    method: 'post',
    data: form,
   dataType: 'JSON',
   success: function(response){
    toastr.success(response.success);
    location.reload();     
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
          url: '/vehicles/edit/' + uuid,
          method: 'get',
          success: function(response){
          $('#namee').val(response.name);
          $('#modell').val(response.model);
          $('#typee').val(response.type);
          $('#chesis_numberr').val(response.chesis_number);
          $('#plate_numberr').val(response.plate_number);
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