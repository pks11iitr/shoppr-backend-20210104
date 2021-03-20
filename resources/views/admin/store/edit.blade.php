@extends('layouts.admin')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Store</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{route('store.list')}}">Store</a></li>
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
                <h3 class="card-title">Store Update</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" enctype="multipart/form-data" action="{{route('store.update',['id'=>$data->id])}}">
                 @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Store Name</label>
                                <input type="text" name="store_name" class="form-control" id="exampleInputEmail1" placeholder="Enter Store Name" value="{{$data->store_name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Store Type</label>
                                <input type="text" name="store_type"class="form-control" id="exampleInputEmail1" placeholder="Enter Store type" value="{{$data->store_type}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile</label>
                                <input type="number" name="mobile" maxlength="10" minlength="10" class="form-control" id="exampleInputEmail1" placeholder="Enter Mobile" value="{{$data->mobile}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email</label>
                                <input type="email" name="email"class="form-control" id="exampleInputEmail1" placeholder="Enter email" value="{{$data->email}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Open Timing</label>
                                <input type="text" name="opening_time"class="form-control" id="exampleInputEmail1" placeholder="Enter Open Timing" value="{{$data->opening_time}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Is Active</label>
                                <select class="form-control" name="isactive" required>
                                    <option  selected="selected" value="1" {{$data->isactive==1?'selected':''}}>Yes</option>
                                    <option value="0" {{$data->isactive==0?'selected':''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Address</label>
                                <textarea class="form-control" name="address" rows="3">
                                    {{$data->address}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">About Store</label>
                                <textarea class="form-control" name="about_store" rows="3">  {{$data->about_store}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6" style="display: none">
                            <div class="form-group">
                            <label for="exampleInputEmail1">Latitude</label><br>
                                <input type="text" name="lat" class="form-control" id="exampleInputEmail1" placeholder="Enter Latitude" value="{{$data->lat}}">
                            </div>
                        </div>
                        <div class="col-md-6" style="display: none">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Langitude</label><br>
                              <input type="text" name="lang" class="form-control" id="exampleInputEmail1" placeholder="Enter Lang" value="{{$data->lang}}">
                          </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Location</label>
                                <select class="form-control" name="location_id" required>
                                    @foreach($worklocations as $location)
                                        <option value="{{$location->id}}">{{$location->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <input type="file" name="image" class="form-control"  id="exampleInputFile" accept="image/*" >
                            </div>
                            <img src="{{$data->image}}" height="100" width="200">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Is Sale</label>
                                <select class="form-control" name="is_sale" required>
                                    <option  selected="selected" value="1" {{$data->is_sale==1?'selected':''}}>Yes</option>
                                    <option value="0" {{$data->is_sale==0?'selected':''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Categories</label>
                                <select class="form-control" name="categories[]" required multiple>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" @foreach($data->categories as $cat) @if($cat->id==$category->id){{'selected'}}@endif @endforeach>{{$category->name}}</option>
                                    @endforeach
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
      <section class="content">
          <div class="container-fluid">
              <!-- SELECT2 EXAMPLE -->
              <div class="card card-default">
                  <div class="card-header">
                      <h3 class="card-title">Customer Images Add</h3>
                  </div>
                  <!-- /.card-header -->
                  <form action="{{route('store.images.uploads',['id'=>$data->id])}}" method="post" enctype="multipart/form-data">
                      @csrf
                      <div class="card-body">
                          <!-- /.row -->
                          <div class="row">
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label for="exampleInputEmail1">Customer Image</label>
                                      <input type="file" class="form-control" name="images[]" id="exampleInputEmail1" placeholder="Select image" accept="image/*" multiple>
                                      <br>
                                  </div>
                              </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-3">
                              <div class="form-group">
                                  <button type="submit" class="btn btn-block btn-primary btn-sm">Add</button>
                              </div>
                          </div>
                          <!-- /.col -->
                          <div class="row">
                              <!-- /.col -->
                              @foreach($data->images as $Image)
                                  <div class="form-group">
                                      <img src="{{$Image->image}}" height="100" width="200"> &nbsp; &nbsp; <a href="{{route('store.image.delete',['id'=>$Image->id])}}">X</a>
                                      &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;          &nbsp; &nbsp; &nbsp; &nbsp;
                                  </div>
                              @endforeach
                          </div>
                      </div>
                      <!-- /.row -->
                  </form>
              </div>
          </div>
      </section>

</div>
<!-- ./wrapper -->
@endsection

