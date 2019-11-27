@extends('layouts.app')
@section('title')
Home
@stop
<style>
   .navbar-container.container-fluid{
        display: none !important;
    }
    @media (max-width: 991px){
        .page {
            padding-top: 0px !important;
        }
    }
    .pac-logo:after{
      display: none;
    }
    ul#tree1 {
        column-count: 2;
    }
    .home-category{
        cursor: pointer;
    }

    .home-browse-list h1{
        color: yellow;
    }

    .home-browse-list p {
        font-weight: 200;
    }

</style>
<link href="{{asset('css/treeview.css')}}" rel="stylesheet">
@section('content')       
   
<div class="after_serach">
    <div class="container">
        <div class="row">                
            <div class="col-lg-6 col-sm-12 col-md-6" style="text-align: center;">
                <div class="inner_search">
                    {!! $home->sidebar_content !!}
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 home-browse-list" style="text-align: left;">
                <h1>Browse</h1>
                <div class="list-group">
                    <a class="list-group-item list-group-item-action flex-column align-items-start" href="/organizations">
                        <h4 class="list-group-item-heading mt-0 mb-5"> 
                            Organizations
                        </h4>
                        <p class="mb-0">There is data table for organizations in this page. And this page has a few selectors for filter and google map. Filter by selectors and google map are available. And filtered data are available to export as pdf or csv. </p>
                    </a>
                    <a class="list-group-item list-group-item-action flex-column align-items-start" href="/contacts">
                        <h4 class="list-group-item-heading mt-0 mb-5"> 
                            Contacts
                        </h4>
                        <p class="mb-0">There is data table for contacts in this page. And this page has a few selectors for filter and google map. Filter by selectors and google map are available. And adding contacts to group is available. And also, filtered data are available to export as pdf or csv.</p>
                    </a>
                    <a class="list-group-item list-group-item-action flex-column align-items-start" href="/facilities">
                        <h4 class="list-group-item-heading mt-0 mb-5"> 
                            Facilities
                        </h4>
                        <p class="mb-0">There is data table for facilities in this page. And this page has a few selectors for filter and google map. Filter by selectors and google map are available. And filtered data are available to export as pdf or csv.</p>
                    </a>
                    <a class="list-group-item list-group-item-action flex-column align-items-start" href="/groups">
                        <h4 class="list-group-item-heading mt-0 mb-5"> 
                            Groups
                        </h4>
                        <p class="mb-0">There is data table for groups in this page. Every group can be edited and removed. And members of each group are being shown in this page</p>
                    </a>
                </div>
                <h1>Communicate</h1>
                <div class="list-group">
                    <a class="list-group-item list-group-item-action flex-column align-items-start" href="/campaigns">
                        <h4 class="list-group-item-heading mt-0 mb-5"> 
                            Campaigns
                        </h4>
                        <p class="mb-0">This page shows data related to campaigns</p>
                    </a>
                    <a class="list-group-item list-group-item-action flex-column align-items-start" href="/messages">
                        <h4 class="list-group-item-heading mt-0 mb-5"> 
                            Messages
                        </h4>
                        <p class="mb-0">This page is for manage messages.</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
	


<script src="{{asset('js/treeview.js')}}"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function(){
    $('.home-category').on('click', function(e){
        var id = $(this).attr('at');
        console.log(id);
        $("#category_" +  id).prop( "checked", true );
        $("#filter").submit();
    });
    $('.list-group a').hover(function () {
        $(this).addClass('active');
    }, function () {
        $(this).removeClass('active');
    });
});
</script>
@endsection