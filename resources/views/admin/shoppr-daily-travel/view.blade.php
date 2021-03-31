@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Shoppr Daily Travel </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Shoppr Daily Travel</li>
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
                                        <a href="{{route('dailytravel.create')}}" class="btn btn-primary">Add Shoppr Daily Travel</a>
                                    </div><br><br>

                                    <div class="col-12">

                                        <form class="form-validate form-horizontal"  method="get" action="" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-4">

                                                    <select id="shoppr_id" name="shoppr_id" class="form-control" >
                                                        <option value="" {{ request('shoppr_id')==''?'selected':''}}>Select Shoppr</option>
                                                        @foreach($riders as $rider)
                                                            <option value="{{$rider->id}}" {{request('shoppr_id')==$rider->id?'selected':''}}>{{ $rider->name }}  (SHOPPR{{$rider->id}})</option>                                    @endforeach

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
                                                    <a href="{{route('dailytravel.list')}}" class="btn btn-danger">Reset Filters</a>

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <h4>Total KM:{{$total_km}}/ Total Commission: {{$total_commission}}</h4>
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Rider Name</th>
                                        <th>Date</th>
                                        <th>Km</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($dailytravels as $dailytravel)
                                        <tr>
                                            <td>{{$dailytravel->shoppr->name??''}}</td>

                                            <td>{{$dailytravel->date}}</td>
                                            <td>{{$dailytravel->km}}</td>
                                            <td><a href="{{route('dailytravel.edit',['id'=>$dailytravel->id])}}" class="btn btn-success">Edit</a>&nbsp;

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        {{$dailytravels->appends(request()->query())->links()}}
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

