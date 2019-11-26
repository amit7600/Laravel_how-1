@extends('backLayout.app')
@section('title')
Contacts
@stop
<style>
    tr.modified{
        background-color: red !important;
    }

    tr.modified > td{
        background-color: red !important;
        color: white;
    }
</style>
@section('content')

<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Contacts</h2>
        <div class="clearfix"></div>  
      </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="example" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">id</th>
                    <th class="text-center">First Name</th>
                    <th class="text-center">Middle Name</th>
                    <th class="text-center">Last Name</th>
                    <th class="text-center">Organizations</th>
                    <th class="text-center">Organization ID</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Languages spoken</th>
                    <th class="text-center">Other languages</th>
                    <th class="text-center">Religious title</th>
                    <th class="text-center">Title</th>
                    <th class="text-center">Pronouns</th>
                    <th class="text-center">Mailing Address</th>
                    <th class="text-center">Cell Phones</th>
                    <th class="text-center">Office Phones</th>
                    <th class="text-center">Emergency Phones</th>
                    <th class="text-center">Personal Email</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($contacts as $key => $contact)
                <tr id="contact{{$contact->id}}" class="{{$contact->flag}}">
                               
                  <td class="text-center">{{$contact->contact_id}}</td>
                  
                  <td class="text-center">{{$contact->contact_first_name}}</td>
                  <td class="text-center">{{$contact->contact_middle_name}}</td>
                  <td class="text-center">{{$contact->contact_last_name}}</td>
                  <td class="text-center">
                    @if($contact->contact_organizations!=0)
                    <span class="badge bg-red">{{$contact->organization()->first()->organization_name}}</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <span class="badge bg-blue">{{$contact->contact_organization_id}}</span>
                  </td>
                  <td class="text-center">{{$contact->contact_type}}</td>
                  <td class="text-center">{{$contact->contact_languages_spoken}}</td>
                  <td class="text-center">{{$contact->contact_other_languages}}</td>
                  <td class="text-center">{{$contact->contact_religious_title}}</td>
                  <td class="text-center">{{$contact->contact_title}}</td>
                  <td class="text-center">{{$contact->contact_pronouns}}</td>
                  <td class="text-center">@if($contact->contact_mailing_address!=0)
                    <span class="badge bg-red">{{$contact->address()->first()->address}}</span>
                    @endif</td>
                  <td class="text-center">
                    @if(isset($contact->cellphone()->first()->phone_number))
                      <span class="badge bg-purple">{{$contact->cellphone()->first()->phone_number}}</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if(isset($contact->officephone()->first()->phone_number))
                      <span class="badge bg-purple">{{$contact->officephone()->first()->phone_number}}</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if(isset($contact->emergencyphone()->first()->phone_number))
                      <span class="badge bg-purple">{{$contact->emergencyphone()->first()->phone_number}}</span>
                    @endif
                  </td>
                  <td class="text-center">{{$contact->contact_personal_email}}</td>
                  <td class="text-center">{{$contact->contact_email}}</td>
                  <td>
                    <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$contact->contact_recordid}}"><i class="fa fa-fw fa-edit"></i>Edit</button>
                  </td>
                </tr>
              @endforeach             
            </tbody>
        </table>
        {!! $contacts->links() !!}
      </div>
    </div>
  </div>
</div>
<!-- Passing BASE URL to AJAX -->
<input id="url" type="hidden" value="{{ \Request::url() }}">

<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Contacts</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="modal-body">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">First Name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_first_name" name="contact_first_name" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Middle Name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_middle_name" name="contact_middle_name" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Last Name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_last_name" name="contact_last_name" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Organization ID</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_organization_id" name="contact_organization_id" value="">
                      </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Type</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="contact_type">
                                <option></option>
                                <option value="Religious Leader">Religious Leader</option>
                                <option value="Staff">Staff</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Languages spoken</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="contact_languages_spoken">
                                <option></option>
                                <option value="Arabic">Arabic</option>
                                <option value="Cantonese">Cantonese</option>
                                <option value="English">English</option>
                                <option value="French">French</option>
                                <option value="German">German</option>
                                <option value="Greek">Greek</option>
                                <option value="Italian">Italians</option>
                                <option value="Japanese">Japanese</option>
                                <option value="Korean">Korean</option>
                                <option value="Malay">Malay</option>
                                <option value="Thai">Thai</option>
                                <option value="Spanish">Spanish</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Other languages</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_other_languages" name="contact_other_languages" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Religious title</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_religious_title" name="contact_religious_title" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Title</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_title" name="contact_title" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Pronouns</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_pronouns" name="contact_pronouns" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Personal Email</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_personal_email" name="contact_personal_email" value="">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Email</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="contact_email" name="contact_email" value="">
                      </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                    <input type="hidden" id="id" name="id" value="0">
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
@endsection

@section('scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable( {
        responsive: {
            details: {
                renderer: function ( api, rowIdx, columns ) {
                    var data = $.map( columns, function ( col, i ) {
                        return col.hidden ?
                            '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td>'+col.title+':'+'</td> '+
                                '<td>'+col.data+'</td>'+
                            '</tr>' :
                            '';
                    } ).join('');
 
                    return data ?
                        $('<table/>').append( data ) :
                        false;
                }
            }
        },
        "paging": false,
        "pageLength": 20,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": true
    });
});
</script>
<script src="{{asset('js/contacts_ajaxscript.js')}}"></script>
@endsection
