<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RouteWebService</title>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <link rel="stylesheet" type="text/css" href="include/main.css">

    <script src="https://www.gstatic.com/firebasejs/5.7.2/firebase.js"></script>
    <!--    <script src="https://www.gstatic.com/firebasejs/5.7.2/firebase-auth.js"></script>-->
    <script src="https://www.gstatic.com/firebasejs/5.7.2/firebase-database.js"></script>
    <script src="include/firebase_config.js"></script>
    <script src="include/main.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
</head>
<body>
<p></p>
<script>
    var doluKont = {"locations": [], "containerNo": []};
    var konteynerListesi = firebase.database().ref().child('konteynerListesi');
    var toplam = 0;
    var seviye = 0;
    konteynerListesi.on("value", function (snapshot) {
        snapshot.forEach(function (item) {
            if (item.val().no == 0) {
                doluKont['locations'].push("[" + item.val().longMap + "," + item.val().latMap + "]");
                doluKont['containerNo'].push(item.val().no);
            }
            if (item.val().level > 75) {
                seviye = item.val().level * item.val().kapasite / 100;
                toplam += seviye;
                if (toplam > 20000) {
                    toplam -= seviye;
                } else {
                    doluKont['locations'].push("[" + item.val().longMap + "," + item.val().latMap + "]");
                    doluKont['containerNo'].push(item.val().no);
                }
            }
        });
        // console.log(doluKont['containerNo']);
        // console.log("toplam:" + toplam);
        OpenRouteService(doluKont);
    });

    function OpenRouteService(doluKont) {
        // OpenRouteService DistanceMatrixAPI
        $.ajax(
            {
                "url": "https://api.openrouteservice.org/v2/matrix/driving-car",
                "method": "POST",
                "headers": {
                    "Authorization": "OpenRoute Service Key",
                    "Content-Type": "application/json"
                },
                "data": "{\"locations\":[" + doluKont['locations'] + "],\"metrics\":[\"distance\"],\"units\":\"m\"}"
            }
        ).done(function (response) {
            RouteWebService(response);
        });
    }

    function RouteWebService(response) {
        // Python Ortools Route API
        $.post("http://68.183.212.83/post", {
            "data": JSON.stringify(response.distances)
        }, function (data) {
            RouteContainerNoChange(data, doluKont);
        });
    }

    function RouteContainerNoChange(data, doluKont) {
        str = data.replace(/\'/g, '');

        var obj = JSON.parse(str);

        for (var k in obj) {
            var jsonRoute = obj[k]['rota'];
            var virgulsuz = jsonRoute.split(",");
            var newSplit = "";
            for (var j in virgulsuz) {
                virgulsuz[j] = doluKont['containerNo'][virgulsuz[j]]
                newSplit += virgulsuz[j] + ",";
            }
            obj[k]['rota'] = newSplit;
        }

        $("p").html(JSON.stringify(obj));

        firebase.database().ref("rotalar").update({
            "bkEDgODAWiS0LlfjiJ98ALijOlA2": obj[0],
            "mg7ghHm7pDOdeSugkZCCBS7vWMO2": obj[1],
            "quIgMoTIn8RZ8UbC9DiBN7X9epx1": obj[2]
        }, function (data2) {
            // FCMNotificationService();
        });
    }

    function FCMNotificationService() {
        // Firebase Cloud Messaging API
        $.ajax({
            type: 'POST',
            url: "https://fcm.googleapis.com/fcm/send",
            headers: {
                Authorization: 'key=' + 'Firebase API KEY'
            },
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
                "notification": {
                    "title": "CONTAINER INFORMATION SYSTEM",
                    "body": "The routes created. Please check it.",
                },
                // "to": "/topics/Sofor-mg7ghHm7pDOdeSugkZCCBS7vWMO2",
                "to": "/topics/Soforler",
                "data": {
                    "title": "CONTAINER INFORMATION SYSTEM",
                    "body": "The routes created. Please check it."
                }
            }),
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.log(status);
            }
        });
    }
</script>
</body>
</html>