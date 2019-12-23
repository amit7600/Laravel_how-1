<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::auth();
// this route for response incoming message
Route::get('forward', 'WebhookController@index');

// this route for response incoming email
Route::post('inbound', 'WebhookController@inbound_email');

Route::group(['middleware' => ['web', 'auth']], function () {

    Route::get('/', ['uses' => 'HomeController@home']);
    Route::get('/home', function () {
        //return view('welcome');
        return redirect('/');
    });
    Route::get('/admin', function () {
        //return view('welcome');
        return redirect('/login');
    });

    Route::match(['get', 'post'], '/search', [
        'uses' => 'ExploreController@geocode',
    ]);

    Route::get('/about', ['uses' => 'HomeController@about']);
    Route::get('/feedback', ['uses' => 'HomeController@feedback']);

    Route::get('/services', 'ServiceController@services');
    Route::get('/service/{id}', 'ServiceController@service');

    Route::get('/contacts', 'ContactController@contacts');
    Route::post('/get_all_contacts', 'ContactController@get_all_contacts');
    Route::post('/contacts/action_group', 'ContactController@group_operation');
    Route::get('/contacts/contacts_update_static_group', 'ContactController@contacts_update_static_group');
    Route::get('/contacts/contacts_update_dynamic_group', 'ContactController@contacts_update_dynamic_group');
    Route::get('/contacts/create_new_static_group_add_members', 'ContactController@create_new_static_group_add_members');
    Route::get('/contact/{id}', 'ContactController@contact');
    Route::get('/contact/{id}/tagging', 'ContactController@tagging');
    Route::get('/contact/{id}/edit', 'ContactController@edit');
    Route::get('/contact/{id}/update', 'ContactController@update');
    Route::post('/contact/{id}/add_comment', 'ContactController@add_comment');
    Route::get('/contact/{id}/add_group', 'ContactController@add_group');
    Route::get('/contact/{id}/{group_name}/update_group', 'ContactController@update_group');
    Route::get('/contact_create', 'ContactController@create');
    Route::get('/add_new_contact', 'ContactController@add_new_contact');

    Route::get('/organizations', 'OrganizationController@organizations');
    Route::post('/get_all_organizations', 'OrganizationController@get_all_organizations');
    Route::post('/organizations/action_group', 'OrganizationController@group_operation');
    Route::get('/organization/{id}', 'OrganizationController@organization');
    Route::get('/organization/{id}/tagging', 'OrganizationController@tagging');
    Route::get('/organization/{id}/edit', 'OrganizationController@edit');
    Route::get('/organization/{id}/update', 'OrganizationController@update');
    Route::post('/organization/{id}/add_comment', 'OrganizationController@add_comment');
    Route::get('/organization_create', 'OrganizationController@create');
    Route::get('/add_new_organization', 'OrganizationController@add_new_organization');

    Route::get('/groups', 'GroupController@groups');
    Route::get('/group/{id}', 'GroupController@group');
    Route::get('/group/{id}/edit', 'GroupController@edit');
    Route::get('/group/{id}/tagging', 'GroupController@tagging');
    Route::get('/group/{id}/update', 'GroupController@update');
    Route::get('/group_create', 'GroupController@create');
    Route::get('/add_new_group', 'GroupController@add_new_group');

    Route::get('/facilities', 'LocationController@facilities');
    Route::post('/get_all_facilities', 'LocationController@get_all_facilities');
    Route::get('/facilities/action_group', 'LocationController@group_operation');
    Route::get('/facility/{id}', 'LocationController@facility');
    Route::get('/facility/{id}/tagging', 'LocationController@tagging');
    Route::get('/facility/{id}/edit', 'LocationController@edit');
    Route::get('/facility/{id}/update', 'LocationController@update');
    Route::post('/facility/{id}/add_comment', 'LocationController@add_comment');
    Route::get('/facility_create', 'LocationController@create');
    Route::get('/add_new_facility', 'LocationController@add_new_facility');

    Route::get('/category/{id}', 'ServiceController@taxonomy');

    Route::get('/services_near_me', 'ExploreController@geolocation');

    Route::post('/filter', 'ExploreController@filter');
    Route::get('/filter', 'ExploreController@filter');

    // Route::post('/explore', 'ExploreController@index');
    Route::get('/profile/{id}', 'ExploreController@profile');
    Route::get('/explore/status_{id}', 'ExploreController@status');
    Route::get('/explore/district_{id}', 'ExploreController@district');
    Route::get('/explore/category_{id}', 'ExploreController@category');
    Route::get('/explore/cityagency_{id}', 'ExploreController@cityagency');

    //download pdf
    Route::get('/download_service/{id}', 'ServiceController@download');
    Route::get('/download_organization/{id}', 'OrganizationController@download');

    Route::post('/range', 'ExploreController@filterValues1');

    // this is for campaign and message
    Route::resource('campaigns', 'CampaignController');
    Route::post('updateStatus', 'CampaignController@updateStatus')->name('updateStatus');
    Route::post('deleteCampaigns', 'CampaignController@deleteCampaigns')->name('deleteCampaigns');
    Route::post('deleteRecipient', 'CampaignController@deleteRecipient')->name('deleteRecipient');
    Route::get('/confirm/{id}', 'CampaignController@confirm');
    Route::get('/campaign_report/{id}', 'CampaignController@campaign_report')->name('campaign_report');
    Route::resource('messages', 'MessageController');
    Route::post('send_campaign/{id}', 'BulkSmsController@send_campaign')->name('send_campaign');
    Route::get('message/sent', 'MessageController@messages_sent');
    Route::get('/message/recieved', 'MessageController@messages_recieved');
    Route::post('connect_compaign', 'MessageController@connect_compaign')->name('connect_compaign');
    Route::post('connect_group', 'MessageController@connect_group')->name('connect_group');
    Route::post('getContact', 'MessageController@getContact')->name('getContact');
    Route::get('messagesSetting', 'MessageController@messagesSetting')->name('messagesSetting');
    Route::post('saveMessageCredential', 'MessageController@saveMessageCredential')->name('saveMessageCredential');

    Route::post('/checkSendgrid', 'HomeController@checkSendgrid')->name('checkSendgrid');
    Route::post('/checkTwillio', 'HomeController@checkTwillio')->name('checkTwillio');
    Route::post('/create_group', 'MessageController@create_group')->name('create_group');
    Route::get('download_attachment/{id}', 'CampaignController@download_attachment');

    Route::post('send_message/{id}', 'BulkSmsController@send_message')->name('send_message');
    Route::post('group_message/{id}', 'BulkSmsController@group_message')->name('group_message');
});

