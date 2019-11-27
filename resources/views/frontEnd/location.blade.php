@extends('layouts.app')
@section('title')
Facility
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
button.dt-button {
    display: none !important;
}
div#tbl-location-profile-history_filter {
    margin-left: 10px;
}
table#tbl-location-profile-history {
    width: 100% !important;
    display: block;
    border-bottom: 0px;
}
#tbl-location-profile-history_wrapper {
    overflow-x: scroll;
}

</style>

@section('content')
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="content" class="container">
		<div class="row m-0">
        	<div class="col-md-8 pt-15 pb-15 pl-30">
               <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">
							<a href="">{{$facility->location_name}}
							</a>
                        </h4>
                        <h4>
                            <span class="badge bg-red pl-0 organize_font"><b>Organization:</b></span> 
                            <a href="/organization/{{$organization_id}}">{{$organization_name}}</a>	
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Facility Type:</b></span> 
							{{$facility->location_type}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Address:</b></span> 
							{{$address_name}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b># Congregations at this facility:</b></span> 
							{{$facility->location_congregation}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Building Status:</b></span> 
							{{$facility->location_building_status}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Call in Emergency:</b></span> 
							{{$facility->location_call}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Comments:</b></span> 
							{{$facility->location_description}}
                        </h4>
                    </div>
                </div>
            </div>  
            <div class="col-md-4 property">
				<div class="pt-10 pb-10 pl-0 btn-download">
					<a href="/facility/{{$facility->location_recordid}}/edit" class="btn btn-primary "><i class="fa fa-fw fa-edit"></i>Edit</a>
                    <button class="btn btn-danger delete-td" value="{{$facility->location_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"><i class="fa fa-fw fa-remove"></i>Delete</button>
				</div>
				<div class="card">
					<div id="map" style="width:initial;margin-top: 0;height: 50vh;"></div>
                </div>
            </div> 
            <div class="col-md-8 pt-15 pb-15 pl-30 pl-30">
               <div class="card">
                    <div class="card-block">  
                        <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-location-profile-history">
                            <thead>
                                <tr> 
                                    <th class="default-inactive">ID</th>                                  
                                    <th class="default-active">Timestamp</th>
                                    <th class="default-active">Field Changed</th>
                                    <th class="default-active">Previous Field Contents</th>
                                    <th class="default-active">New Field Contents</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($facility_history_list as $key => $facility_history)
                                <tr>
                                    <td>{{$facility_history->id}}</td>
                                    <td>{{$facility_history->updated_at}}</td>
                                    <td>{{$facility_history->fieldname_changed}}</td>
                                    <td>{{$facility_history->old_value}}</td>
                                    <td>{{$facility_history->new_value}}</td>                                    
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
                        <form action="/facility_delete_filter" method="POST" id="facility_delete_filter">
                            {!! Form::token() !!}
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Delete Facility</h4>
                            </div>
                            <div class="modal-body">
                                
                                <input type="hidden" id="facility_recordid" name="facility_recordid">
                                <h4>Are you sure to delete this facility?</h4>
                                
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
        dataTable = $('#tbl-location-profile-history').DataTable({
            dom: 'lBfrtip',
            order: [[ 0, 'desc' ]],
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

    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#facility_recordid').val(value);
    });
    
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$map->api_key}}&libraries=places&callback=initMap"
  async defer>
</script>
@endsection




