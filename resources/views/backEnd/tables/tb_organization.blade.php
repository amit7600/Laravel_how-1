@extends('backLayout.app')
@section('title')
Organizations
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
        <h2>Organizations</h2>
        <div class="clearfix"></div>  
      </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="example" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">id</th>
                    <th class="text-center">Alt id</th>                                   
                    <th class="text-center">Religion</th>
                    <th class="text-center">Faith Tradition</th>     
                    <th class="text-center">Denomination</th>
                    <th class="text-center">Judicatory Body</th>  
                    <th class="text-center">Organization Type</th>                     
                    <th class="text-center">Url</th>
                    <th class="text-center">Facebook</th>
                    <th class="text-center">C Board</th>
                    <th class="text-center">Internet Access</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Location</th>
                    <th class="text-center">Borough</th>
                    <th class="text-center">Zipcode</th>
                    <th class="text-center">Details</th> 
                    <th class="text-center">Contact</th>          
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
              @foreach($organizations as $key => $organization)
                <tr id="organization{{$organization->id}}" class="{{$organization->flag}}">
                   @if($source_data->active == 1 )
                  <td class="text-center">{{$key+1}}</td>
                  @else
                  <td>{{$organization->organization_recordid}}</td>
                  @endif
                  <td class="">{{$organization->organization_name}}</td>
                  <td class="">{{$organization->organization_id}}</td>
                  <td class="">{{$organization->organization_alt_id}}</td>
                  <td class="text-center">{{$organization->organization_religion}}</td>

                  <td class="text-center">{{$organization->organization_faith_tradition}}</td>

                  <td class="text-center">{{$organization->organization_denomination}}</td>

                  <td class="text-center">{{$organization->organization_judicatory_body}}</td>

                  <td class="text-center">{{$organization->organization_type}}</td>


                  <td class="text-center">{{$organization->organization_url}}</td>

                  <td class="text-center">{{$organization->organization_facebook}}</td>

                  <td class="text-center">{{$organization->organization_c_board}}</td>

                  <td class="text-center">{{$organization->organization_internet_access}}</td>

                  <td class="text-center"><span style="white-space:normal;">{!! $organization->organization_description !!}</span></td>

                  <td class="text-center">@if(isset($organization->location))@foreach($organization->location as $location)
                    <span class="badge bg-blue">{{$location->location_name}}</span>
                  @endforeach
                  @endif
                  </td>

                  <td class="text-center">{{$organization->organization_borough}}</td>

                  <td class="text-center">{{$organization->organization_zipcode}}</td>

                  

                  <td class="text-center">@if($organization->organization_details!='')
                    @foreach($organization->detail as $detail)
                    <span class="badge bg-purple">{{$detail->detail_value}}</span>
                  @endforeach
                  @endif
                  </td>

                  <td class="text-center">@if($organization->organization_contact!='')
                    @foreach($organization->contact as $contact)
                    <span class="badge bg-purple">{{$contact->contact_id}}</span>
                  @endforeach
                  @endif
                  </td>

                  <td class="text-center">
                    <button class="btn btn-block btn-primary btn-sm open_modal"  value="{{$organization->organization_recordid}}" style="width: 80px;"><i class="fa fa-fw fa-edit"></i>Edit</button>
                  </td>
                </tr>
              @endforeach             
            </tbody>
        </table>
        {!! $organizations->links() !!}
      </div>
    </div>
  </div>
</div>
<!-- Passing BASE URL to AJAX -->
<input id="url" type="hidden" value="{{ \Request::url() }}">

<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Organization</h4>
            </div>
            <form class=" form-horizontal user" id="frmProducts" name="frmProducts"  novalidate="" style="margin-bottom: 0;">
                <div class="row modal-body">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Name</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_name" name="organization_name" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">id</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_id" name="organization_id" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">alt id</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_alt_id" name="organization_alt_id" value="">
                      </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Organization Type</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="organization_type">
                                <option value="-">-</option>
                                <option value="Faith-Based Service Provider">Faith-Based Service Provider</option>
                                <option value="Faith-Based Service Provider, House of Worship">Faith-Based Service Provider, House of Worship</option>
                                <option value="House of Worship">House of Worship</option>
                                <option value="House of Worship, Religious School">House of Worship, Religious School</option>
                                <option value="Religious School">Religious School</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                   
                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Url</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_url" name="organization_url" value="">
                      </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Facebook</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_facebook" name="organization_facebook" value="">
                      </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Internet Access</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="organization_internet_access">
                                <option></option>
                                <option value="yes">yes</option>
                                <option value="no">no</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Description</label>

                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_description" name="organization_description" value="">
                      </div>

                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-3 control-label">Borough</label>
                        <div class="col-sm-7">
                            <select class="form-control" id="organization_borough">
                                <option></option>
                                <option value="-">-</option>
                                <option value="Brooklyn">Brooklyn</option>
                                <option value="Manhattan">Manhattan</option>
                                <option value="Queens">Queens</option>
                                <option value="The Bronx">The Bronx</option>
                                <option value="Staten Island">Staten Island</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                      <label for="inputPassword3" class="col-sm-3 control-label">Zip Code</label>  
                      <div class="col-sm-7">
                        <input type="text" class="form-control" id="organization_zipcode" name="organization_zipcode" value="">
                      </div>

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
    } );
} );
</script>
<script src="{{asset('js/organization_ajaxscript.js')}}"></script>
@endsection
