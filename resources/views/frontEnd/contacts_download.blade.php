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
                            <th class="default-inactive">Name</th>
                            <th class="default-active">Organization</th>
                            <th class="default-active">Type</th>
                            <th class="default-inactive">Languages</th>    
                            <th class="default-inactive">Religious Title</th>  
                            <th class="default-inactive">Position Title</th>                        
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                        <tr>
                            <td>@if($contact->contact_first_name!='?'){{$contact->contact_first_name}}@endif @if($contact->contact_middle_name!=''){{$contact->contact_middle_name}}@endif @if($contact->contact_last_name!=''){{$contact->contact_last_name}}@endif</td>
                            @php $organization_name = $contact->organization['organization_name']; @endphp
                            <td>{{$organization_name}}</td>
                            <td>{{$contact->contact_type}}</td>
                            <td>{{$contact->contact_languages_spoken}}</td>
                            <td>{{$contact->contact_religious_title}}</td>
                            <td>{{$contact->contact_title}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h3>{!! $layout->footer_pdf !!}</h3>
        </div>
    </div>
</div>
