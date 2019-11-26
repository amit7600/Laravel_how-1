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

    .home-browse-list h4{
        color: yellow;
    }

    .home-browse-list a .icon {
        width: 120px;
        height: 120px;
        background-color: rgba(0,0,0,.5);
        margin: 0 auto 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
    }

    .home-browse-list a .icon:hover {
        background-color: #f9ad19;        
    }

    .home-browse-list a .icon span {
        font-size: 50px;
        color: white;
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
            <div class="col-lg-6 col-sm-12 col-md-6 home-browse-list" style="text-align: center;">
                <h1>Browse</h1>
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-md-12" style="text-align: center;">
                        <a href="/organizations">  
                            <div class="icon organizations">
                                <span>O</span>
                            </div>                         
                            <h4 class="text-shadow-1 mb0">Organizations</h4>
                        </a>
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-md-6" style="text-align: center;">
                        <a href="/contacts">
                            <div class="icon contacts">
                                <span>C</span>
                            </div>
                            <h4 class="text-shadow-1 mb0">Contacts</h4>
                        </a>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-md-6" style="text-align: center;">
                        <a href="/facilities">
                            <div class="icon facilities">
                                <span>F</span>
                            </div>
                            <h4 class="text-shadow-1 mb0">Facilities</h4>
                        </a>
                    </div>
                </div>
                <div class="row">                    
                    <div class="col-lg-12 col-sm-12 col-md-12" style="text-align: center;">
                        <a href="/groups">
                            <div class="icon groups">
                                <span>G</span>
                            </div>
                            <h4 class="text-shadow-1 mb0">Groups</h4>
                        </a>
                    </div>
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
});
</script>
@endsection