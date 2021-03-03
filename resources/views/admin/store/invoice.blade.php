<table>
    <thead>
    <tr>
        <th>Store Name</th>
        <th>Store Type</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Open Timing</th>
        <th>Isactive</th>
        <th>IsSale</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $data)
        <tr>
            <td>{{$data->store_name}}</td>
            <td>{{$data->store_type}}</td>
            <td>{{$data->mobile}}</td>
            <td>{{$data->email}}</td>
            <td>{{$data->opening_time}}</td>
            <td>@if($data->isactive==1){{'Yes'}}
                @else{{'No'}}
                @endif
            </td>
            <td>
                @if($data->is_sale==1){{'Yes'}}
                @else{{'No'}}
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
