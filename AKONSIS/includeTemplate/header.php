<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Konteyner Bilgi Sistemi</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">

    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/skins/skin-blue.css">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" type="text/css" href="include/main.css">

    <script src="https://www.gstatic.com/firebasejs/5.8.2/firebase.js"></script>
    <!--    <script src="https://www.gstatic.com/firebasejs/5.7.2/firebase-auth.js"></script>-->
    <script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-database.js"></script>
    <script src="https://www.gstatic.com/firebasejs/5.8.2/firebase-messaging.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="include/firebase_config.js"></script>
    <script src="include/main.js"></script>

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="index.php" class="logo">
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">Konteyner<b>BilgiSistemi</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><b>KARABÜK BELEDİYESİ</b></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <p>
                                    KARABÜK BELEDİYESİ
                                    <small>
                                        Temizlik İşleri Müdürlüğü
                                    </small>
                                </p>
                                <p>
                                    0370 413 19 97
                                </p>
                                <p>
                                    Çağrı Merkezi<br/>
                                    444 21 78
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <!--<li class="user-footer">-->
                            <!--<div class="pull-left">-->
                            <!--<a href="#" class="btn btn-default btn-flat">Profile</a>-->
                            <!--</div>-->
                            <!--<div class="pull-right">-->
                            <!--<a href="#" class="btn btn-default btn-flat">Sign out</a>-->
                            <!--</div>-->
                            <!--</li>-->
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-cogs"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
