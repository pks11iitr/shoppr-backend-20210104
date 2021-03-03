@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Order Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('order.list')}}">Order</a></li>
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
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Order Details</h3>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td>Order ID</td>
                                        <td>{{$order->refid}}</td>
                                    </tr>
                                    <tr>
                                        <td>Date & Time</td>
                                        <td>{{$order->created_at}}</td>
                                    </tr>
                                    <tr>
                                        <td>Shoppr Name</td>
                                        <td>{{$order->shoppr->name??''}}
{{--                                            <a href="{{route('order.details',['id'=>$order->id])}}" class="open-RiderChange btn btn-success" data-toggle="modal" data-target="#exampleModal" data-id="{{$order->id}}">Change Shoppr</a>--}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Service Charge</td>
                                        <td>{{$order->service_charge}}</td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td>{{$order->total}}</td>
                                    </tr>
                                    <tr>
                                        <td>Payment Status</td>
                                        <td>{{$order->payment_status}}
                                            @if(in_array($order->payment_status, ['Pending']))
                                                <a href="{{route('payment.status.change', ['id'=>$order->id,'status'=>'Paid'])}}" name='status' class="btn btn-primary">Mark As Paid</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Payment Mode</td>
                                        <td>{{$order->payment_mode}}</td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Schedule</td>
                                        <td>{{$order->delivery_at}}</td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>{{$order->status}}<br><br>
                                            @if(in_array($order->status, ['Confirmed']))
                                                <a href="{{route('order.status.change', ['id'=>$order->id,'status'=>'Delivered'])}}" name='status' class="btn btn-primary">Mark Delivered</a>
                                            @endif
                                            @if(in_array($order->status, ['Confirmed']))
                                                <a href="{{route('order.status.change', ['id'=>$order->id,'status'=>'Cancelled'])}}" name='status' class="btn btn-primary">Mark Cancelled</a>
                                            @endif
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Message</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->details as $detail)
                                        <tr>
                                            <td>{{$detail->message}}</td>
                                            <td>{{$detail->price}}</td>
                                            <td>{{$detail->quantity}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Customer Details</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Name</td>
                                        <td>{{$order->customer->name??''}}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{$order->customer->email??''}}</td>
                                    </tr>
                                    <tr>
                                        <td>Mobile</td>
                                        <td>{{$order->customer->mobile??''}}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>{{$order->deliveryaddress[0]->message??''}}</td>
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Change Rider</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form role="form" method="post" enctype="multipart/form-data"  action="{{route('rider.change',['id'=>$order->id])}}">
                            @csrf
                            <input type="hidden" name="orderid" class="form-control" id="orderid">
                            <div class="form-group">
                                <label for="exampleInputtitle">Rider Name</label>
                                <select name="riderid" class="form-control" id="riderid" placeholder="" >
                                    @foreach($riders as $rider)
                                        <option value="{{$rider->id}}"
                                            {{$order->shoppr_id==$rider->id?'selected':''}}>{{$rider->name}}  (SHOPPR{{$rider->id}})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button class="btn btn-primary" type="submit">Change</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>


    </div>
    <!-- ./wrapper -->
@endsection
