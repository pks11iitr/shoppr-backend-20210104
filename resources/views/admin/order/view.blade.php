@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Orders</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Orders</li>
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
                                    <div class="col-12">

                                        <form class="form-validate form-horizontal"  method="get" action="" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-4">
                                                    <input  class="form-control" name="search" placeholder=" search customer name/order id" value="{{request('search')}}"  type="text" />
                                                </div>
                                                <div class="col-4">
                                                    <select id="ordertype" name="ordertype" class="form-control" >
                                                        <option value="">Please Select Order</option>

                                                        <option value="DESC" {{ request('ordertype')=='DESC'?'selected':''}}>DESC</option>
                                                        <option value="ASC" {{ request('ordertype')=='ASC'?'selected':''}}>ASC</option>
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <select id="status" name="status" class="form-control" >
                                                        <option value="">Please Select Status</option>

                                                        <option value="Pending" {{ request('status')=='Pending'?'selected':''}}>Pending</option>
                                                        <option value="Confirmed" {{ request('status')==='Confirmed'?'selected':''}}>Confirmed</option>
                                                        <option value="Delivered" {{ request('status')=='Delivered'?'selected':''}}>Delivered</option>
                                                        <option value="Cancelled" {{ request('status')=='Cancelled'?'selected':''}}>Cancelled</option>

                                                    </select>
                                                </div><br><br>
                                                <div class="col-4">
                                                    <input   class="form-control" name="fromdate" placeholder=" search name" value="{{request('fromdate')}}"  type="date" />
                                                </div>
                                                <div class="col-4">
                                                    <input  class="form-control" name="todate" placeholder=" search name" value="{{request('todate')}}"  type="date" />
                                                </div>
                                                <div class="col-4">

                                                    <select id="shoppr_id" name="shoppr_id" class="form-control" >
                                                        <option value="" {{ request('shoppr_id')==''?'selected':''}}>Select Shoppr</option>
                                                        @foreach($riders as $rider)
                                                            <option value="{{$rider->id}}" {{request('shoppr_id')==$rider->id?'selected':''}}>{{ $rider->name }} (SHOPPR{{$rider->id}})</option>                                    @endforeach

                                                    </select>
                                                </div><br><br>
                                                <div class="col-4">
                                                    <button type="submit" name="save" class="btn btn-primary">Submit</button>
                                                    <a href="{{route('order.list')}}" class="btn btn-danger">Reset Filters</a>
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
                                        <th>Customer Name</th>
                                        <th>Rider Name</th>
                                        <th>Order ID</th>
                                        <th>Total</th>
                                        <th>Service Charge</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                        <th>Payment Mode</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{$order->customer->name??''}}</td>
                                            <td>{{$order->shoppr->name??''}}</td>
                                            <td>{{$order->refid}}</td>
                                            <td>{{$order->total}}</td>
                                            <td>{{$order->service_charge}}</td>
                                            <td>{{$order->status}}</td>
                                            <td>{{$order->payment_status}}</td>
                                            <td>{{$order->payment_mode}}</td>
                                            <td>
                                                <a href="{{route('order.details',['id'=>$order->id])}}" class="btn btn-info">Details</a><br><br>
                                                <a href="{{route('order.chats.details',['id'=>$order->chat_id])}}" class="btn btn-success">Chats</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        {{$orders->appends(request()->query())->links()}}
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
    </div>
    <!-- ./wrapper -->
@endsection

