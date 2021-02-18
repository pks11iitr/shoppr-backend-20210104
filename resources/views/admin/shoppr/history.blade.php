@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Transaction </h1>
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
                                    <div class="col-3">
                                        {{--<a href="{{route('shoppr.create')}}" class="btn btn-primary">Add Shoppr</a> </div>--}}
                                    <div class="col-9">

                                       {{-- <form class="form-validate form-horizontal"  method="get" action="" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-4">
                                                    <input  id="fullname"  class="form-control" name="search" placeholder=" search title" value="{{request('search')}}"  type="text" />
                                                </div>
                                                <div class="col-4">
                                                    <select id="ordertype" name="ordertype" class="form-control" >
                                                        <option value="" {{ request('ordertype')==''?'selected':''}}>Please Select</option>
                                                        <option value="DESC" {{ request('ordertype')=='DESC'?'selected':''}}>DESC</option>
                                                        <option value="ASC" {{ request('ordertype')=='ASC'?'selected':''}}>ASC</option>
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <button type="submit" name="save" class="btn btn-primary">Submit</button>
                                                </div>
                                            </div>
                                        </form>--}}
                                    </div>

                                </div>
                            </div>

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Refid</th>
                                        <th>Amount</th>
                                        <th>Orderid</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Description</th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($datas as $data)
                                        <tr>
                                            <td>{{$data->shoppr->name}}</td>
                                            <td>{{$data->refid}}</td>
                                            <td>{{$data->amount}}</td>
                                            <td>{{$data->order_id}}</td>
                                            <td>{{$data->created_at}}</td>
                                            <td>{{$data->type}}</td>
                                            <td>{{$data->description}}</td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        {{$datas->appends(request()->query())->links() }}
                        <!-- /.card-body -->
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            </div>
        </section>
    </div>
    <!-- ./wrapper -->
@endsection

