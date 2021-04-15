@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Merchant Application</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Merchant Application</li>
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
                                <div class="row">
                                        <h3 class="card-title">Merchant Application</h3>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Customer Name</th>
                                        <th>Store</th>
{{--                                        <th>Store Type</th>--}}
                                        <th>Image</th>
                                        <th>About Store</th>
                                        <th>Opening Time </th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Address</th>
                                        <th>IsActive</th>
                                        <th>Is Sale</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($merchants as $merchant)
                                        <tr>
                                            <td>{{$merchant->customer->name??''}}</td>
                                            <td><b>Name :-</b>
                                                {{$merchant->store_name}}<br>
                                                <b>Type :-</b> {{$merchant->store_type}}
                                            </td>
{{--                                            <td>{{$merchant->store_type}}</td>--}}
                                            <td><img src="{{$merchant->image}}" height="80px" width="80px"/></td>
                                            <td>{{$merchant->about_store}}</td>
                                            <td>{{$merchant->opening_time}}</td>
                                            <td>{{$merchant->email}}</td>
                                            <td>{{$merchant->mobile}}</td>
                                            <td>{{$merchant->address}}</td>
                                            <td>
                                                @if($merchant->isactive==1){{'Yes'}}
                                                @else{{'No'}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($merchant->is_sale==1){{'Yes'}}
                                                @else{{'No'}}
                                                @endif
                                            </td>
                                            &nbsp;

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        {{$merchants->appends(request()->query())->links()}}
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

