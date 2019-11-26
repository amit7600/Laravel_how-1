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
                <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-organization">
                    <thead>
                        <tr>
                            <th class="default-inactive">Organization</th>
                            <th class="default-active">Address</th>
                            <th class="default-active">#Congregations</th>
                            <th class="default-inactive">Status</th>
                            <th class="default-inactive">Call</th>                          
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facilities as $facility)
                        <tr>
                            @php $organization = $facility->organization['organization_name']; @endphp
                            <td>{{$organization}}</td>
                            <td>
                                @if(isset($facility->address[0]))
                                    @php $address_info = $facility->address[0]; @endphp
                                    {{$address_info['address_1']}}, {{$address_info['address_city']}}, {{$address_info['address_state']}}, {{$address_info['address_zip_code']}}
                                @endif
                            </td>
                            <td>{{$facility->location_congregation}}</td>
                            <td>{{$facility->location_building_status}}</td>
                            <td>{{$facility->location_call}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h3>{!! $layout->footer_pdf !!}</h3>
        </div>
    </div>
</div>