Route::resource('login_register_edit', 'EditLoginRegisterController');
Route::group(['middleware' => ['web', 'auth', 'permission']], function () {
    Route::get('dashboard', ['uses' => 'HomeController@dashboard', 'as' => 'home.dashboard']);

    Route::resource('pages', 'PagesController');
    //users
    Route::resource('user', 'UserController');
    Route::get('user/{user}/permissions', ['uses' => 'UserController@permissions', 'as' => 'user.permissions']);
    Route::post('user/{user}/save', ['uses' => 'UserController@save', 'as' => 'user.save']);
    Route::get('user/{user}/activate', ['uses' => 'UserController@activate', 'as' => 'user.activate']);
    Route::get('user/{user}/deactivate', ['uses' => 'UserController@deactivate', 'as' => 'user.deactivate']);
    Route::post('user/ajax_all', ['uses' => 'UserController@ajax_all']);

    //roles
    Route::resource('role', 'RoleController');
    Route::get('role/{role}/permissions', ['uses' => 'RoleController@permissions', 'as' => 'role.permissions']);
    Route::post('role/{role}/save', ['uses' => 'RoleController@save', 'as' => 'role.save']);
    Route::post('role/check', ['uses' => 'RoleController@check']);

    Route::get('/logout', ['uses' => 'Auth\LoginController@logout']);

    Route::get('/sync_services', ['uses' => 'ServiceController@airtable']);
    Route::get('/sync_locations', ['uses' => 'LocationController@airtable']);
    Route::get('/sync_organizations', ['uses' => 'OrganizationController@airtable']);
    Route::get('/sync_contact', ['uses' => 'ContactController@airtable']);
    Route::get('/sync_phones', ['uses' => 'PhoneController@airtable']);
    Route::get('/sync_address', ['uses' => 'AddressController@airtable']);
    Route::get('/sync_schedule', ['uses' => 'ScheduleController@airtable']);
    Route::get('/sync_taxonomy', ['uses' => 'TaxonomyController@airtable']);
    Route::get('/sync_details', ['uses' => 'DetailController@airtable']);
    Route::get('/sync_service_area', ['uses' => 'AreaController@airtable']);

    //Route::get('/tb_projects', ['uses' => 'ProjectController@index']);
    Route::resource('tb_services', 'ServiceController');
    Route::resource('tb_locations', 'LocationController');
    Route::resource('tb_organizations', 'OrganizationController');
    Route::resource('tb_contact', 'ContactController');
    Route::resource('tb_phones', 'PhoneController');
    Route::resource('tb_address', 'AddressController');
    Route::resource('tb_schedule', 'ScheduleController');
    Route::resource('tb_service_area', 'AreaController');

    Route::get('/tb_regular_schedules', function () {
        return redirect('/tb_schedule');
    });

    Route::resource('tb_taxonomy', 'TaxonomyController');
    Route::resource('tb_details', 'DetailController');
    Route::resource('tb_languages', 'LanguageController');
    Route::resource('tb_accessibility', 'AccessibilityController');

    Route::get('/tb_accessibility_for_disabilites', function () {
        return redirect('/tb_accessibility');
    });

    Route::get('/tb_services_taxonomy', function () {
        return redirect('/tb_services');
    });

    Route::get('/tb_services_location', function () {
        return redirect('/tb_locations');
    });

    Route::resource('layout_edit', 'EditlayoutController');
    Route::resource('home_edit', 'EdithomeController');
    Route::resource('about_edit', 'EditaboutController');
    // Route::resource('login_register_edit', 'EditLoginRegisterController');

    // Route::resource('meta_filter', 'MetafilterController');

    Route::resource('map', 'MapController');
    Route::get('/scan_ungeocoded_location', 'MapController@scan_ungeocoded_location');
    Route::get('/scan_enrichable_location', 'MapController@scan_enrichable_location');
    Route::get('/apply_geocode', 'MapController@apply_geocode');
    Route::get('/apply_enrich', 'MapController@apply_enrich');

    Route::get('/import', ['uses' => 'PagesController@import']);
    Route::get('/export', ['uses' => 'PagesController@export']);
    Route::get('/meta_filter', ['uses' => 'PagesController@metafilter']);
    Route::post('/meta/{id}', 'PagesController@metafilter_save');

    Route::post('/taxonomy_filter', 'PagesController@taxonomy_filter');
    Route::post('/postal_code_filter', 'PagesController@postal_filter');

    Route::post('/meta_filter', 'PagesController@operation');
    Route::post('/meta_delete_filter', 'PagesController@delete_operation');
    Route::post('/meta_filter/{id}', 'PagesController@metafilter_edit');

    Route::resource('data', 'DataController');
    Route::resource('analytics', 'AnalyticsController');

    Route::post('/organization_delete_filter', 'OrganizationController@delete_organization');
    Route::post('/facility_delete_filter', 'LocationController@delete_facility');
    Route::post('/contact_delete_filter', 'ContactController@delete_contact');
    Route::post('/group_delete_filter', 'GroupController@delete_group');
    Route::post('/group_remove_members', 'GroupController@group_remove_members');

    // new d9

});
Route::resource('religions', 'backend\ReligionsController');
Route::resource('organizationTypes', 'backend\organizationTypeController');
