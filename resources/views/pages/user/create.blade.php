@extends('index')
@section('content')
                <!-- jquery validation -->
                <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">Add Service</small></h3>
                    </div>
                   @if ($errors->any())
                       @foreach ($errors as $error)
                       <li class="alert-success">{{ $error }}</li>
                       @endforeach
                   @endif
                    <form action="/services/store" method="post">
                      @csrf
                      <div class="card-body">
                        <div class="form-group">
                          <label for="exampleInputEmail1">Service Name</label>
                          <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Name">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Service Description</label>
                          <input type="text" name="description" class="form-control" id="exampleInputEmail1" placeholder="Description">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputPassword1">Service Password</label>
                          <input type="file" name="image" class="form-control" id="exampleInputPassword1" placeholder="File">
                        </div>
                      </div>
                      <!-- /.card-body -->
                      <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  </div>
@endsection