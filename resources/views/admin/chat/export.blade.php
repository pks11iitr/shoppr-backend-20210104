<table>
    <thead>
    <tr>
        <th>Chat ID</th>
        <th>Customer Name</th>
        <th>Rider Name</th>
        <th>Start Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $data)
        <tr>
                <td>{{$data->id??''}}</td>
                <td>{{$data->customer->name??''}}</td>
                <td>{{$data->shoppr->name??''}}</td>
                <td>{{$data->created_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
