@extends('layouts.admin')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Store Add</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Store Add</li>
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
                <h3 class="card-title">Store Add</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" enctype="multipart/form-data" action="{{route('store.store')}}">
                 @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="exampleInputEmail1">Store Name</label>
                            <input type="text" name="store_name" class="form-control" id="exampleInputEmail1" placeholder="Enter Store Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Store Type</label>
                                <input type="text" name="store_type"class="form-control" id="exampleInputEmail1" placeholder="Enter Store type">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile</label>
                                <input type="number" maxlength="10" minlength="10" name="mobile"class="form-control" id="exampleInputEmail1" placeholder="Enter Mobile">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="email" name="email"class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Open Timing</label>
                                <input type="text" name="opening_time"class="form-control" id="exampleInputEmail1" placeholder="Enter open timing">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Is Active</label>
                                <select class="form-control" name="isactive" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Address</label>
                                <textarea class="form-control" placeholder="Enter Address" name="address" rows="3"> </textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">About Store</label>
                                <textarea class="form-control" placeholder="Enter About Stor" name="about_store" rows="3"> </textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="exampleInputEmail1">Latitude</label><br>
                                <input type="text" name="lat" class="form-control" id="exampleInputEmail1" placeholder="Enter Latitude">
                            </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                           <label for="exampleInputEmail1">Langitude</label><br>
                              <input type="text" name="lang" class="form-control" id="exampleInputEmail1" placeholder="Enter Langitude">
                          </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <input type="file" name="image" class="form-control"  id="exampleInputFile" accept="image/*" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Is Sale</label>
                                <select class="form-control" name="is_sale" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
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

