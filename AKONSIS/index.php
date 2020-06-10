<?php require_once("includeTemplate/header.php"); ?>
<?php require_once("includeTemplate/menu.php"); ?>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-core.min.js"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-cartesian.min.js"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js"></script>
<div class="content-wrapper">
    <section class="content">
        <div class="col-lg-3 col-xs-6">
            <script>
                var toplamCount = [];
                var contList = firebase.database().ref().child('konteynerListesi');
                contList.once("value", function (snapshot) {
                    snapshot.forEach(function (item) {
                        toplamCount.push(item.val());
                    });
                    document.getElementById('tks').innerText = toplamCount.length - 1;
                    toplamCount = [];
                });

            </script>
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3 id="tks" style="font-size: 50px;"></h3>

                    <p>Toplam Konteyner Sayısı</p>
                </div>
                <div class="icon">
                    <i class="fa fa-dumpster"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <script>
                var doluCount = [];
                var contList = firebase.database().ref().child('konteynerListesi');
                contList.on("value", function (snapshot) {
                    snapshot.forEach(function (item) {
                        if (item.val().level > 75) {
                            doluCount.push(item.val().level);
                        }
                    });
                    document.getElementById('dks').innerText = doluCount.length;
                    doluCount = [];
                });

            </script>
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3 id="dks" style="font-size: 50px;"></h3>

                    <p>Dolu Konteyner Sayısı</p>
                </div>
                <div class="icon">
                    <i class="fa fa-dumpster-fire"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <script>
                var aracCount = [];
                var arac = firebase.database().ref().child('kullanicilar');
                arac.once("value", function (snapshot) {
                    snapshot.forEach(function (aracSay) {
                        aracCount.push(aracSay.val());
                    });
                    document.getElementById('as').innerText = aracCount.length;
                    aracCount = [];
                });
            </script>
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3 id="as" style="font-size: 50px;"></h3>

                    <p>Toplam Araç Sayısı</p>
                </div>
                <div class="icon">
                    <i class="fa fa-truck"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <script>
                var capacity = [];
                var topCap = 0;
                var conCap = firebase.database().ref().child('konteynerListesi');
                conCap.on("value", function (snapshot) {
                    snapshot.forEach(function (item) {
                            capacity.push(item.val().kapasite);

                    });
                    for(var i=0;i<capacity.length;i++){
                        topCap += capacity[i];
                    }
                    document.getElementById('cap').innerText = topCap;
                    topCap=0;
                });

            </script>
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3 id="cap" style="font-size: 50px;"></h3>

                    <p>Konteyner Toplam Kapasite</p>
                </div>
                <div class="icon">
                    <i class="fa fa-warehouse"></i>
                </div>
            </div>
        </div>
        <div class="content container-fluid col-md-12">
            <div class="box box-primary" style="padding: 0px 10px; width: %100;">
                <h4>Konteyner Doluluk Oranları</h4>
                <div id="container" style="padding: 0px; margin: 0px; width: %100; height: 500px;"></div>
                <script>
                    var dizi = [];
                    var count = 0;
                    anychart.onDocumentReady(function () {
                        var updateControl = 0;
                        var konteynerListesi = firebase.database().ref().child('konteynerListesi');
                        konteynerListesi.on("value", function (snapshot) {
                            snapshot.forEach(function (item) {
                                if (updateControl == 1) {
                                    location.reload();
                                }
                                dizi.push(["Konteyner" + (count), item.val().level],);
                                count++;
                            });
                            count = 0;
                            updateControl = 1;

                            var dataSet = anychart.data.set(dizi);
                            // map the data
                            // create a chart
                            var chart = anychart.column();
                            chart.yScale().minimum(0);
                            chart.yScale().maximum(100);
                            // create a series and set the data
                            var mapping = dataSet.mapAs({x: 0, value: 1});

                            var series = chart.column(mapping);
                            series.name("Seviye");


                            // set the container id
                            chart.container("container");
                            // initiate drawing the chart
                            chart.draw();
                            dizi = [];
                        });
                    });
                </script>
            </div>
        </div>

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
