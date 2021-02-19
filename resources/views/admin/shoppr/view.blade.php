@extends('layouts.admin')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Shoppr </h1>
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
                         <a href="{{route('shoppr.create')}}" class="btn btn-primary">Add Shoppr</a> </div>
         <div class="col-9">

        <form class="form-validate form-horizontal"  method="get" action="" enctype="multipart/form-data">
              <div class="row">
					      <div class="col-4">
                           <input  id="fullname"  class="form-control" name="search" placeholder=" search name" value="{{request('search')}}"  type="text" />
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
                    <th>Location</th>
                    <th>Latitude</th>
                   <th>Langitude</th>
                   <th>Image</th>
                    <th>Isactive</th>
                    <th>Wallet Balance</th>
                   <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
				@foreach($datas as $data)
                  <tr>
					  <td>{{$data->name}}</td>
					  <td>{{$data->mobile}}</td>
					  <td>{{$data->location}}</td>
					  <td>{{$data->lat}}</td>
					  <td>{{$data->lang}}</td>
                      <td><img src="{{$data->image}}" height="80px" width="80px"/></td>
                       <td>
                        @if($data->isactive==1){{'Yes'}}
                             @else{{'No'}}
                             @endif
                        </td>
                      <td>
                          {{\App\Models\ShopprWallet::balance($data->id)}}
                      </td>
                      <td><a href="{{route('shoppr.edit',['id'=>$data->id])}}" class="btn btn-success">Edit</a></td>
                 </tr>
                 @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                      <th>Name</th>
                      <th>Mobile</th>
                      <th>Location</th>
                      <th>Latitude</th>
                      <th>Langitude</th>
                      <th>Image</th>
                      <th>Isactive</th>
                      <th>Status</th>
                      <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              {{$datas->appends(request()->query())->links() }}
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

