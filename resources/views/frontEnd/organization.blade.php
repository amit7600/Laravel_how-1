@extends('layouts.app')
@section('title')
Organization
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


<style type="text/css">
.table a{
    text-decoration:none !important;
    color: rgba(40,53,147,.9);
    white-space: normal;
}
.footable.breakpoint > tbody > tr > td > span.footable-toggle{
    position: absolute;
    right: 25px;
    font-size: 25px;
    color: #000000;
}
.ui-menu .ui-menu-item .ui-state-active {
    padding-left: 0 !important;
}
ul#ui-id-1 {
    width: 260px !important;
}
#map{
    position: relative !important;
    z-index: 0 !important;
}
@media (max-width: 768px) {
    .property{
        padding-left: 30px !important;
    }
    #map{
        display: block !important;
        width: 100% !important;
    }
}
.morecontent span {
  display: none;

}
.morelink{
  color: #428bca;
}
#tbl-org-profile-contact_wrapper {
    overflow-x: scroll;
}

#tbl-org-profile-contact {
    width: 100% !important;
}

</style>

@section('content')
<div class="wrapper">
 
    <div id="content" class="container">
		<div class="row m-0">
        	<div class="col-md-8 pt-15 pb-15 pl-30">
               <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">
							<a href="">@if($organization->organization_logo_x)<img src="{{$organization->organization_logo_x}}" height="80">@endif {{$organization->organization_name}} @if($organization->organization_alternate_name!='')({{$organization->organization_alternate_name}})@endif
							</a>
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Religion:</b></span> 
							{{$organization->organization_religion}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Organization Type:</b></span> 
							{{$organization->organization_type}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Faith Tradition:</b></span> 
							{{$organization->organization_faith_tradition}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Denomination:</b></span> 
							{{$organization->organization_denomination}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Judicatory Body:</b></span> 
							{{$organization->organization_judicatory_body}}
                        </h4>
                        <h4>
                        @if(isset($organization->location))
                            @foreach($organization->location as $location)
							<span class="badge bg-red pl-0 organize_font"><b>Main Address</b></span> 
                                @if(isset($location->address))
                                    @foreach($location->address as $address)
                                    {{ $address->address_1 }}, {{ $address->address_city }}, {{ $address->address_state }}, {{ $address->address_zip_code }}
                                    @endforeach
                                @endif
                            @endforeach
                        @endif                        
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Facebook:</b></span> 
							{{$organization->organization_facebook}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Website:</b></span> 
							{{$organization->organization_url}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Internet Access:</b></span> 
							{{$organization->organization_internet_access}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Comments:</b></span> 
							{{$organization->organization_description}}
                        </h4>
                    </div>
                </div>
            </div>           
            
            <div class="col-md-4 property">
				<div class="pt-10 pb-10 pl-0 btn-download">
					<a href="/organization/{{$organization->organization_recordid}}/edit" class="btn btn-primary "><i class="fa fa-fw fa-edit"></i>Edit</a>
                    <button class="btn btn-danger delete-td" value="{{$organization->organization_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"><i class="fa fa-fw fa-remove"></i>Delete</button>
				</div>
				<div class="card">
					<div id="map" style="width:initial;margin-top: 0;height: 50vh;"></div>
					<div class="card-block">
						<div class="p-10">
						@if(isset($organization->location))
							@foreach($organization->location as $location)
							<h4>
								<span><i class="icon fas fa-building font-size-24 vertical-align-top  "></i>
                                    <a href="/facility/{{$location->location_recordid}}">{{$location->location_name}}</a>
								</span> 
							</h4>
							<h4>
								<span><i class="icon md-pin font-size-24 vertical-align-top  "></i>
									@if(isset($location->address))
										@foreach($location->address as $address)
										{{ $address->address_1 }}, {{ $address->address_city }}, {{ $address->address_state }}, {{ $address->address_zip_code }}
										@endforeach
									@endif
								</span> 
							</h4>
							@endforeach
						@endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 pt-15 pb-15 pl-30 pl-30">
               <div class="card">
                    <div class="card-block">
                        <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-org-profile-contact">
                            <thead>
                                <tr>
                                    <th class="default-active" style="visibility: hidden;">Action</th>
                                    <th class="default-inactive">ID</th>
                                    <th class="default-active">First Name</th>
                                    <th class="default-active">Middle Name</th>
                                    <th class="default-active">Last Name</th>
                                    <th class="default-active">Contact Type</th>
                                    <th class="default-active">Languages Spoken</th>
                                    <th class="default-active">Religious Title</th>
                                    <th class="default-active">Position Title</th> 
                                    <th class="default-active">Other Languages</th> 
                                    <th class="default-active">Pronouns</th> 
                                    <th class="default-inactive">Organization</th>                                  
                                    <th class="default-inactive">Mailing Address</th>   
                                    <th class="default-inactive">Cell Phone</th>  
                                    <th class="default-inactive">Office Phone</th>   
                                    <th class="default-inactive">Emergency Phone</th>  
                                    <th class="default-inactive">Office Fax</th>  
                                    <th class="default-inactive">Personal Email</th>  
                                    <th class="default-inactive">Work Email</th>                                 
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($contacts as $key => $contact)
                                <tr>
                                    <td>
                                        <a class="btn btn-primary open-td" href="/contact/{{$contact->contact_recordid}}" style="color: white;">Open</a>
                                    </td>
                                    <td>{{$contact->contact_recordid}}</td>
                                    <td>{{$contact->contact_first_name}}</td>
                                    <td>{{$contact->contact_middle_name}}</td>
                                    <td>{{$contact->contact_last_name}}</td>
                                    <td>{{$contact->contact_type}}</td>
                                    <td>{{$contact->contact_languages_spoken}}</td>
                                    <td>{{$contact->contact_religious_title}}</td>
                                    <td>{{$contact->contact_title}}</td>  
                                    <td>{{$contact->contact_other_languages}}</td>
                                    <td>{{$contact->contact_pronouns}}</td>
                                    <td>{{$contact->contact_organizations}}</td>                                  
                                    <td>{{$contact->contact_mailing_address}}</td>
                                    <td>{{$contact->contact_cell_phones}}</td>
                                    <td>{{$contact->contact_office_phones}}</td>
                                    <td>{{$contact->contact_emergency_phones}}</td>
                                    <td>{{$contact->contact_office_fax_phones}}</td>
                                    <td>{{$contact->contact_personal_email}}</td>
                                    <td>{{$contact->contact_email}}</td>
                                </tr>
                            @endforeach
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="/organization_delete_filter" method="POST" id="organization_delete_filter">
                            {!! Form::token() !!}
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Delete Organization</h4>
                            </div>
                            <div class="modal-body">
                                
                                <input type="hidden" id="organization_recordid" name="organization_recordid">
                                <h4>Are you sure to delete this organization?</h4>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger btn-delete">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var dataTable;
    $(document).ready(function() {
        dataTable = $('#tbl-org-profile-contact').DataTable({
            "scrollX": true,           
            dom: 'lBfrtip',
            buttons: [{
                extend: 'colvis',
                columns: ':gt(9)'
            }],
            columnDefs: [
                { targets: 'default-inactive', visible: false}
            ]
        });           
    })
    $(document).ready(function(){  
        setTimeout(function(){
        var locations = <?php print_r(json_encode($locations)) ?>;
        var organization = <?php print_r(json_encode($organization->organization_name)) ?>;
        var maplocation = <?php print_r(json_encode($map)) ?>;
        console.log(locations);

        if(maplocation.active == 1){
            avglat = maplocation.lat;
            avglng = maplocation.long;
            zoom = maplocation.zoom_profile;
        }
        else
        {
            avglat = 40.730981;
            avglng = -73.998107;
            zoom = 12;
        }

        latitude = locations[0].location_latitude;
        longitude = locations[0].location_longitude;

        if(latitude == null){
            latitude = avglat;
            longitude = avglng;
        }

        
        var mymap = new GMaps({
            el: '#map',
            lat: latitude,
            lng: longitude,
            zoom: zoom
        });


        $.each( locations, function(index, value ){
                // console.log(locations);
                var name = value.organization==null?'':value.organization.organization_name;
                var serviceid = value.services.length == 0?'':value.services[0].service_recordid;
                var service_name = value.services.length == 0?'':value.services[0].service_name;

                var content = "";
                for(i = 0; i < value.services.length; i ++){
                    content +=  '<a href="/service/'+value.services[i].service_recordid+'" style="color:#428bca;font-weight:500;font-size:14px;">'+value.services[i].service_name+'</a><br>';
                }
                content += '<p>'+name+'</p>';

                if(value.location_latitude){
                    mymap.addMarker({

                        lat: value.location_latitude,
                        lng: value.location_longitude,
                        title: value.city,
                            
                        infoWindow: {
                            maxWidth: 250,
                            content: (content)
                        }
                    });
                }
            });
        }, 2000)
    });


    $(document).ready(function() {
        var showChar = 250;
        var ellipsestext = "...";
        var moretext = "More";
        var lesstext = "Less";
        $('.more').each(function() {
        var content = $(this).html();

        if(content.length > showChar) {

            var c = content.substr(0, showChar);
            var h = content.substr(showChar, content.length - showChar);

            var html = c + '<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';

            $(this).html(html);
        }
    });

    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#organization_recordid').val(value);
    });

    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
        });
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$map->api_key}}&libraries=places&callback=initMap"
  async defer>
</script>
@endsection


