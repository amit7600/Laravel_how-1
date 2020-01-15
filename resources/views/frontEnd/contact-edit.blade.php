@extends('layouts.app')
@section('title')
Contact Edit
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    #contacts-edit-content {
        margin-top: 50px;
        width: 35%;
    }

    #contacts-edit-content .form-group {
        width: 100%;
    }

    button[data-id="contact_first_name"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_middle_name"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_last_name"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_organization_name"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_languages_spoken"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_other_languages"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_type"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_organization_type"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_pronouns"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    .form-group button {
        width: auto;
    }

    .form-group a {
        width: auto;
    }

    @media only screen and (max-width: 768px) {
        .form-group button {
            width: 100%;
        }

        .form-group a {
            width: 32.96%;
        }
    }

    .contact-details-div.org .dropdown.bootstrap-select.form-control {
        padding: 0 15px;
    }

    .delete-btn-div {
        text-align: center;
    }



    h1 {
        text-align: center;
    }
</style>

@section('content')
<div class="wrapper">
    <div id="contacts-edit-content" class="container">
        <h1>Edit Contact</h1>
        <div class="form-group delete-btn-div">

        </div>
        {{-- <form action="/contact/{{$contact->contact_recordid}}/update" method="GET"> --}}
        {!! Form::model($contact,['route' => array('contacts.update',$contact->contact_recordid),'method' => 'PUT']) !!}
        <div class="row">
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">First Name: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <input class="form-control selectpicker" type="text" id="contact_first_name"
                        name="contact_first_name" value="{{$contact->contact_first_name}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Middle Name: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <input class="form-control selectpicker" type="text" id="contact_middle_name"
                        name="contact_middle_name" value="{{$contact->contact_middle_name}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Last Name: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <input class="form-control selectpicker" type="text" id="contact_last_name" name="contact_last_name"
                        value="{{$contact->contact_last_name}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Type: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    {{-- <select class="form-control selectpicker" data-live-search="true" id="contact_type"
                            name="contact_type">
                            @foreach($contact_types as $key => $type)
                            <option value="{{$type->contact_type}}" @if ($contact->contact_type == $type->contact_type)
                    selected @endif>{{$type->contact_type}}</option>
                    @endforeach
                    </select> --}}
                    {!! Form::select('contact_type',$contact_types,null,['class' =>
                    'form-control selectpicker','id' => 'contact_type','data-live-search' => 'true']) !!}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Organization Name: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <select class="form-control selectpicker" data-live-search="true" id="contact_organization_name"
                        name="contact_organization_name">
                        @foreach($organization_name_list as $key => $org_name)
                        <option value="{{$org_name}}" @if ($contact_organization_name==$org_name) selected @endif>
                            {{$org_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Languages Spoken: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <select class="form-control selectpicker" multiple data-live-search="true"
                        id="contact_languages_spoken" name="contact_languages_spoken[]">
                        @foreach($contact_languages as $key => $contact_language)
                        <option value="{{$key}}" @if (strpos($contact->contact_languages_spoken,
                            strval($key)) !== false) selected @endif>{{$contact_language}}</option>
                        @endforeach
                    </select>
                    {{-- {!! Form::select('contact_languages_spoken[]',$contact_languages,null,['class' =>
                    'form-control selectpicker','id' => 'contact_languages_spoken','data-live-search' =>
                    'true','multiple' => 'multiple']) !!} --}}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Other Languages: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <input class="form-control selectpicker" type="text" id="contact_other_languages"
                        name="contact_other_languages" value="{{$contact->contact_other_languages}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Religious Title: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <input class="form-control selectpicker" type="text" id="contact_religious_title"
                        name="contact_religious_title" value="{{$contact->contact_religious_title}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Pronouns: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <select class="form-control selectpicker" data-live-search="true" id="contact_pronouns"
                        name="contact_pronouns">
                        @foreach($contact_pronoun_list as $key => $contact_pronoun)
                        <option value="{{$contact_pronoun}}" @if ($contact->contact_pronouns == $contact_pronoun)
                            selected @endif>{{$contact_pronoun}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Mailing Address: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <input class="form-control selectpicker" type="text" id="contact_mailing_address"
                        name="contact_mailing_address" value="{{$mailing_address}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Cell Phone: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <input class="form-control selectpicker" type="text" id="contact_cell_phones"
                        name="contact_cell_phones" value="{{$cell_phone_number}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Office Phone: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <input class="form-control selectpicker" type="text" id="contact_office_phones"
                        name="contact_office_phones" value="{{$office_phone_number}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Emergency Phone: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <input class="form-control selectpicker" type="text" id="contact_emergency_phones"
                        name="contact_emergency_phones" value="{{$emergency_phone_number}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Office Fax: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <input class="form-control selectpicker" type="text" id="contact_office_fax_phones"
                        name="contact_office_fax_phones" value="{{$office_fax_phone_number}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Personal Email: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <input class="form-control selectpicker" type="text" id="contact_personal_email"
                        name="contact_personal_email" value="{{$contact->contact_personal_email}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sel-label-org pl-4">Work Email: </label>
                <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                    <input class="form-control selectpicker" type="text" id="contact_email" name="contact_email"
                        value="{{$contact->contact_email}}">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12 text-center">
                    <a href="/contacts/{{$contact->contact_recordid}}" class="btn btn-info btn-rounded"
                        id="view-contact-btn"><i class="fa fa-arrow-left"></i> Back</a>
                    <button type="button" class="btn btn-danger delete-td" id="delete-contact-btn"
                        value="{{$contact->contact_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg"><i
                            class="fa fa-fw fa-trash"></i> Delete</button>
                    <button type="submit" class="btn btn-success btn-rounded" id="save-contact-btn"><i
                            class="fa fa-check"></i> Save</button>
                </div>

            </div>
        </div>
        {{-- </form> --}}
        {!! Form::close() !!}
        <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form action="/contact_delete_filter" method="POST" id="contact_delete_filter">
                        {!! Form::token() !!}
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel">Delete Contact</h4>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" id="contact_recordid" name="contact_recordid">
                            <h4>Are you sure to delete this contact?</h4>

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

<script>
    $('button.delete-td').on('click', function() {
        var value = $(this).val();
        $('input#contact_recordid').val(value);
    });
</script>
@endsection