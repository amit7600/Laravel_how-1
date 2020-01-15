@extends('layouts.app')
@section('title')
Contact Create
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

    button[data-id="contact_organization_type"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_pronouns"] {
        height: 100%;
        border: 1px solid #ddd;
    }

    button[data-id="contact_type"] {
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

    /* #view-contact-btn {
        float: right;
    } */

    h1 {
        text-align: center;
    }
</style>

@section('content')
<div class="wrapper">
    <div id="contacts-edit-content" class="container">
        <h1>Create New Contact</h1>
        <form action="/add_new_contact" method="GET">
            <div class="row">
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">First Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_first_name"
                            name="contact_first_name" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Middle Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_middle_name"
                            name="contact_middle_name" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Last Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_last_name"
                            name="contact_last_name" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Type: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        {{-- <input class="form-control selectpicker" type="text" id="contact_type" name="contact_type"
                            value=""> --}}
                        {!! Form::select('contact_type',$contact_type,null,['class' => 'form-control
                        selectpicker','id' =>
                        'contact_type','data-live-search' => 'true','placeholder' => 'Select contact type']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Religious Prefix: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_religious_title"
                            name="contact_religious_title" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Job Title: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_title" name="contact_title"
                            value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Organization Name: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="contact_organization_name"
                            name="contact_organization_name" required>
                            @foreach($organization_name_list as $key => $org_name)
                            <option value="{{$org_name}}">{{$org_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Languages Spoken: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        {{-- <select class="form-control selectpicker" multiple data-live-search="true"
                            id="contact_languages_spoken" name="contact_languages_spoken[]">
                            @foreach($contact_languages as $key => $contact_language)
                            <option value="{{$contact_language}}">{{$contact_language}}</option>
                        @endforeach
                        </select> --}}
                        {!! Form::select('contact_languages_spoken[]',$contact_languages,null,['class' => 'form-control
                        selectpicker','id' =>
                        'contact_languages_spoken','data-live-search' => 'true','multiple' => 'multiple']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Other Languages: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_other_languages"
                            name="contact_other_languages" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Pronouns: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <select class="form-control selectpicker" data-live-search="true" id="contact_pronouns"
                            name="contact_pronouns">
                            @foreach($contact_pronoun_list as $key => $contact_pronoun)
                            <option value="{{$contact_pronoun}}">{{$contact_pronoun}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Mailing Address: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_mailing_address"
                            name="contact_mailing_address" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Cell Phone: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_office_phones"
                            name="contact_cell_phones" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Office Phone: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_office_phones"
                            name="contact_office_phones" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Emergency Phone: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_emergency_phones"
                            name="contact_emergency_phones" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Office Fax: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_office_fax_phones"
                            name="contact_office_fax_phones" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Personal Email: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_personal_email"
                            name="contact_personal_email" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label sel-label-org pl-4">Work Email: </label>
                    <div class="col-md-12 col-sm-12 col-xs-12 contact-details-div">
                        <input class="form-control selectpicker" type="text" id="contact_email" name="contact_email"
                            value="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12 text-center">
                        <a href="/contacts" class="btn btn-danger btn-rounded" id="view-contact-btn"><i
                                class="fa fa-arrow-left"></i> Back</a>
                        <button type="submit" class="btn btn-success btn-rounded" id="save-contact-btn"><i
                                class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<script>
    $(document).ready(function() {
        $('select#contact_organization_name').val([]).change();
        $('select#contact_languages_spoken').val([]).change();
        $('select#contact_pronouns').val([]).change();   
    });

</script>
@endsection