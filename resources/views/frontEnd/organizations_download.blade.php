<style>
table, th, td {
  border: 1px solid black;
}
thead{
    background: grey;
}
</style>
<div class="wrapper">
    <div id="content" class="container">
        <div class="container-fluid p-0" style="margin-right: 0">
            <h3>{!! $layout->header_pdf !!}</h3>
            <div class="col-md-8 pt-15 pr-0">
                <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-organization" border='1'>
                    <thead>
                        <tr>
                            <th class="default-inactive">Id</th>
                            <th class="default-active">Name</th>
                            <th class="default-active">Religion</th>
                            <th class="default-inactive">Facility Address</th>                          
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($organizations as $organization)
                        <tr>
                            <td>{{$organization->organization_recordid}}</td>
                            <td>{{$organization->organization_name}}</td>
                            <td>{{$organization->organization_religion}}</td>
                            @if($organization->organization_locations!='')
                                @foreach($organization->location as $location)
                                    <td>
                                        @if($location->location_address!='')
                                            @foreach($location->address as $address)
                                                {{ $address->address_1 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                            @endforeach
                                        @endif
                                    </td>
                                @endforeach
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h3>{!! $layout->footer_pdf !!}</h3>
        </div>
    </div>
</div>
