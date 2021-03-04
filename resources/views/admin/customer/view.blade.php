@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Customer</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Customer</li>
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
                                                    <input  class="form-control" name="search" placeholder=" search name" value="{{request('search')}}"  type="text" />
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

                                                        <option value="1" {{ request('status')=='1'?'selected':''}}>Active</option>
                                                        <option value="0" {{ request('status')=='0'?'selected':''}}>Inactive</option>

                                                    </select>
                                                </div><br><br>
                                                <div class="col-4">
                                                    <input   class="form-control" name="fromdate" placeholder=" search name" value="{{request('fromdate')}}"  type="date" />
                                                </div>
                                                <div class="col-4">
                                                    <input  class="form-control" name="todate" placeholder=" search name" value="{{request('todate')}}"  type="date" />
                                                </div>
                                                <div class="col-4">
                                                    <button type="submit" name="save" class="btn btn-primary">Submit</button>
                                                    <a href="{{route('customer.list')}}" class="btn btn-danger">Reset Filters</a>
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
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Image</th>
                                        <th>Isactive</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($customers as $customer)
                                        <tr>
                                            <td>{{$customer->name}}</td>
                                            <td>{{$customer->mobile}}</td>
                                            <td>{{$customer->email}}</td>
                                            <td>
                                                <img src="{{$customer->image}}" height="80px" width="80px"/>
                                            </td>

                                            <td>
                                                @if($customer->status==1)
                                                    <b style="color:green">Yes</b>
                                                @else
                                                    <b style="color:red">No</b>
                                                @endif
                                            </td>
                                            <td><a href="{{route('customer.edit',['id'=>$customer->id])}}" class="btn btn-success">Edit</a>&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{$customers->appends(request()->query())->links()}}
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

