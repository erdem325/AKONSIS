<?php require_once("includeTemplate/header.php"); ?>
<?php require_once("includeTemplate/menu.php"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-circle"></i> Tüm Konteyner Haritası
            <small>Harita anlık olarak güncellenir</small>
        </h1>
    </section>
    <section class="content container-fluid">
        <div class="box box-success">
            <div style="padding: 20px;">
                <div id="map" style="width: 100%; height: 750px;"></div>

                <script>
                    function initMap() {
                        lastWindow = null;
                        var dolulukOrani = document.getElementById('dolulukOrani');
                        var map = new google.maps.Map(document.getElementById('map'), {
                            center: new google.maps.LatLng(41.22264, 32.6598061),
                            zoom: 14
                        });

                        var infoWindow = new google.maps.InfoWindow;
                        // var image = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';
                        var konteynerListesi = firebase.database().ref().child('konteynerListesi');
                        var marker;
                        var count = 0;
                        var markerAddress = "";
                        var markerAddressArray = [];
                        var markersArray = [];

                        function clearOverlays() {
                            for (var i = 0; i < markersArray.length; i++) {
                                markersArray[i].setMap(null);
                            }
                            markersArray.length = 0;
                        }

                        konteynerListesi.on("value", function (snapshot) {
                            clearOverlays();

                            snapshot.forEach(function (item) {
                                // dolulukOrani.appendChild(item.val().level);
                                var latlng = new google.maps.LatLng(item.val().latMap, item.val().longMap);
                                var infowindow = new google.maps.InfoWindow();
                                var itemNo = '%' + item.val().level.toString();
                                marker = new google.maps.Marker({
                                    position: latlng,
                                    map: map,
                                    label: {text: itemNo, color: "white", fontSize: "11px"},
                                    // icon: image,
                                });
                                count++;
                                markersArray.push(marker);
                                markerAddressArray.push(item.val().latMap + "," + item.val().longMap);
                                marker.addListener('click', function () {
                                    var geocoder = new google.maps.Geocoder();
                                    geocoder.geocode({'latLng': latlng}, function (results, status) {
                                        // markerAddress = results[0].address_components[1].long_name;
                                        markerAddress = results[1].formatted_address;
                                        infowindow.setContent(
                                            '<span style="font-size: 15px;">' +
                                            '<b>Konteyner No:</b> ' + item.val().no.toString() + '<br/>' +
                                            '<b>Seviye:</b> %' + item.val().level.toString() + '<br/>' +
                                            '<b>Adres:</b> ' + markerAddress +
                                            '</span>'
                                        );
                                    });

                                    if (lastWindow) lastWindow.close();
                                    infowindow.open(map, this);
                                    lastWindow = infowindow;
                                });

                            })
                            // for(var i=0;i<markerAddressArray.length;i++){
                            //     geocoderGet(markerAddressArray[i]);
                            // }
                        })
                        console.log(count);
                    }

                    function geocoderGet(latLng) {

                        $.ajax({
                            url: 'https://reverse.geocoder.api.here.com/6.2/reversegeocode.json',
                            type: 'GET',
                            dataType: 'jsonp',
                            jsonp: 'jsoncallback',
                            data: {
                                prox: latLng,
                                mode: 'retrieveAddresses',
                                maxresults: '1',
                                gen: '9',
                                app_id: '39e7ABT6iNO3Wr3qWATq',
                                app_code: 'vT8jUsqhtYrGzaD7dTpzHQ'
                            },
                            success: function (data) {
                                // console.log(latLng + "-" + data.Response.View[0].Result[0].Location.Address.District);
                                // console.log(data[0].View[2].Result[5].Address[5].Label);
                            }
                        });


                        // var geocoder = new google.maps.Geocoder();
                        // geocoder.geocode({'latLng': latLng}, function(results, status) {
                        //   console.log(results[1].formatted_address);
                        // });
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

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->

</body>
</html>
