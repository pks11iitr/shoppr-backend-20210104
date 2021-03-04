<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Isactive</th>
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->id??'' }}</td>
            <td>{{ $customer->name??'' }}</td>
            <td>{{ $customer->mobile??'' }}</td>
            <td>{{ $customer->email??'' }}</td>
            <td>
                @if($customer->status==1)
                    <b style="color:green">Yes</b>
                @else
                    <b style="color:red">No</b>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
