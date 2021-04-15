@extends('layouts.admin')
@section('content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
                                <label for="exampleInputEmail1">Email</label>
                                <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter Email" value="{{$data->email}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Current Address</label>
                                <input type="text" name="address" class="form-control" id="exampleInputEmail1" placeholder="Enter Address" value="{{$data->address}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Current City</label>
                                <select class="form-control" name="city" required>
                                    <option value="0" >Select</option>
                                    @foreach($cities as $city)
                                    <option value="{{$city->id}}" {{$city->id==$data->city?'selected':''}}>{{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Current State</label>

                                <select class="form-control" name="city" required>
                                    <option value="0" >Select</option>
                                    @foreach($States as $state)
                                        <option value="{{$state->id}}" {{$state->id==$data->state?'selected':''}}>{{$state->name}}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Location</label>
                                <select class="form-control" name="location_id[]" multiple>
                                    <option value="">Please Select Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{$location->id}}" @foreach($data->locations as $l){{($l->id??'')==$location->id?'selected':''}}@endforeach>
                                            {{$location->name}}--{{$location->city->name??''}}</option>
                                    @endforeach
                                </select>
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
                                <label>Available to accept orders?</label>
                                <select class="form-control" name="is_available" required>
                                    <option value="1" {{$data->is_available==1?'selected':''}}>Yes</option>
                                    <option value="0" {{$data->is_available==0?'selected':''}}>No</option>
                                </select>
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
                            <img src="{{$data->image}}" height="100" width="100">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Work Time</label>
                                <select class="form-control" name="work_type" required>
                                    <option  selected="selected" value="1" {{$data->work_type==1?'selected':''}}>Full Time</option>
                                    <option value="0" {{$data->work_type==0?'selected':''}}>Part-Time</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Secondary Mobile </label>
                                <input type="number" maxlength="10" minlength="10" name="secondary_mobile" class="form-control" id="exampleInputEmail1" placeholder="Enter Secondary Mobile" value="{{$data->secondary_mobile}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Emergency Mobile </label>
                                <input type="number" maxlength="10" minlength="10" name="emergency_mobile" class="form-control" id="exampleInputEmail1" placeholder="Enter Emergency Mobile" value="{{$data->emergency_mobile}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Permanent Address</label>
                                <input type="text" name="permanent_address" class="form-control" id="exampleInputEmail1" placeholder="Enter Permanent Address" value="{{$data->permanent_address}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Permanent State</label>
                                <select class="form-control" name="permanent_state" required>
                                    <option value="">Please Select State</option>
                                    @foreach($States as $State)
                                        <option value="{{$State->id}}" {{$data->permanent_state==$State->id?'selected':''}}>
                                            {{$State->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Permanent City</label>
                                <select class="form-control" name="permanent_city" required>
                                    <option value="0" >Select</option>
                                    @foreach($cities as $city)
                                        <option value="{{$city->id}}" {{$city->id==$data->permanent_city?'selected':''}}>{{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Permanent Pin Code</label>
                                <input type="text" name="permanent_pin" class="form-control" id="exampleInputEmail1" placeholder="Enter Pin Code" value="{{$data->permanent_pin}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Account Holder Name</label>
                                <input type="text" name="account_holder" class="form-control" id="exampleInputEmail1" placeholder="Enter Account Holder Name" value="{{$data->account_holder}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" id="exampleInputEmail1" placeholder="Enter Bank Name" value="{{$data->bank_name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Account No</label>
                                <input type="text" name="account_no" class="form-control" id="exampleInputEmail1" placeholder="Enter Account No" value="{{$data->account_no}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">IFSC Code</label>
                                <input type="text" name="ifsc_code" class="form-control" id="exampleInputEmail1" placeholder="Enter IFSC Code" value="{{$data->ifsc_code}}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Select (Pay)</label><br>
                                <div class="form-group clearfix">
                                    <div class="icheck-danger d-inline">
                                        @if($data->pay_per_km==1)
                                        <input type="checkbox" id="checkboxDanger1" name="pay_per_km" value="@if($data->pay_per_km==1)1@else 0 @endif" {{$data->pay_per_km==1?'checked':''}}>
                                            <label for="checkboxDanger1" class="form-check-label">Pay Per Km</label>

                                        @elseif($data->pay_per_km==0)
                                            <input type="checkbox" id="checkboxDanger1" name="pay_per_km" value="1">
                                        <label for="checkboxDanger1" class="form-check-label">Pay Per Km</label>
                                        @endif
                                    </div><br><br>

                                    <div class="icheck-danger d-inline">
                                        @if($data->pay_commission==1)
                                        <input type="checkbox" id="checkboxDanger2" name="pay_commission" value="@if($data->pay_commission==1)1@else 0 @endif" {{$data->pay_commission==1?'checked':''}}>
                                        <label for="checkboxDanger2" class="form-check-label">Pay Commission</label>
                                        @elseif($data->pay_commission==0)
                                            <input type="checkbox" id="checkboxDanger2" name="pay_commission" value="1">
                                            <label for="checkboxDanger2" class="form-check-label">Pay Commission</label>
                                        @endif
                                    </div><br><br>

                                    <div class="icheck-danger d-inline">
                                        @if($data->pay_delivery==1)
                                        <input type="checkbox" id="checkboxDanger3" name="pay_delivery" value="@if($data->pay_delivery==1)1@else 0 @endif" {{$data->pay_delivery==1?'checked':''}}>
                                        <label for="checkboxDanger3" class="form-check-label">Pay Delivery</label>
                                        @elseif($data->pay_delivery==0)
                                            <input type="checkbox" id="checkboxDanger3" name="pay_delivery" value="1">
                                            <label for="checkboxDanger3" class="form-check-label">Pay Delivery</label>
                                        @endif
                                    </div>
                                </div>
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
</div>
<!-- ./wrapper -->
@endsection

@section('scripts')

    <script type="text/javascript">
        $(document).ready(function() {
            $('select[name="permanent_state"]').on('change', function() {
                var stateID = $(this).val();
                var data ='permanent_state='+stateID;
                //alert(stateID)
                if(stateID) {
                    $.ajax({
                        url: "{{route('shoppr.state.ajax')}}",
                        type: "GET",
                        dataType: "json",
                        data: data,
                        success:function(data) {

                            $('select[name="permanent_city"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="permanent_city"]').append('<option value="'+ key +'">'+ value +'</option>');
                            });
                        }
                    });
                }else{
                    $('select[name="permanent_city"]').empty();
                }
            });
        });
    </script>

@endsection



