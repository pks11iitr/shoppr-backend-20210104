<table>
    <thead>
    <tr>
        <th>Chat ID</th>
        <th>Shopper Name</th>
        <th>Customer Name</th>
        <th>Shopper Chat</th>
        <th>Customer Chat</th>
        <th>Date Time</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $data)
        <tr>
            <td>{{$data->id??''}}</td>
            @if($data->direction==0)
                <td></td>
                <td>{{$data->chat->customer->name??''}}</td>
            @else
                <td>{{$data->chat->shoppr->name??''}}</td>
                <td></td>
            @endif
            @if($data->direction==0)
                <td></td>
                <td>@if(in_array($data->type, ['text','address-request', 'rating','address', 'total', 'add-money', 'recharge', 'payment', 'paid', 'track']))
                        {{$data->message??''}}
                @elseif($data->type=='product')
                        {{$data->file_path}}/ {{$data->message}}: Rs.{{$data->price}}/{{$data->quantity}} Status:{{$data->status}}
                @elseif($data->type=='audio')
                                Audio Message: {{$data->file_path??''}}
                @elseif($data->type=='image')
                        {{$data->file_path??''}}
                    @endif</td>
            @else
                <td>@if(in_array($data->type, ['text','address-request', 'rating','address', 'total', 'add-money', 'recharge', 'payment', 'paid', 'track']))
                        {{$data->message??''}}
                    @elseif($data->type=='product')
                        {{$data->file_path}}/ {{$data->message}}: Rs.{{$data->price}}/{{$data->quantity}} Status:{{$data->status}}
                    @elseif($data->type=='audio')
                        Audio Message: {{$data->file_path??''}}
                    @elseif($data->type=='image')
                        {{$data->file_path??''}}
                    @endif</td>
                <td></td>
            @endif
            <td>{{date('d-m-Y h:a', strtotime($data->getRawOriginal('created_at')))}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
