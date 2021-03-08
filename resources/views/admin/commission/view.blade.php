@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Commission </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Commission</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-3">
{{--                                        <a href="{{route('shoppr.create')}}" class="btn btn-primary">Add Shoppr</a> --}}

                                    </div>
                                    <div class="col-12">

                                        <form class="form-validate form-horizontal" method="get" action="" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-4">

                                                    <select id="shoppr_id" name="shoppr_id" class="form-control" >
                                                        <option value="" {{ request('shoppr_id')==''?'selected':''}}>Select Shoppr</option>
                                                        @foreach($riders as $rider)
                                                            <option value="{{$rider->id}}" {{request('shoppr_id')==$rider->id?'selected':''}}>{{ $rider->name }}  (SHOPPR{{$rider->id}})</option>                                    @endforeach

                                                    </select>
                                                </div><br><br>
                                                <div class="col-4">
                                                    <input   class="form-control" name="from_date" value="{{request('from_date')}}"  type="date" />
                                                </div>
                                                <div class="col-4">
                                                    <input  class="form-control" name="to_date" value="{{request('to_date')}}"  type="date" />
                                                </div>
                                                <div class="col-4">
                                                    <button type="submit" name="save" class="btn btn-primary">Submit</button>
                                                    <a href="{{route('commission.list')}}" class="btn btn-danger">Reset Filters</a>

                                                    <a href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['type' => 'export'])) }}" class="btn btn-warning">Download</a>

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Shopper Name</th>
                                        <th>OrderId</th>
                                        <th>Delivery Charges</th>
                                        <th>Commission Amount</th>
                                        <th>Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($commission_transactions as $data)
                                        <tr>
                                            <td>{{$data->shoppr->name??''}}</td>
                                            <td>{{$data->refid??''}}</td>
                                            <td>{{$data->rider_delivery_charge??''}}</td>
                                            <td>{{$data->rider_commission??''}}</td>
                                            <td>{{$data->created_at??''}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Shopper Name</th>
                                        <th>OrderId</th>
                                        <th>Delivery Charge</th>
                                        <th>Commission Amount</th>
                                        <th>Date</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->

        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
@endsection

