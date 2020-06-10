<?php require_once("includeTemplate/header.php"); ?>
<?php require_once("includeTemplate/menu.php"); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-circle"></i> Konteyner Harita <span class="badge canli blink">CANLI</span>
            <small>Harita anlık olarak güncellenir</small>
        </h1>
    </section>
    <section class="content container-fluid">
        <div class="box box-success">
            <div style="padding: 20px;">
                <div id="map" style="width: 100%; height: 750px;"></div>
                <script>
                    var map;
                    function initMap() {
                        var directionsService = new google.maps.DirectionsService();
                        //var directionsDisplay = new google.maps.DirectionsRenderer;
                        var mapOptions = {
                            zoom: 1,
                            center: {lat: 41.185897, lng: 32.649667}
                        };
                        map = new google.maps.Map(document.getElementById('map'),mapOptions);
                        calculateAndDisplayRoute(directionsService);
                    }

                    function calculateAndDisplayRoute(directionsService) {
                        var renderArray = [];
                        var requestArray = [];
                        var garbage = new Map();
                        var markersArray = new Map();
                        var updateControl = 0;
                        var konteynerListesi = firebase.database().ref().child('konteynerListesi');

                        konteynerListesi.on("value", function (snapshot) {
                            snapshot.forEach(function (item) {
                                markersArray = [];
                                // console.log(item.val().level);
                                markersArray.no = item.val().no;
                                markersArray.latMap = item.val().latMap;
                                markersArray.longMap = item.val().longMap;
                                markersArray.level = item.val().level;
                                garbage.set(markersArray.no, markersArray);

                            })
                        });
                        // console.log(garbage);
                        var rotalar="";
                        var rotaDizi = [];
                        var rota = firebase.database().ref().child('rotalar');
                        rota.on("value",function (snapshot) {
                            snapshot.forEach(function (item) {
                                if (updateControl == 1) {
                                    location.reload();
                                }
                                var baslangicUzakligi;
                                var waypointsLatLong;
                                var rotaDuzenleme = [];
                                var waypoints = [];
                                var baslangicLat;
                                var baslangicLong;
                                var baslangicLatLong;
                                var bitisLatLong;
                                var bitisUzakligi;
                                var bitisLong;
                                var bitisLat;
                                rotalar = "";
                                rotaDizi = [];
                                rotalar += item.val().rota;
                                // console.log(rotalar);
                                    rotaDizi = rotalar.split(",");
                                    rotaDizi.pop();
                                    rotaDizi.pop();
                                    rotaDizi.shift();
                                console.log(rotaDizi);
                                    if(rotaDizi.length > 1) {

                                        for (var j = 0; j < rotaDizi.length; j++) {
                                            var indis = parseInt(rotaDizi[j]);
                                            waypointsLatLong = garbage.get(indis).latMap + "," + garbage.get(indis).longMap;
                                            rotaDuzenleme.push(garbage.get(indis).latMap + "," + garbage.get(indis).longMap);
                                            waypoints.push({location: waypointsLatLong, stopover: true});
                                        }
                                        baslangicLatLong = rotaDuzenleme.shift();
                                        bitisLatLong = rotaDuzenleme.pop();
                                        //console.log(baslangic);
                                        baslangicUzakligi = baslangicLatLong.indexOf(",");
                                        baslangicLong = parseFloat(baslangicLatLong.slice(baslangicUzakligi + 1, baslangicLatLong.length));
                                        baslangicLat = parseFloat(baslangicLatLong.slice(0, baslangicUzakligi - 1));

                                        bitisUzakligi = bitisLatLong.indexOf(",");
                                        bitisLong = parseFloat(bitisLatLong.slice(bitisUzakligi + 1, bitisLatLong.length));
                                        bitisLat = parseFloat(bitisLatLong.slice(0, baslangicUzakligi - 1));
                                        // console.log(bitis);
                                        // console.log(baslangicLat);
                                        // console.log(baslangicLong);
                                        // console.log(bitisLat);
                                        // console.log(bitisLong);
                                        waypoints.pop();
                                        waypoints.shift();

                                        var request = {
                                            origin: new google.maps.LatLng(baslangicLat, baslangicLong),
                                            destination: new google.maps.LatLng(bitisLat, bitisLong),
                                            waypoints: waypoints,
                                            optimizeWaypoints: true,
                                            travelMode: 'DRIVING'
                                        };

                                        requestArray.push({"request": request});

                                        // directionsService.route({
                                        //}, function (_response,_status) {
                                        //  if (_status === 'OK') {
                                        //    directionsDisplay.setDirections(_response);
                                        //}
                                        //else{
                                        //  window.alert('Directions request failed due to ' + _status);
                                        //}
                                        //});
                                    }

                            });
                            updateControl = 1;
                            var i = 0;
                            function submitRequest(){
                                directionsService.route(requestArray[i].request, directionResults);
                            }
                            function directionResults(result, status) {
                                if (status == google.maps.DirectionsStatus.OK) {
                                    // Create a unique DirectionsRenderer 'i'
                                    renderArray[i] = new google.maps.DirectionsRenderer();
                                    renderArray[i].setMap(map);
                                    // Some unique options from the colorArray so we can see the routes
                                    // Use this new renderer with the result
                                    renderArray[i].setDirections(result);
                                    // and start the next request
                                    nextRequest();
                                }

                            }
                            function nextRequest(){
                                // Increase the counter
                                i++;
                                // Make sure we are still waiting for a request
                                if(i >= requestArray.length){
                                    // No more to do
                                    return;
                                }
                                // Submit another request
                                submitRequest();
                            }
                            submitRequest();
                        });
                    }

                </script>
                <script async defer
                        src="https://maps.googleapis.com/maps/api/js?key=YOUR GOOGLE API KEY&callback=initMap">
                </script>
            </div>
        </div>
    </section>
</div>
<?php require_once("includeTemplate/footer.php"); ?>
<?php require_once("includeTemplate/sagpanel.php"); ?>


<!-- Add the sidebar's background. This div must be placed
immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>