<table>
    <thead>
    <tr>
        <th>B/L</th>
        <th>Shipper</th>
        <th>Consignee</th>
        <th>Consignee</th>
        <th>Consignee</th>
    </tr>
    </thead>
    <tbody>
    @foreach( $shipments as $shipment)
        <tr>
            <td>{{ $shipment->shipment_id}}</td>
            <td>{{ $shipment->container_id}}</td>
            <td>{{ $shipment->shipper}}</td>
            <td>{{ $shipment->consignee}}</td>
            
        </tr>
    @endforeach
    </tbody>
</table>