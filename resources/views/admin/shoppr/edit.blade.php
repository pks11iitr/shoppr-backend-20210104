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
                <li class="breadcrumb-item active"><a href="{{route('shoppr.list')}}">Shoppr </a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

      <section class="content">
          <div class="container-fluid">
              <div class="row">
                  <!-- left column -->
                  <div class="col-md-12">
                      <!-- general form elements -->
                      <div class="card card-primary">
                          <div class="card-header">
                              <h3 class="card-title">Shoppr Wallet</h3>

                          </div>
                          <!-- /.card-header -->
                          <!-- form start -->

                          <form role="form" method="post" enctype="multipart/form-data" action="{{route('shoppr.wallet.add',['id'=>$data->id])}}">
                              @csrf
                              <div class="card-body">
                                  <a href="{{route('shoppr.tranaction.list',['id'=>$data->id])}}" class="btn btn-success">Transaction</a>
                                  <div class="form-group">
                                      <label for="exampleInputEmail1">Available Balance: {{\App\Models\ShopprWallet::balance($data->id)}}</label>
                                  </div>
                                  <div class="row">

                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label for="exampleInputEmail1">Enter Amount</label>
                                              <input type="number" name="amount" class="form-control" id="exampleInputEmail1" placeholder="Enter add money">
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label for="exampleInputEmail1">Credit/Debit</label>
                                              <select name="type" class="form-control">
                                                  <option value="Credit">Credit</option>
                                                  <option value="Debit">Debit</option>
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

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Shoppr Update</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" method="post" enctype="multipart/form-data" action="{{route('shoppr.update',['id'=>$data->id])}}">
                 @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" value="{{$data->name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile</label>
                                <input type="number" maxlength="10" minlength="10" name="mobile" class="form-control" id="exampleInputEmail1" placeholder="Enter Mobile" readonly value="{{$data->mobile}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Location</label>
                                <input type="text" name="location" class="form-control" id="exampleInputEmail1" placeholder="Enter Location" value="{{$data->location}}">
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
                            <label for="exampleInputEmail1">Latitude</label><br>
                                <input type="text" name="lat" class="form-control" id="exampleInputEmail1" placeholder="Enter Latitude" value="{{$data->lat}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Langitude</label><br>
                              <input type="text" name="lang" class="form-control" id="exampleInputEmail1" placeholder="Enter Lang" value="{{$data->lang}}">
                          </div>
                        </div>
{{--                        <div class="col-md-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label>Is Status</label>--}}
{{--                                <select class="form-control" name="status" required>--}}
{{--                                    <option  selected="selected" value="1" {{$data->status==1?'selected':''}}>Yes</option>--}}
{{--                                    <option value="0" {{$data->status==0?'selected':''}}>No</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <input type="file" name="image" class="form-control"  id="exampleInputFile" accept="image/*" >

                            </div>
                            <img src="{{$data->image}}" height="100" width="200">
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
</div>
<!-- ./wrapper -->
@endsection

