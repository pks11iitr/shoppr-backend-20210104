<table>
    <thead>
    <tr>
        <th>Rider Name</th>
        <th>CheckIn</th>
        <th>CheckOut</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($checkins as $key=>$value)
        @php
            $shoppr=explode('**',$key)[0];
            $date=explode('**', $key)[1];
        @endphp
        <tr>
            <td>{{$shoppr??''}}</td>

            <td>@if(isset($value['checkin'])){{($value['checkin']['address']??'').' at '.($value['checkin']['time']??'')}}@endif</td>
            <td>@if(isset($value['checkout'])){{($value['checkout']['address']??'').' at '.($value['checkout']['time']??'')}}@endif</td>

            <td>{{$date??''}}</td>

        </tr>

    @endforeach
    </tbody>
</table>
