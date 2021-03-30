<table>
    <thead>
    <tr>
        <th>Shopper Name</th>
        <th>Customer Name</th>
        <th>Shopper Chat</th>
        <th>Customer Chat</th>
        <th>Date & Time</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $data)
        <tr>
                <td>{{$data->id??''}}</td>
                @if($data->diretion==0)
                    <td> </td>
                    <td>{{$data->chat->customer->name??''}}</td>
                @else
                    <td>{{$data->chat->shoppr->name??''}}</td>
                    <td> </td>
                @endif
                @if($data->diretion==0)
                    <td> </td>
                    <td>{{$data->message??''}}</td>
                @else
                    <td>{{$data->message??''}}</td>
                    <td> </td>
                @endif
        </tr>
    @endforeach
    </tbody>
</table>
