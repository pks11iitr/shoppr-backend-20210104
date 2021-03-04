<table>
    <thead>
    <tr>
        <th>Customer Name</th>
        <th>Rider Name</th>
        <th>Order ID</th>
        <th>Total</th>
        <th>Service Charge</th>
        <th>Status</th>
        <th>Payment Status</th>
        <th>Payment Mode</th>
    </tr>
    </thead>
    <tbody>
    @foreach($datas as $data)
        <tr>
                <td>{{$data->customer->name??''}}</td>
                <td>{{$data->shoppr->name??''}}</td>
                <td>{{$data->refid}}</td>
                <td>{{$data->total}}</td>
                <td>{{$data->service_charge}}</td>
                <td>{{$data->status}}</td>
                <td>{{$data->payment_status}}</td>
                <td>{{$data->payment_mode}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
