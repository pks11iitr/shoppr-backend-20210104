@extends('layouts.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <a href="{{url()->current()}}?type=export">Download</a>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
{{--        <section class="content-header">--}}
{{--            <div class="container-fluid">--}}
{{--                <div class="row mb-2">--}}
{{--                    <div class="col-sm-6">--}}
{{--                        <h1>Chats</h1>--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-6">--}}
{{--                        <ol class="breadcrumb float-sm-right">--}}
{{--                            <li class="breadcrumb-item"><a href="#">Home</a></li>--}}
{{--                            <li class="breadcrumb-item active">DataTables</li>--}}
{{--                        </ol>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div><!-- /.container-fluid -->--}}
{{--        </section>--}}

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body" style="height:500px">
{{--                                <div class="card direct-chat direct-chat-primary">--}}
{{--                                    <div class="card-header">--}}
{{--                                        <h3 class="card-title">Direct Chat</h3>--}}

{{--                                        <div class="card-tools">--}}
{{--                                            <span data-toggle="tooltip" title="3 New Messages" class="badge badge-primary">3</span>--}}
{{--                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">--}}
{{--                                                <i class="fas fa-minus"></i>--}}
{{--                                            </button>--}}
{{--                                            <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Contacts"--}}
{{--                                                    data-widget="chat-pane-toggle">--}}
{{--                                                <i class="fas fa-comments"></i>--}}
{{--                                            </button>--}}
{{--                                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>--}}
{{--                                            </button>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <!-- /.card-header -->

                                        <!-- Conversations are loaded here -->
                                        <div class="direct-chat-messages" style="height:450px">
                                            @foreach($chats as $chat)
                                                @if($chat->direction==0)
                                            <div class="direct-chat-msg">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-left">{{$chat->chat->customer->name??''}}</span>
                                                    <span class="direct-chat-timestamp float-right">
                                                        {{date('d-m-Y h:i a',strtotime($chat->getRawOriginal('created_at')))}}
                                                    </span>
                                                </div>
                                                <!-- /.direct-chat-infos -->
                                                <img class="direct-chat-img" src="{{$chat->chat->customer->image??''}}" alt="message user image">
                                                <!-- /.direct-chat-img -->
                                                <div class="direct-chat-text">
                                                    @if(in_array($chat->type, ['text','address-request', 'rating','address', 'total', 'add-money', 'recharge', 'payment', 'paid', 'track']))
                                                        {{$chat->message}}
                                                    @elseif($chat->type=='product')
                                                        <image src="{{$chat->file_path}}" height="100" width="100">
                                                            <span>{{$chat->message}}: Rs.{{$chat->price}}/{{$chat->quantity}} Status:{{$chat->status}}</span>
                                                    @elseif($chat->type=='audio')
                                                                Audio Message: <a href="{{$chat->file_path}}">View</a>
                                                    @elseif($chat->type=='image')
                                                        <image src="{{$chat->file_path}}">
                                                    @endif
                                                </div>
                                                <!-- /.direct-chat-text -->
                                            </div>
                                                @else
                                            <div class="direct-chat-msg right">
                                                <div class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-right">{{$chat->chat->shoppr->name??''}}</span>
                                                    <span class="direct-chat-timestamp float-left">
                                                        {{date('d-m-Y h:ia',strtotime($chat->getRawOriginal('created_at')))}}
                                                    </span>
                                                </div>
                                                <!-- /.direct-chat-infos -->
                                                <img class="direct-chat-img" src="{{$chat->chat->shoppr->image??''}}" alt="message user image">
                                                <!-- /.direct-chat-img -->
                                                <div class="direct-chat-text">
                                                    @if(in_array($chat->type, ['text','address-request', 'rating','address', 'total', 'add-money', 'recharge', 'payment', 'paid', 'track']))
                                                    {{$chat->message}}
                                                    @elseif($chat->type=='product')
                                                        <image src="{{$chat->file_path}}" height="100" width="100">
                                                            <span>{{$chat->message}}: Rs.{{$chat->price}}/{{$chat->quantity}} Status:{{$chat->status}}</span>
                                                            @elseif($chat->type=='audio')
                                                                Audio Message: <a href="{{$chat->file_path}}">View</a>
                                                            @elseif($chat->type=='image')
                                                                <image src="{{$chat->file_path}}">
                                                    @endif
                                                </div>
                                                <!-- /.direct-chat-text -->
                                            </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <!--/.direct-chat-messages-->

                                    <!-- /.card-body -->
                                    <!-- /.card-footer-->
                                </div>
                                <!--/.direct-chat -->
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    {{--        <script>--}}
    {{--            // Here the value is stored in new variable x--}}

    {{--            function verifySubmit(){--}}

    {{--                var compid = $("#compid").val();--}}

    {{--                var des = $("#message").val();--}}

    {{--                $.post('{{route('complain.message')}}', {compid:compid, _token:'{{csrf_token()}}', des:des}, function(data){--}}
    {{--                    alert('Message has been sent successfully')--}}
    {{--                })--}}

    {{--                window.location.reload();--}}
    {{--                // console.log(data);--}}

    {{--            }--}}
    {{--        </script>--}}

    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
@endsection

