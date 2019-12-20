<body>
	<nav class="site-navbar navbar navbar-inverse navbar-fixed-top navbar-mega " role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<div class="navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
					<a class="navbar-brand" href="/">
						@if($layout->logo_active == 1)
						<img class="navbar-brand-logo navbar-brand-logo-normal"
							src="../uploads/images/{{$layout->logo}}" title="{{$layout->site_name}}"
							style="height: 30px;">
						<img class="navbar-brand-logo navbar-brand-logo-special"
							src="./uploads/images/{{$layout->logo}}" title="{{$layout->site_name}}"
							style="height: 30px;">
						@endif
						<span class="navbar-brand-text hidden-xs-down">{{$layout->site_name}}</span>
					</a>
				</div>
				<button type="button" id="sidebarCollapse"
					class="navbar-toggler hamburger hamburger-close navbar-toggler-center hided" data-toggle="menubar">
					<span class="sr-only">Toggle navigation</span>
					<span class="hamburger-bar"></span>
				</button>
				@if($layout->tagline!=null)
				<div class="navbar-brand ticker well ml-10 mr-10">
					<label style="transition: none !important; display: content;">
						<b>{{$layout->tagline}}</b>
					</label>
				</div>
				@endif
				<button type="button" class="navbar-toggler collapsed float-right" data-target="#site-navbar-collapse"
					data-toggle="collapse">
					<i class="icon wb-more-horizontal" aria-hidden="true"></i>
				</button>
			</div>

			<div class="navbar-collapse navbar-collapse-toolbar collapse " id="site-navbar-collapse">
				<ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
					<li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/organizations">Organizations</a>
					</li>
					<li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/contacts">Contacts</a>
					</li>
					<li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/facilities">Facilities</a>
					</li>
					<li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/groups">Groups</a>
					</li>
					<li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/campaigns">Campaigns</a>
					</li>
					<li class="nav-item responsive_menu">
						<div class="dropdown">
							<button class="dropbtn">(+)</button>
							<div class="dropdown-content">
								<a href="/organization_create">New Organization</a>
								<a href="/contact_create">New Contact</a>
								<a href="/facility_create">New Facility</a>
								<a href="/group_create">New Group</a>
								<a href="/campaigns/create">New Campaign</a>
							</div>
						</div>
					</li>
					{{-- <li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/messages">Messages</a>
					</li> --}}
					<li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/dashboard">My Account</a>
					</li>
					<li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/about">About</a>
					</li>
					<li class="nav-item responsive_menu">
						<a class="nav-link waves-effect waves-light waves-round" href="/logout">Logout</a>
					</li>
					<!-- 					<li class="nav-item">
						<a class="nav-link waves-effect waves-light waves-round"><i class="icon md-share" style="    line-height: 22px;"></i></a>
					</li>
					<li class="nav-item">
						<a id="google_translate_element" class="nav-link waves-effect waves-light waves-round"></a>
					</li> -->

				</ul>
			</div>
		</div>
	</nav>
	<style type="text/css">
		.ticker {
			width: 400px;
			background-color: transparent;
			color: #fff;
			border: 0;
		}

		.search-near {
			padding: 10px;
			padding-left: 20px;
			font-size: 1.1em;
			display: block;
			color: #424242;
			font-weight: 400;
		}

		.bg-primary-color {
			background-color: {
					{
					$layout->primary_color
				}
			}

			;
		}

		.bg-secondary {
			background-color: {
					{
					$layout->secondary_color
				}
			}

			;
		}

		.btn-button {
			border-color: {
					{
					$layout->button_color
				}
			}

			;

			background-color: {
					{
					$layout->button_color
				}
			}

			;
			color: white;
		}

		.btn-button:hover {
			border-color: {
					{
					$layout->button_hover_color
				}
			}

			;

			background-color: {
					{
					$layout->button_hover_color
				}
			}

			;

			color: {
					{
					$layout->button_color
				}
			}

			;
		}

		.dropbtn {
			background: transparent;
			color: white;
			padding: 16px;
			font-size: 16px;
			border: none;
		}

		.dropdown {
			position: relative;
			display: inline-block;
		}

		.dropdown-content {
			display: none;
			position: absolute;
			background-color: #f1f1f1;
			min-width: 160px;
			box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
			z-index: 1;
		}

		.dropdown-content a {
			color: white;
			padding: 12px 16px;
			text-decoration: none;
			display: block;
			background: darkgreen;
		}

		.dropdown-content a:hover {
			background-color: yellow;
			color: black;
		}

		/* Show the dropdown menu on hover */
		.dropdown:hover .dropdown-content {
			display: block;
		}

		/* Change the background color of the dropdown button when the dropdown content is shown */
		.dropdown:hover .dropbtn {
			background-color: #36459b;
		}
	</style>