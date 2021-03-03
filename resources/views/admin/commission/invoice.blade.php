<table>
    <thead>
    <tr>
        <th>Shopper Name</th>
        <th>OrderId</th>
        <th>Commission Amount</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($historyobj as $commission)
        <tr>
            <td>{{$commission->shoppr->name??''}}</td>
            <td>{{$commission->refid??''}}</td>
            <td>{{$commission->rider_commission??''}}</td>
            <td>{{$commission->created_at??''}}</td>
        </tr>

    @endforeach
    </tbody>
</table>
