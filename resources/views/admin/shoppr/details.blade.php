@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Shoppr Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active"><a href="">Shoppr</a></li>
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
                                <h3 class="card-title">Document Details</h3>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <td>Pan Card</td>
                                        <td>
                                            <a href="{{$shoppr->pan_card}}">
                                                <button type="button" target="_blank" class="btn btn-warning">View</button>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Front Aadhaar Card</td>
                                        <td>
                                            <a href="{{$shoppr->front_aadhaar_card}}">
                                                <button type="button" target="_blank" class="btn btn-warning">View</button>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Back Aadhaar Card</td>
                                        <td>
                                            <a href="{{$shoppr->back_aadhaar_card}}">
                                                <button type="button" target="_blank" class="btn btn-warning">View</button>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Front DL</td>
                                        <td>
                                            <a href="{{$shoppr->front_dl_no}}">
                                                <button type="button" target="_blank" class="btn btn-warning">View</button>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Back DL</td>
                                        <td>
                                            <a href="{{$shoppr->back_dl_no}}">
                                                <button type="button" target="_blank" class="btn btn-warning">View</button>
                                            </a>
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
                                        <th>Account Details</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>Holder Name</td>
                                        <td>{{$shoppr->account_holder}}</td>
                                    </tr>
                                    <tr>
                                        <td>Bank Name</td>
                                        <td>{{$shoppr->bank_name}}</td>
                                    </tr>
                                    <tr>
                                        <td>IFSC Code</td>
                                        <td>{{$shoppr->ifsc_code}}</td>
                                    </tr>
                                    <tr>
                                        <td>Account No</td>
                                        <td>{{$shoppr->account_no}}</td>
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
    </div>
    <!-- ./wrapper -->
@endsection
