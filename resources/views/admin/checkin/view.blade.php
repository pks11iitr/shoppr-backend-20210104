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
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">DataTables</li>
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

                                                    <select id="shoppr_id" name="shoppr_id" class="form-control" >
                                                        <option value="" {{ request('shoppr_id')==''?'selected':''}}>Select Rider</option>
                                                        @foreach($riders as $rider)
                                                            <option value="{{$rider->id}}" {{request('shoppr_id')==$rider->id?'selected':''}}>{{ $rider->name }}</option>                                    @endforeach

                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <input   class="form-control" name="fromdate" placeholder=" search name" value="{{request('fromdate')}}"  type="date" />
                                                </div>
                                                <div class="col-4">
                                                    <input  class="form-control" name="todate" placeholder=" search name" value="{{request('todate')}}"  type="date" />
                                                </div><br><br>
                                                <div class="col-4">
                                                    <button type="submit" name="save" class="btn btn-primary">Submit</button>
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
                                        <th>Rider Name</th>
                                        <th>CheckIn</th>
                                        <th>CheckOut</th>
                                        <th>Date & Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($checkins as $checkin)
                                        <tr>
                                            <td>{{$checkin->shoppr->name??''}}</td>
                                            <td>{{$checkin->value??'name'}}</td>
                                            <td>{{$checkin->value??'name'}}</td>
                                            <td>{{$checkin->created_at}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        {{$checkins->appends(request()->query())->links()}}
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

