"ajax": function (data, callback, settings) {
var start = data.start;
var length = data.length;
var search_term = data.search.value;
var filter_contact_borough = data.columns[26].search.value;
var filter_contact_zipcode = data.columns[27].search.value;
var filter_tag = data.columns[30].search.value;
var filter_contact_languages = data.columns[11].search.value;
var filter_contact_address = data.columns[25].search.value;
var filter_contact_type = data.columns[8].search.value;
var filter_religion = data.columns[21].search.value;
var filter_faith_tradition = data.columns[22].search.value;
var filter_denomination = data.columns[23].search.value;
var filter_judicatory_body = data.columns[24].search.value;
var filter_email = data.columns[14].search.value;
var filter_phone = data.columns[16].search.value;
var check_marks = sessionStorage.getItem('check_marks');

console.log(filter_denomination);
// console.log(data.columns[11],);

$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
}
});
$('#waiting').show();
$.ajax({
type: "POST",
url: "/get_all_contacts",
data: {
start: start,
length: length,
search_term: search_term,
filter_contact_borough: filter_contact_borough,
filter_contact_zipcode: filter_contact_zipcode,
filter_contact_languages: filter_contact_languages,
filter_contact_address: filter_contact_address,
filter_contact_type: filter_contact_type,
filter_religion: filter_religion,
filter_faith_tradition: filter_faith_tradition,
filter_denomination: filter_denomination,
filter_judicatory_body: filter_judicatory_body,
filter_email: filter_email,
filter_phone: filter_phone,
filter_tag: filter_tag,
filter_map: filter_map,
},
success: function (response) {
$('#waiting').hide();
console.log(response);
callback({
draw: data.draw,
data: response.data,
recordsTotal: response.recordsTotal,
recordsFiltered: response.recordsFiltered,
marks: response.marks
});
$('button.delete-td').on('click', function(e) {
e.preventDefault();
var value = $(this).val();
$('input#contact_recordid').val(value);
});

console.log(check_marks);
// if (sessionStorage.getItem('check_marks') == 'true') {
// return;
// }

var locations = response.filtered_locations_list;
var maplocation = <?php print_r(json_encode($map)) ?>;
if(maplocation.active == 1){
avglat = maplocation.lat;
avglng = maplocation.long;
zoom = maplocation.zoom;
}
else
{
avglat = 40.730981;
avglng = -73.998107;
zoom = 12 * 15;
}
latitude = locations[0].location_latitude;
longitude = locations[0].location_longitude;
if(latitude == null){
latitude = avglat;
longitude = avglng;
}
var map = new google.maps.Map(document.getElementById('map'), {
zoom: zoom,
center: {lat: parseFloat(avglat), lng: parseFloat(avglng)}
});

if (sessionStorage.getItem('check_marks') == 'true') {
var poly_coordinate_list = sessionStorage.getItem('poly_coordinate_list');
var point_list = JSON.parse(poly_coordinate_list);
var poly = new google.maps.Polygon({
paths: point_list,
strokeColor: '#000000',
strokeOpacity: 1.0,
strokeWeight: 3
});
poly.setMap(map);
}
else {
var poly = new google.maps.Polygon({
strokeColor: '#000000',
strokeOpacity: 1.0,
strokeWeight: 3
});
}

var marks = [];

$('#enable-polygon-btn').on('click', function(e) {
e.preventDefault();
poly = new google.maps.Polygon({
strokeColor: '#000000',
strokeOpacity: 1.0,
strokeWeight: 3
});
poly.setMap(map);
map.addListener('click', addLatLng);
});

$('#filter-polygon-btn').on('click', function(e) {
e.preventDefault();
google.maps.event.clearListeners(map, 'click');
});

$('#reset-filter-polygon-btn').on('click', function(e) {
e.preventDefault();
google.maps.event.clearListeners(map, 'click');
poly.setMap(null);
clearMarkers();
marks = [];
});

// Sets the map on all markers in the array.
function setMapOnAll(map) {
for (var i = 0; i < marks.length; i++) { marks[i].setMap(map); } } // Removes the markers from the map, but keeps them
    in the array. function clearMarkers() { setMapOnAll(null); } var locations_info=locations.map((value)=> {
    if (value) {
    return {
    lat: parseFloat(value.location_latitude),
    lng: parseFloat(value.location_longitude),
    location_name: value.location_name,
    location_type: value.location_type
    }
    }
    })

    var markers = locations_info.map(function(location, i) {
    var position = {
    lat: location.lat,
    lng: location.lng
    }
    var marker = new google.maps.Marker({
    position: position,
    map: map,
    title: location.location_name
    });
    return marker;
    });

    var markerCluster = new MarkerClusterer(map, markers,
    + {imagePath: "{{asset('images/m')}}"});

    function addLatLng(event) {
    var path = poly.getPath();
    // Because path is an MVCArray, we can simply append a new coordinate
    // and it will automatically appear.
    path.push(event.latLng);

    // Add a new marker at the new plotted point on the polyline.
    var marker = new google.maps.Marker({
    position: event.latLng,
    title: '#' + path.getLength(),
    map: map
    });
    marks.push(marker);
    }

    google.maps.Polygon.prototype.Contains = function (point) {

    var crossings = 0,
    path = this.getPath();
    // for each edge
    for (var i = 0; i < path.getLength(); i++) { var a=path.getAt(i), j=i + 1; if (j>= path.getLength()) {
        j = 0;
        }
        var b = path.getAt(j);
        if (rayCrossesSegment(point, a, b)) {
        crossings++;
        }
        }
        // odd number of crossings?
        return (crossings % 2 == 1);
        function rayCrossesSegment(point, a, b) {
        var px = point.lng(),
        py = point.lat(),
        ax = a.lng(),
        ay = a.lat(),
        bx = b.lng(),
        by = b.lat();
        if (ay > by) {
        ax = b.lng();
        ay = b.lat();
        bx = a.lng();
        by = a.lat();
        }
        // alter longitude to cater for 180 degree crossings
        if (px < 0) { px +=360; } if (ax < 0) { ax +=360; } if (bx < 0) { bx +=360; } if (py==ay || py==by) py
            +=0.00000001; if ((py> by || py < ay) || (px> Math.max(ax, bx))) return false;
                if (px < Math.min(ax, bx)) return true; var red=(ax !=bx) ? ((by - ay) / (bx - ax)) : Infinity; var
                    blue=(ax !=px) ? ((py - ay) / (px - ax)) : Infinity; return (blue>= red);
                    }
                    };

                    $('#filter-polygon-btn').on('click', function(e) {
                    e.preventDefault();
                    var filtered_points = [];
                    // var point = new google.maps.LatLng(41.781227, -88.141844);
                    console.log(markers[0].position.lng());
                    console.log(markers[0].position.lat());
                    for (i = 0; i < markers.length; i++) { var point=new google.maps.LatLng(markers[i].position.lat(),
                        markers[i].position.lng()); if (poly.Contains(point)) { var lat=markers[i].position.lat(); var
                        lng=markers[i].position.lng(); filtered_points.push({ lat: lat, lng: lng }); } }
                        console.log(filtered_points); filter_map=JSON.stringify(filtered_points);
                        dataTable.ajax.reload(); sessionStorage.setItem('check_marks', 'true' );
                        console.log('=========after filter===========');
                                console.log(marks); 

                                var poly_coordinate_list = [];
                                for(var i = 0; i < marks.length; i ++) {
                                    var poly_coordinate = {
                                        lat: marks[i].position.lat(), 
                                        lng: marks[i].position.lng()
                                    };
                                    poly_coordinate_list.push(poly_coordinate);
                                }
                                sessionStorage.setItem(' poly_coordinate_list', JSON.stringify(poly_coordinate_list));
                        console.log(poly_coordinate_list); }); $('#download_pdf').on('click', function(e) {
                        e.preventDefault(); var center_lat=map.center.lat(); var center_lng=map.center.lng(); // var
                        zoom=map.zoom; var zoom=9; var maptype=map.mapTypeId; var
                        img_url='https://maps.googleapis.com/maps/api/staticmap?center=' + center_lat + ', ' +
                        center_lng + '&zoom=' +zoom+'&size=600x300&maptype=' + maptype + '
                        &key=AIzaSyDHW59pLhUQA4IODjApYTVnBdav32ORYYA' for(i=0; i < Math.min(350, markers.length); i ++)
                        { var lat=markers[i].position.lat(); var lng=markers[i].position.lng(); var
                        markers_param="&markers=" + lat + ", " + lng; img_url +=markers_param; } console.log(img_url);
                        $('input#contact_map_image').val(img_url); $('#contacts_form').submit(); }); }, error: function
                        (data) { if (data.status==0 || data.status==414) { console.log('Contacts in filtered Ploygon are
                        too much. Enlarge Map and filter in more detailed area.'); } console.log('Error:', data); } });
                        },