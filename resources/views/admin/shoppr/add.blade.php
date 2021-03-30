@extends('layouts.admin')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Shoppr</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{route('shoppr.list')}}">Shoppr</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Shoppr Add</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" enctype="multipart/form-data" action="{{route('shoppr.store')}}">
                 @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile</label>
                                <input type="number" name="mobile" maxlength="10" minlength="10" class="form-control" id="exampleInputEmail1" placeholder="Enter Mobile">
                            </div>
                        </div>
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="exampleInputEmail1">Location</label>--}}
{{--                                <input type="text" name="location" class="form-control" id="exampleInputEmail1" placeholder="Enter location">--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Is Active</label>
                                <select class="form-control" name="isactive" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" style="display:none">
                            <div class="form-group">
                               <label for="exampleInputEmail1">Latitude</label><br>
                                    <input type="text" name="lat" class="form-control" id="exampleInputEmail1" placeholder="Enter Latitude" >
                            </div>
                        </div>
                        <div class="col-md-6" style="display:none">
                          <div class="form-group">
                           <label for="exampleInputEmail1">Langitude</label><br>
                              <input type="text" name="lang" class="form-control" id="exampleInputEmail1" placeholder="Enter Langitude">
                          </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Location</label>
                                <select class="form-control" name="location_id[]" required multiple>
                                    @foreach($worklocations as $location)
                                    <option value="{{$location->id}}">{{$location->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label>Is Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">Shoppr Image</label>
                                <input type="file" name="image" class="form-control"  id="exampleInputFile" accept="image/*" required>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- ./wrapper -->
@endsection

