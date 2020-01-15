@extends('layouts.app')
@section('title')
Confirm campaign
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


<style type="text/css">
    #organizations-edit-content {
        margin-top: 50px;
        width: 50%;
        margin: 0 auto;
    }

    #organizations-edit-content .form-group {
        width: 100%;
    }

    button[data-id="organization_organization_type"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="organization_organization_religion"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="organization_organization_faith_tradition"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="organization_organization_denomination"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="organization_organization_judicatory_body"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .form-group button {
        width: 32.8%;
    }

    .form-group a {
        width: 32.8%;
    }

    @media only screen and (max-width: 768px) {
        .form-group button {
            width: 100%;
        }

        .form-group a {
            width: 32.96%;
        }
    }

    .organization-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }

    .delete-btn-div {
        text-align: center;
    }

    h1 {
        text-align: center;
    }

    .jconfirm-box-container {
        margin-left: 35% !important;
    }
</style>

@section('content')
<div class="wrapper">
    <!-- Page Content Holder -->
    <div class="container">
        <h1>Confirm Campaign</h1>
        {!! Form::open(['route' => array('send_campaign',$campaignConfirm->id),'class' => 'form-horizontal
        form-label-left','enctype' => 'multipart/form-data']) !!}
        <div class="row">
            <div id="organizations-edit-content">
                <div class="col-md-12">
                    <div class="card background_gry">
                        <div class="card-body">
                            <input type="hidden" name="CampaignId" id="CampaignId" value="{{ $campaignConfirm->id}}">
                            <p class="card-text"><b>Name:</b> {{ $campaignConfirm->name}} </p>
                            <p class="card-text"><b>Type:
                                </b>{{ $campaignConfirm->campaign_type == 1 ? 'Email' : ($campaignConfirm->campaign_type == 2 ? 'SMS' : 'Audio')}}
                            </p>

                            @if($campaignConfirm->campaign_type == 1)
                            <p class="card-text"><b>Subject:</b> {{ $campaignConfirm->subject}}</p>
                            @endif
                            <p class="card-text"><b>Message Body:</b> {{ $campaignConfirm->body}}</p>
                            <!--  @if($campaignConfirm->campaign_type != 2)
                            @if($campaignConfirm->campaign_type == 1)
                            <p class="card-text"><b>Attechemnt :</b> </p>
                            @else
                            <p class="card-text"><b>Audio Recording:</b> </p>
                            @endif
                            @endif -->
                            @if($campaignConfirm->sending_type == 2)
                            <p class="card-text"><b>Schedule:</b>
                                {{ date('d/m/Y h:i', strtotime($campaignConfirm->schedule_date))}}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mt-0">Recipients </h3>
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th><b>Name</b></th>
                                    <th><b>Organization</b></th>
                                    <th><b>Group</b></th>
                                    <th><b>Action</b></th>
                                </tr>
                                @if($groupContact != null)
                                @foreach($groupContact as $value)
                                <tr id="id_{{ $value->id}}">
                                    <td>{{ $value->contact_first_name}} {{ $value->contact_middle_name}}
                                        {{ $value->contact_last_name}}</td>
                                    <td>{{ $value->contact_organizations != null ?  $value->organization->organization_name : ''}}
                                    </td>
                                    @php
                                    $groupsId = $value->contact_group != null ? explode(',',$value->contact_group) : [];
                                    $groupName = [];
                                    foreach ($groupsId as $key => $groupId) {
                                    $group = \DB::table('groups')->where('group_recordid',$groupId)->first();
                                    if($group){
                                    array_push($groupName,$group->group_name);
                                    }
                                    }
                                    @endphp
                                    <td>{{ $groupName != null ? implode(',',$groupName) : ''}}</td>
                                    <td><button type="button" class="btn btn-danger"
                                            onclick="deleteCampaign('{{ $value->id }}')">Remove</button></td>
                                </tr>
                                @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <a href="{{route('campaigns.index')}}" class="btn btn-danger btn-rounded"
                                style="color:#000;"><i class="fa fa-check"></i> Save &
                                Close</a>
                            <a href="{{route('campaigns.edit',$campaignConfirm->id)}}"
                                class="btn btn-warning btn-rounded" style="color:#000;"><i class="fa fa-pencil"></i>
                                Edit</a>
                            <button type="submit" class="btn btn-success btn-rounded" style="color:#000;"><i
                                    class="fa fa-paper-plane" style="color:#fff;"></i>
                                Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<!-- Modal -->
<div id="contact" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Contact</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success alert-dismissable custom-success-box" style="margin: 15px;display:none;"
                    id="successMessage">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong> </strong>
                </div>
                <div class="alert alert-danger alert-dismissable custom-danger-box" style="margin: 15px; display:none;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong id="dangerMessage"> </strong>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><b>Contact name</b></th>
                                @if ($campaignType == 1)
                                <th><b>Email</b></th>
                                @elseif($campaignType == 2)
                                <th><b>Phone</b></th>
                                @elseif($campaignType == 3)
                                <th><b>Office phone</b></th>
                                @endif
                                <th><b>Organization</b></th>
                                <th><b>Action</b></th>
                            </tr>
                        </thead>
                        <tbody id="tbody">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>


    </div>
</div>
@endsection
@section('customScript')
<script src="{{asset('js/markerclusterer.js')}}"></script>
<script>
    var blankContact = '<?php echo json_encode($blankContact) ?>'
    var contact = JSON.parse(blankContact);
    var type = {{ $campaignType }}
    $(document).ready(function(){
        if(contact.length > 0){
            $('#contact').modal('show');
            if (type == 1) {    
                $.each(contact,function(i,v){
                    $('#tbody').append('<tr><td>'+v.name+'</td><td contenteditable id="email_'+v.id+'"></td><td>'+v.organization+'</td><td><button class="btn btn-info btn-sm" id="addInfo" onclick="addInfo('+v.id+')">add info</button><button class="btn btn-success btn-sm" id="saveInfo" style="display:none;" onclick="saveInfo('+v.id+')">save info</button></td></tr>');
                })
            }else if(type == 2){
                $.each(contact,function(i,v){
                    $('#tbody').append('<tr><td>'+v.name+'</td><td id="phone_'+v.id+'"></td><td>'+v.organization+'</td><td><button class="btn btn-info btn-sm" id="addInfo" onclick="addInfo('+v.id+')">add info</button><button class="btn btn-success btn-sm" id="saveInfo" style="display:none;" onclick="saveInfo('+v.id+')">save info</button></td></tr>');
                });
            }else if(type == 3){
                $.each(contact,function(i,v){
                    $('#tbody').append('<tr><td>'+v.name+'</td><td contenteditable id="officePhone_'+v.id+'"></td><td>'+v.organization+'</td><td><button class="btn btn-info btn-sm" id="addInfo" onclick="addInfo('+v.id+')">add info</button><button class="btn btn-success btn-sm" id="saveInfo" style="display:none;" onclick="saveInfo('+v.id+')">save info</button></td></tr>');
                });
            }
        }
    })
    function addInfo(id){  

        if(type == 1){
            $('#email_'+id).attr('contenteditable',true);
            $('#email_'+id).focus();
        }else if(type == 2){
            $('#phone_'+id).attr('contenteditable',true);
            $('#phone_'+id).focus();
        }else if(type == 3){
            $('#officePhone_'+id).attr('contenteditable',true);
            $('#officePhone_'+id).focus();
        }
        $('#addInfo').hide();
        $('#saveInfo').show();

    }
    function saveInfo(id){
        let email
        let phone
        let officePhone
        if(type == 1){
          email = $('#email_'+id).text();
          var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i

          if(!pattern.test(email)){
              alert('Please valid e-mail address');
              $('#email_'+id).focus();
              return false;
          }
          $('#email_'+id).removeAttr('contenteditable',false);
        }else if(type == 2){
          phone = $('#phone_'+id).text();
          if(phone == ''){
              alert('Please enter valid Phone number!');
              $('#phone_'+id).focus();
              return false;
          }
          $('#phone_'+id).removeAttr('contenteditable',false);
        }else if(type == 3){

          officePhone = $('#officePhone_'+id).text();
          var pattern = /^\b[0-9._%-]{10,12}\b$/i
          if(!pattern.test(officePhone)){
              alert('Please enter valid Phone number!');
              $('#officePhone_'+id).focus();
              return false;
          }
          $('#officePhone_'+id).removeAttr('contenteditable',false);
        }

        $('#addInfo').show();
        $('#saveInfo').hide();
        $.ajax({
            method: 'POST',
            url: '{{route("saveContactInfo")}}',
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            data:{ email,phone,officePhone,type,id },
            success:function(response){
                $('#successMessage').show()
                $('#successMessage').find('strong').empty();
                $('#successMessage').find('strong').append(response.message);
                setTimeout(function(){
                    $('#successMessage').hide()
                },1000);

            },
            error:function(error){
                $('#errorMessage').show()
                $('#errorMessage').find('strong').empty();
                $('#errorMessage').find('strong').append(error['responseJSON'].message);
            }
        })

    }
</script>
<script type="text/javascript">
    function deleteCampaign(id){
        var token = $('meta[name="_token"]').attr('content');
        var CampaignId = $('#CampaignId').val();
        $.confirm({
                title: 'Recipients Delete!',
                theme: 'black',
                content: '<span style="font-size: 16px;">Are you sure want to delete this record?</span>',
                confirmButtonClass: 'btn-primary',
                cancelButtonClass: 'btn-danger',
                confirmButton:'Yes',
                boxWidth: '30%',
                useBootstrap: false,
                cancelButton:'No',
                confirm: function(){
                         $.ajax({
                            type: 'POST',
                            url: "{{route('deleteRecipient')}}",
                            data: {'_token':token, 'id':id,'campaignId':CampaignId },          
                            success: function(resultData) { 
                                if(resultData.success){
                                    $('#id_'+id).hide();
                                    //alert('successfully deleted.');
                                    $.confirm({
                                        title: 'Recipients deleted successfully!',
                                        theme: 'black',                                        
                                        confirmButtonClass: 'btn-success',        
                                        confirmButton:'Ok',
                                        boxWidth: '20%',
                                    });    
                                }else{
                                    $.confirm({
                                        title: 'Not deleted!',
                                        theme: 'black',                                        
                                        confirmButtonClass: 'btn-success',        
                                        confirmButton:'Ok',
                                        boxWidth: '20%',
                                    }); 
                                }                    
                            }
                        }); 
                    },
                cancel: function(){
                }
            });


        // if(confirm('Are you sure want to delete?')){            
            
             
        // }
    }
</script>

@endsection