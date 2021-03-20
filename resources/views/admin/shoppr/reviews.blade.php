@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Shoppr Reviews</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">Shoppr</li>
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
{{--                            <div class="card-header">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-3">--}}
{{--                                        <a href="{{route('shoppr.create')}}" class="btn btn-primary">Add Shoppr</a>--}}
{{--                                        <a href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['type' => 'export'])) }}" class="btn btn-warning">Download</a>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-9">--}}

{{--                                        <form class="form-validate form-horizontal"  method="get" action="" enctype="multipart/form-data">--}}
{{--                                            <div class="row">--}}
{{--                                                <div class="col-4">--}}
{{--                                                    <input  id="fullname"  class="form-control" name="search" placeholder=" search name" value="{{request('search')}}"  type="text" />--}}
{{--                                                </div>--}}
{{--                                                <div class="col-4">--}}
{{--                                                    <select id="ordertype" name="ordertype" class="form-control" >--}}
{{--                                                        <option value="" {{ request('ordertype')==''?'selected':''}}>Please Select</option>--}}
{{--                                                        <option value="DESC" {{ request('ordertype')=='DESC'?'selected':''}}>DESC</option>--}}
{{--                                                        <option value="ASC" {{ request('ordertype')=='ASC'?'selected':''}}>ASC</option>--}}
{{--                                                    </select>--}}
{{--                                                </div>--}}
{{--                                                <div class="col-4">--}}
{{--                                                    <button type="submit" name="save" class="btn btn-primary">Submit</button>--}}
{{--                                                    <a href="{{route('shoppr.list')}}" class="btn btn-danger">Reset Filters</a>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </form>--}}
{{--                                    </div>--}}

{{--                                </div>--}}
{{--                            </div>--}}

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Rating</th>
                                        <th>Review</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($reviews as $review)
                                        <tr>
                                            <td>{{date('d/m/Y', strtotime($review->getRawOriginal('created_at')))}}</td>
                                            <td>{{$review->quantity}}</td>
                                            <td>{{$review->message}}</td>
                                            <td><a href="{{route('order.details', ['id'=>$review->order_id])}}">View Order</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            </div>
                        {{$reviews->appends(request()->query())->links() }}
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
