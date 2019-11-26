@extends('layouts.app')
@section('title')
Group Profile
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">

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
#tbl-group-profile-contact {
    width: 100% !important;
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
table#tbl-group-profile-members {
    width: 100% !important;
    display: block;
    border-bottom: 0px;
}
#tbl-group-profile-members_wrapper {
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
							<a href="">{{$group->group_name}}
							</a>
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Group Type:</b></span> 
							{{$group->group_type}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Created:</b></span> 
							{{$group_date_created}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Group Email:</b></span> 
							{{$group->group_emails}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Group Messages:</b></span> 
							{{$group->group_messages}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Last Sent:</b></span> 
							{{$group->group_message_last_sent}}
                        </h4>
                    </div>
                </div>
            </div> 
            <div class="col-md-4 property">
				<div class="pt-10 pb-10 pl-0 btn-download">
                    <a href="/group/{{$group->group_recordid}}/edit" class="btn btn-primary "><i class="fa fa-fw fa-edit"></i>Edit</a>
                    <a href="#" class="btn btn-secondary "><i class="fa fa-fw fa-edit"></i>Add Contact</a>
                    <a href="#" class="btn btn-info "><i class="fa fa-fw fa-envelope"></i>Send a Message</a>
				</div>
				<div class="card">
					<div id="map" style="width:initial;margin-top: 0;height: 50vh;"></div>					
                </div>
            </div>
            <div class="col-md-8 pt-15 pb-15 pl-30 pl-30">
                <button class="btn btn-danger remove-td" id="remove-members-group-btn" value="{{$group->group_recordid}}" data-toggle="modal" data-target=".bs-remove-modal-lg"><i class="fa fa-fw fa-remove"></i>Remove</button>
                <div class="card">
                    <div class="card-block">
                        <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-group-profile-members">
                            <thead>
                                <tr>
                                    <th class="default-active"></th>
                                    <th class="default-active"></th>
                                    <th class="default-inactive">ID</th>
                                    <th class="default-active">First Name</th>
                                    <th class="default-inactive">Middle Name</th>
                                    <th class="default-active">Last Name</th>
                                    <th class="default-active">Type</th>
                                    <th class="default-inactive">Religious Title</th>                                   
                                    <th class="default-active" style="width: 30%;">Organization</th>
                                    <th class="default-inactive">Religion</th>                                   
                                    <th class="default-inactive">Borough</th>   
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($contacts as $key => $contact)
                                <tr>
                                    <td></td>
                                    <td>
                                        <a class="btn btn-primary open-td" href="/contact/{{$contact->contact_recordid}}" style="color: white;">Open</a>
                                    </td>
                                    <td>{{$contact->contact_recordid}}</td>
                                    <td>{{$contact->contact_first_name}}</td>
                                    <td>{{$contact->contact_middle_name}}</td>
                                    <td>{{$contact->contact_last_name}}</td>
                                    <td>{{$contact->contact_type}}</td>                                    
                                    <td>{{$contact->contact_religious_title}}</td>
                                    <td>
                                        <a id="contact_organization_link" style="color: #3949ab; text-decoration: underline;" href="/organization/{{$contact->organization_recordid}}">{{$contact->organization_name}}</a>
                                    </td> 
                                    <td>{{$contact->organization_religion}}</td>                                 
                                    <td>{{$contact->address_city}}</td>                                    
                                </tr>
                            @endforeach
                            <tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pt-15 pb-15 pl-30 pl-30">
               <div class="card">
                    <div class="card-block">
                        <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-group-profile-campagins">
                            <thead>
                                <tr>
                                    <th class="default-active"></th>
                                    <th class="default-inactive">ID</th>
                                    <th class="default-active">Campaign Name</th>
                                    <th class="default-active">Time Sent</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade bs-remove-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="/group_remove_members" method="POST" id="group_remove_members">
                            {!! Form::token() !!}
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Remove contacts from Group</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="group_recordid" name="group_recordid">
                                <input type="hidden" id="checked_terms" name="checked_terms">
                                <h4>Are you sure to remove selected members from this group?</h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger btn-delete">Remove</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('customScript')

<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script>
    var dataTable;
    var checked_terms_set;
    $(document).ready(function() {
        dataTable = $('#tbl-group-profile-members').DataTable({
                   
            dom: 'lBfrtip',
            buttons: [{
                extend: 'colvis',
                columns: [4, 7, 10]
            }],
            columnDefs: [
                { targets: 'default-inactive', visible: false},
                {
                    orderable: false,
                    className: 'select-checkbox',
                    targets:   0
                }
            ],
            select: {
                'style': 'multi'
            },
        });           
    })
   $(document).ready(function(){  
        
        var locations = <?php print_r(json_encode($locations)) ?>;        
        var maplocation = <?php print_r(json_encode($map)) ?>; 
        console.log(locations);       

        if(maplocation.active == 1){
            avglat = maplocation.lat;
            avglng = maplocation.long;
            zoom = maplocation.zoom;
        }
        else
        {
            avglat = 40.730981;
            avglng = -73.998107;
            zoom = 12;
        }

        latitude = null;
        longitude = null;
        if (locations.length > 0) {
            if (locations[0][0] != null){
            latitude = locations[0][0].location_latitude;
            longitude = locations[0][0].location_longitude;
            } else {
                latitude = null;
                longitude = null;
            }
        }
        
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

        $.each(locations, function(index, value){ 
            if(value[0]){
                mymap.addMarker({
                    lat: value[0].location_latitude,
                    lng: value[0].location_longitude,
                });
            }
        });
        
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

    $('#remove-members-group-btn').click(function(e){
        console.log(checked_terms_set);
        if (!checked_terms_set) {
            e.preventDefault();
            var value = $(this).val();
            $('input#group_recordid').val(value);

            var checked_rows = dataTable.rows('.selected').data();
            var checked_terms = [];
            for (i = 0; i < checked_rows.length; i++) {
                checked_terms.push(checked_rows[i][2]);
            }            
            $('#checked_terms').val(checked_terms.join(","));            
            checked_terms_set = true;
            $(this).trigger('click');
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
        $('input#group_recordid').val(value);
    });

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$map->api_key}}&libraries=places&callback=initMap"
  async defer>
</script>
@endsection




