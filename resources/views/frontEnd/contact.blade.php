@extends('layouts.app')
@section('title')
Contact
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
table#tbl-message-profile-contact {
    width: 100% !important;
    display: block;
    border-bottom: 0px;
}
#tbl-message-profile-contact_wrapper {
    overflow-x: scroll;
}
#contact_group_list_div {
    display: flex;
    flex-direction: column;
}
#tagging-div {
    margin-top: 12px !important;
}
</style>

@section('content')
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="content" class="container">
		<div class="row m-0">
        	<div class="col-md-8 pt-15 pl-30">
               <div class="card">
                    <div class="card-block">
                        <h4 class="card-title">
							<a href="">@if($contact->contact_first_name!='?'){{$contact->contact_first_name}}@endif @if($contact->contact_middle_name!=''){{$contact->contact_middle_name}}@endif @if($contact->contact_last_name!=''){{$contact->contact_last_name}}@endif
                            </a>
                            <a href="/contact/{{$contact->contact_recordid}}/edit" class="btn btn-floating btn-success waves-effect waves-classic" style="float: right;">
                                <i class="icon md-edit" style="margin-right: 0px;"></i>
                            </a>
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Religious Prefix:</b></span> 
							{{$contact->contact_religious_title}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Job Title:</b></span> 
							{{$contact->contact_title}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Organization:</b></span> 
                            <a href="/organization/{{$organization_id}}">{{$contact_organization_name}}</a>						
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Type:</b></span> 
							{{$contact->contact_type}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Pronouns:</b></span> 
							{{$contact->contact_pronouns}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Mailing Address:</b></span> 
							{{$mailing_address}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Office Phone:</b></span> 
							{{$office_phone_number}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Languages Spoken:</b></span> 
							{{$contact->contact_languages_spoken}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Other Languages:</b></span> 
							{{$contact->contact_other_languages}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Cell Phone:</b></span> 
							{{$cell_phone_number}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Emergency Phone:</b></span> 
							{{$emergency_phone_number}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Office Fax:</b></span> 
							{{$office_fax_phone_number}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Personal Email:</b></span> 
							{{$contact->contact_personal_email}}
                        </h4>
                        <h4>
							<span class="badge bg-red pl-0 organize_font"><b>Work Email:</b></span> 
							{{$contact->contact_email}}
                        </h4>
                    </div>
                </div>
            </div> 
            <div class="col-md-4 property">
				<div class="pt-10 pl-0 btn-download">
                    <form method="GET" action="/contact/{{$contact->contact_recordid}}/tagging" id="contact_tagging">
                        <div class="row m-0" id="tagging-div">
                            <div class="col-md-10">
                                <input type="text" class="form-control" id="tokenfield" name="tokenfield" value="{{$contact->contact_tag}}" />
                            </div> 
                            <div class="col-md-2">  
                                <button type="submit" class="btn btn-secondary btn-tag-save">
                                    <i class="fas fa-save"></i>
                                </button>
                            </div> 
                        </div>
                    </form>
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-fw fa-home"></i> Add to Group
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach($groups as $group)
                                <a class="dropdown-item" href="/contact/{{$contact->contact_recordid}}/{{$group->group_name}}/update_group">{{$group->group_name}}</a>
                            @endforeach
                        </div>
                    </div>
                    <button class="btn btn-secondary message-td" value="{{$contact->contact_recordid}}" data-toggle="modal" data-target=".bs-message-modal-lg"><i class="fa fa-fw fa-envelope"></i> Send a Message</button>
				</div>
				<div class="card">
					<div id="map" style="width:initial;margin-top: 10px;height: 90vh;"></div>					
                </div>
            </div> 
            <div class="col-md-8 pb-15 pl-30 pl-30">
                <div class="card">
                    <div class="card-block">
                        <h3>Messages</h3>
                        <table class="table table-striped jambo_table bulk_action nowrap" id="tbl-message-profile-contact">
                            <thead>
                                <tr>
                                    <th class="default-active"></th>
                                    <th class="default-inactive">ID</th>
                                    <th class="default-active">Timestamp</th>
                                    <th class="default-active">Type</th>
                                    <th class="default-active">Status</th>
                                    <th class="default-active">Campaign</th>
                                    <th class="default-active">Subject</th>
                                    <th class="default-active">Message</th>
                                    <th class="default-active">Attachments</th> 
                                    <th class="default-active">Reply</th> 
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-block">
                    <h3 class="card-title">
                        Groups
                    </h3>
                    <div class="p-10" id="contact_group_list_div">
                        @foreach($contact_group_name_list as $key => $contact_group_name)
                            <a href="/group/{{$contact_group_recordid_list[$key]}}">{{$contact_group_name}}</a>
                        @endforeach
                    </div>
                </div>

                <div class="comments-block" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-block">
                            <h3>Comments</h3>
                            <div class="comment-body media-body">
                                @foreach($comment_list as $key => $comment)
                                    <a class="comment-author" href="javascript:void(0)">{{$comment->comments_user_firstname}} {{$comment->comments_user_lastname}}</a>
                                    <div class="comment-meta">
                                        <span class="date">{{$comment->comments_datetime}}</span>
                                    </div>
                                    <div class="comment-content">
                                        <p style="color: black;">{{$comment->comments_content}}</p>
                                    </div>
                                    <hr>
                                @endforeach
                                <div class="comment-actions">
                                    <a class="active" id="reply-btn" href="javascript:void(0)" role="button">Add a comment</a>
                                </div>
                                <form class="comment-reply" action="/contact/{{$contact->contact_recordid}}/add_comment" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <textarea class="form-control" id="reply_content" name="reply_content" rows="3">
                                        </textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary waves-effect waves-classic">Post</button>
                                        <button type="button" id="close-reply-window-btn" class="btn btn-link grey-600 waves-effect waves-classic">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="modal fade bs-message-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="/send_message" method="POST" id="send_message_form">
                            {!! Form::token() !!}
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                </button>
                                <h2 class="modal-title" id="myModalLabel" style="color: #3949ab;">Send Message</h2>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="contact_recordid" name="contact_recordid">
                                <div class="form-group">
                                    <label for="contact_message_textarea">Message Content</label>
                                    <textarea class="form-control" id="contact_message_textarea" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-secondary btn-send">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/dist/bootstrap-tokenfield.js"></script>
<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js"></script>

<script>
    var dataTable;
    $(document).ready(function() {
        dataTable = $('#tbl-message-profile-contact').DataTable({
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

    
    $(document).ready(function() {
        $('.comment-reply').hide();
        $('#reply_content').val('');
    });
    $("#reply-btn").on('click', function(e) {
        e.preventDefault();
        $('.comment-reply').show();
    });
    $("#close-reply-window-btn").on('click', function(e) {
        e.preventDefault();
        $('.comment-reply').hide();
    });
    $(document).ready(function() {   
        $('#tokenfield').tokenfield({
        autocomplete: {
            source: [],
            delay: 100
        },
        showAutocompleteOnFocus: true
        });
    });
    $(document).ready(function(){  
        setTimeout(function(){
        var locations = <?php print_r(json_encode($locations)) ?>;
        var contact = <?php print_r(json_encode($contact->contact_first_name)) ?>;
        var maplocation = <?php print_r(json_encode($map)) ?>;
        console.log(locations);
        var latitude = null;
        var longitude = null;
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
        if (locations[0]) {
            latitude = locations[0].location_latitude;
            longitude = locations[0].location_longitude;
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
        $.each( locations, function(index, value ){
                // console.log(locations);
                var serviceid = value.services.length == 0?'':value.services[0].service_recordid;
                var service_name = value.services.length == 0?'':value.services[0].service_name;

                var content = "";
                for(i = 0; i < value.services.length; i ++){
                    content +=  '<a href="/service/'+value.services[i].service_recordid+'" style="color:#428bca;font-weight:500;font-size:14px;">'+value.services[i].service_name+'</a><br>';
                }
                if(value){
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
                }
            });
        }, 2000)
    });

    

</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$map->api_key}}&libraries=places&callback=initMap"
  async defer>
</script>
@endsection




