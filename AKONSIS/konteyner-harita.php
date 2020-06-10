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
                    function initMap() {
                        lastWindow = null;
                        var map = new google.maps.Map(document.getElementById('map'), {
                            center: new google.maps.LatLng(41.22264, 32.6598061),
                            zoom: 14
                        });
                        var konteynerListesi = firebase.database().ref().child('konteynerListesi');
                        var count = 0;
                        var marker;
                        var markersArray = [];
                        var markerLatLong = "";

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
                                if (item.val().level > 75) {
                                    var infowindow = new google.maps.InfoWindow();
                                    var itemNo = '%' + item.val().level.toString();
                                    marker = new google.maps.Marker({
                                        position: new google.maps.LatLng(item.val().latMap, item.val().longMap),
                                        map: map,
                                        label: {text: itemNo, color: "white", fontSize: "11px"},
                                        // icon: image,
                                    });
                                    markersArray.push(marker);
                                    markerLatLong += item.val().latMap + "," + item.val().longMap + "|";
                                    count++;
                                    marker.addListener('click', function () {

                                        infowindow.setContent(
                                            '<span style="font-size: 20px;">' +
                                            '<b style="font-size:15px;">Konteyner No:</b> ' + item.val().no.toString() + '<br/>' +
                                            '<b style="font-size:15px;">Seviye:</b> %' + item.val().level.toString() +
                                            '</span>'
                                        );
                                        if (lastWindow) lastWindow.close();
                                        infowindow.open(map, this);
                                        lastWindow = infowindow;
                                    });
                                }
                            });

                        })


                    }

                </script>
                <script async defer
                        src="https://maps.googleapis.com/maps/api/js?key=YOUR API KEY&callback=initMap">
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
