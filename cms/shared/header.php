<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ZOL Coverage Map CMS</title>
    <link href="/img/zol_logo_broadband.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <link href="/img/zol_logo_broadband.png" rel="icon" type="image/vnd.microsoft.icon" />
    <!-- Bootstrap -->
    <link href="/cms/theme/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/cms/theme/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/cms/theme/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="/cms/theme/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="/cms/theme/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="/cms/theme/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
    <!-- bootstrap-daterangepicker -->
    <link href="/cms/theme/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="/cms/theme/css/custom.min.css" rel="stylesheet">
    <link href="/cms/theme/css/custom_v1.css" rel="stylesheet"> </head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="clearfix"></div>
                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic"> <img class="logo" src="/cms/theme/images/logo.png" width="80px" /> </div>
                        <div class="profile_info"> <span></span> </div>
                    </div>
                    <!-- /menu profile quick info -->
                    <br />
                    <br />
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li><a href="/cms/"><i class="fa fa-home"></i> Dashboard</a></li>
                                <li><a><i class="fa fa-edit"></i> Wimax Base Stations <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="/cms/wimax/">All</a></li>
                                        <li><a href="/cms/wimax/index.php?a=1">Live</a></li>
                                        <li><a href="/cms/wimax/index.php?d=1">Decomissioned</a></li>
                                        <li><a href="/cms/wimax/index.php?ifa=1">In Fibroniks Area</a></li>
                                        <li><a href="/cms/wimax/index.php?nifa=1">Not in Fibroniks Area</a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-edit"></i> WiBroniks Base Stations <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="/cms/lte/">All</a></li>
                                        <li><a href="/cms/lte/index.php?a=1">Live</a></li>
                                        <li><a href="/cms/lte/index.php?d=1">Pending</a></li>
                                        <li><a href="/cms/lte/index.php?ifa=1">In Fibroniks Area</a></li>
                                        <li><a href="/cms/lte/index.php?nifa=1">Not in Fibroniks Area</a></li>
                                    </ul>
                                </li>
                                <?php
                                    $accessLevel = (isset($_COOKIE["accessLevel"])) ? $_COOKIE["accessLevel"] : 0;
                                    if($accessLevel > 1 ){
                                ?>
                                    <li><a href="/cms/users/"><i class="fa fa-users"></i> Users</a></li>
                                <?php } ?>
                            </ul> <a href="/cms/index.php?status=logout" class="btn btn-primary" style="margin: 13px 15px 12px;">Logout</a> </div>
                    </div>
                    <!-- /sidebar menu -->
                </div>
            </div>
            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle"> <a id="menu_toggle"><i class="fa fa-bars"></i></a> </div>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->