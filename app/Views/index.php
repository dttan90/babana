<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Babana Admin</title>

    <!-- Fontfaces CSS-->
    <link href="frondend/css/font-face.css" rel="stylesheet" media="all">
    <link href="frondend/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="frondend/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="frondend/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="frondend/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="frondend/vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="frondend/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="frondend/vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="frondend/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="frondend/vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="frondend/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="frondend/vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="frondend/css/theme.css" rel="stylesheet" media="all">

    <!-- dtmlx 8 -->
    <link rel="stylesheet" href="<?php echo base_url('suite/codebase/suite.css?v=8.2.1'); ?>">

    <!-- bootrap css -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.4.95/css/materialdesignicons.css?v=6.4.2" media="all" rel="stylesheet" type="text/css">

    <style>
        html,
        body {
            width: 100%;
            height: 100%;
        }

        td {
            padding: 10px 15px !important;
        }

        th {
            padding: 10px 15px !important;
        }

        #layout {
            position: relative;
            padding: 25px 20px;
            min-height: 400px;
        }

        #sidebar {
            width: 20%;
            background: yellow;
        }

        #main {
            position: absolute;
            top: 25px;
            left: 20%;
            background: tomato;
            width: 79%;
        }

        .sidebar-customer {
            background-color: pink;
            width: 100%;
        }

        .main {
            padding: 20px;
        }

        .action-buttons {
            display: flex;
            justify-content: space-evenly;
            width: 100%;
        }

        .food-action-buttons {
            width: 90% !important;
            height: 60% !important;
            position: relative;
            top: -6px;
        }

        /* .btn-add-food {
            width: 90%;
            height: 35px;
        } */

        .dhx_demo-grid .print-button {
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: var(--dhx-font-size-normal) !important;
            width: 40% !important;
            height: 100% !important;
            padding: 0;
            color: white !important;
        }

        .dhx_demo-grid .edit-button {
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: var(--dhx-font-size-normal) !important;
            width: 25% !important;
            height: 80% !important;
            padding: 0;
            color: white !important;
        }

        .dhx_demo-grid .remove-button {
            cursor: pointer;
            color: white !important;
            transition: all 0.3s ease;
            font-size: var(--dhx-font-size-normal) !important;
            width: 25% !important;
            height: 80% !important;
            padding: 0;
        }

        .dhx_demo-grid .detail-button {
            cursor: pointer;
            color: white !important;
            transition: all 0.3s ease;
            font-size: var(--dhx-font-size-normal) !important;
            width: 100% !important;
            height: 80% !important;
            padding: 0;
            color: white;
        }

        .dhx_demo-grid .detail-edit-button {
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: var(--dhx-font-size-normal) !important;
            width: 45% !important;
            height: 80% !important;
            padding: 0;
            color: white !important;
        }

        .dhx_demo-grid .detail-remove-button {
            cursor: pointer;
            color: white !important;
            transition: all 0.3s ease;
            font-size: var(--dhx-font-size-normal) !important;
            width: 45% !important;
            height: 80% !important;
            padding: 0;
        }

        .dhx_demo-grid .detail-add-button {
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: var(--dhx-font-size-normal) !important;
            width: 90% !important;
            height: 80% !important;
            padding: 0;
            color: white !important;
        }

        .highlight-date {
            color: #fff;
        }

        .highlight-date:after {
            background-color: #ff5252;
            opacity: 1;
            z-index: -3;
        }

        .dhx-demo_grid-template {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .dhx-demo_grid-status {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            margin-right: 6px;
            font-size: var(--dhx-font-size-small);
        }

        .dhx-demo_grid-status--done {
            background-color: var(--dhx-color-success);
        }

        .dhx-demo_grid-status--delivered {
            /* background-color: #0dcaf0!important; */
            background-color: var(--dhx-color-primary);
        }

        .dhx-demo_grid-status--in-progress {
            /* background-color: var(--dhx-color-primary); */
            background-color: #ffc107 !important;
        }

        .dhx-demo_grid-status--not-started {
            /* background-color: var(--dhx-color-danger); */
            background-color: #6c757d !important;
        }

        .dhx-demo_grid-status--cancelled {
            background-color: var(--dhx-color-danger);
            /* background-color: #6c757d!important; */
        }

        .detail-btn {
            width: 100% !important;
            height: 80% !important;
            padding: 0;
            color: white;
        }
    </style>

</head>

<body class="animsition" style="height: 100%; width: 100%;">
    <div class="page-wrapper" style="height: 100%; width: 100%;">
        <!-- HEADER DESKTOP-->
        <header class="header-desktop3 d-none d-lg-block">
            <div class="section__content section__content--p35">
                <div class="header3-wrap">
                    <div class="header__logo">
                        <a href="frondend/#">
                            <img src="frondend/images/icon/logo-white.png" alt="CoolAdmin" />
                        </a>
                    </div>
                    <div class="header__navbar">
                        <ul class="list-unstyled">
                            <li class="has-sub">
                                <a href="frondend/#">
                                    <i class="fas fa-tachometer-alt"></i>Dashboard
                                    <span class="bot-line"></span>
                                </a>
                                <ul class="header3-sub-list list-unstyled">
                                    <li>
                                        <a href="#">Dashboard 1</a>
                                    </li>
                                    <li>
                                        <a href="#">Dashboard 2</a>
                                    </li>
                                    <li>
                                        <a href="#">Dashboard 3</a>
                                    </li>
                                    <li>
                                        <a href="#">Dashboard 4</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="frondend/#">
                                    <i class="fas fa-shopping-basket"></i>
                                    <span class="bot-line"></span>eCommerce</a>
                            </li>
                            <li>
                                <a href="frondend/table.html">
                                    <i class="fas fa-trophy"></i>
                                    <span class="bot-line"></span>Features</a>
                            </li>
                            <li class="has-sub">
                                <a href="frondend/#">
                                    <i class="fas fa-copy"></i>
                                    <span class="bot-line"></span>Chức năng</a>
                                <ul class="header3-sub-list list-unstyled">
                                    <li>
                                        <a href="frondend/login.html">Foods</a>
                                    </li>
                                    <li>
                                        <a href="frondend/register.html">Register</a>
                                    </li>
                                    <li>
                                        <a href="frondend/forget-pass.html">Forget Password</a>
                                    </li>


                                    <!-- <li>
                                        <a href="frondend/login.html">Login</a>
                                    </li>
                                    <li>
                                        <a href="frondend/register.html">Register</a>
                                    </li>
                                    <li>
                                        <a href="frondend/forget-pass.html">Forget Password</a>
                                    </li> -->
                                </ul>
                            </li>
                            <li class="has-sub">
                                <a href="frondend/#">
                                    <i class="fas fa-desktop"></i>
                                    <span class="bot-line"></span>UI Elements</a>
                                <ul class="header3-sub-list list-unstyled">
                                    <li>
                                        <a href="frondend/button.html">Button</a>
                                    </li>
                                    <li>
                                        <a href="frondend/badge.html">Badges</a>
                                    </li>
                                    <li>
                                        <a href="frondend/tab.html">Tabs</a>
                                    </li>
                                    <li>
                                        <a href="frondend/card.html">Cards</a>
                                    </li>
                                    <li>
                                        <a href="frondend/alert.html">Alerts</a>
                                    </li>
                                    <li>
                                        <a href="frondend/progress-bar.html">Progress Bars</a>
                                    </li>
                                    <li>
                                        <a href="frondend/modal.html">Modals</a>
                                    </li>
                                    <li>
                                        <a href="frondend/switch.html">Switchs</a>
                                    </li>
                                    <li>
                                        <a href="frondend/grid.html">Grids</a>
                                    </li>
                                    <li>
                                        <a href="frondend/fontawesome.html">FontAwesome</a>
                                    </li>
                                    <li>
                                        <a href="frondend/typo.html">Typography</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="header__tool">
                        <div class="header-button-item has-noti js-item-menu">
                            <i class="zmdi zmdi-notifications"></i>
                            <div class="notifi-dropdown notifi-dropdown--no-bor js-dropdown">
                                <div class="notifi__title">
                                    <p>You have 3 Notifications</p>
                                </div>
                                <div class="notifi__item">
                                    <div class="bg-c1 img-cir img-40">
                                        <i class="zmdi zmdi-email-open"></i>
                                    </div>
                                    <div class="content">
                                        <p>You got a email notification</p>
                                        <span class="date">April 12, 2018 06:50</span>
                                    </div>
                                </div>
                                <div class="notifi__item">
                                    <div class="bg-c2 img-cir img-40">
                                        <i class="zmdi zmdi-account-box"></i>
                                    </div>
                                    <div class="content">
                                        <p>Your account has been blocked</p>
                                        <span class="date">April 12, 2018 06:50</span>
                                    </div>
                                </div>
                                <div class="notifi__item">
                                    <div class="bg-c3 img-cir img-40">
                                        <i class="zmdi zmdi-file-text"></i>
                                    </div>
                                    <div class="content">
                                        <p>You got a new file</p>
                                        <span class="date">April 12, 2018 06:50</span>
                                    </div>
                                </div>
                                <div class="notifi__footer">
                                    <a href="frondend/#">All notifications</a>
                                </div>
                            </div>
                        </div>
                        <div class="header-button-item js-item-menu">
                            <i class="zmdi zmdi-settings"></i>
                            <div class="setting-dropdown js-dropdown">
                                <div class="account-dropdown__body">
                                    <div class="account-dropdown__item">
                                        <a href="frondend/#">
                                            <i class="zmdi zmdi-account"></i>Account</a>
                                    </div>
                                    <div class="account-dropdown__item">
                                        <a href="frondend/#">
                                            <i class="zmdi zmdi-settings"></i>Setting</a>
                                    </div>
                                    <div class="account-dropdown__item">
                                        <a href="frondend/#">
                                            <i class="zmdi zmdi-money-box"></i>Billing</a>
                                    </div>
                                </div>
                                <div class="account-dropdown__body">
                                    <div class="account-dropdown__item">
                                        <a href="frondend/#">
                                            <i class="zmdi zmdi-globe"></i>Language</a>
                                    </div>
                                    <div class="account-dropdown__item">
                                        <a href="frondend/#">
                                            <i class="zmdi zmdi-pin"></i>Location</a>
                                    </div>
                                    <div class="account-dropdown__item">
                                        <a href="frondend/#">
                                            <i class="zmdi zmdi-email"></i>Email</a>
                                    </div>
                                    <div class="account-dropdown__item">
                                        <a href="frondend/#">
                                            <i class="zmdi zmdi-notifications"></i>Notifications</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="account-wrap">
                            <div class="account-item account-item--style2 clearfix js-item-menu">
                                <div class="image">
                                    <img src="frondend/images/icon/avatar-01.jpg" alt="Luna" />
                                </div>
                                <div class="content">
                                    <a class="js-acc-btn" href="frondend/#">Luna</a>
                                </div>
                                <div class="account-dropdown js-dropdown">
                                    <div class="info clearfix">
                                        <div class="image">
                                            <a href="frondend/#">
                                                <img src="frondend/images/icon/avatar-01.jpg" alt=" Luna" />
                                            </a>
                                        </div>
                                        <div class="content">
                                            <h5 class="name">
                                                <a href="frondend/#">Luna</a>
                                            </h5>
                                            <span class="email">dathao280393@gmail.com</span>
                                        </div>
                                    </div>
                                    <div class="account-dropdown__body">
                                        <div class="account-dropdown__item">
                                            <a href="frondend/#">
                                                <i class="zmdi zmdi-account"></i>Account</a>
                                        </div>
                                        <div class="account-dropdown__item">
                                            <a href="frondend/#">
                                                <i class="zmdi zmdi-settings"></i>Setting</a>
                                        </div>
                                        <div class="account-dropdown__item">
                                            <a href="frondend/#">
                                                <i class="zmdi zmdi-money-box"></i>Billing</a>
                                        </div>
                                    </div>
                                    <div class="account-dropdown__footer">
                                        <a href="frondend/#">
                                            <i class="zmdi zmdi-power"></i>Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- END HEADER DESKTOP-->

        <!-- HEADER MOBILE-->
        <header class="header-mobile header-mobile-2 d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="frondend/index.html">
                            <img src="frondend/images/icon/logo-white.png" alt="CoolAdmin" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li class="has-sub">
                            <a class="js-arrow" href="frondend/#">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="frondend/index.html">Dashboard 1</a>
                                </li>
                                <li>
                                    <a href="frondend/index2.html">Dashboard 2</a>
                                </li>
                                <li>
                                    <a href="frondend/index3.html">Dashboard 3</a>
                                </li>
                                <li>
                                    <a href="frondend/index4.html">Dashboard 4</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="frondend/chart.html">
                                <i class="fas fa-chart-bar"></i>Charts</a>
                        </li>
                        <li>
                            <a href="frondend/table.html">
                                <i class="fas fa-table"></i>Tables</a>
                        </li>
                        <li>
                            <a href="frondend/form.html">
                                <i class="far fa-check-square"></i>Forms</a>
                        </li>
                        <li>
                            <a href="frondend/calendar.html">
                                <i class="fas fa-calendar-alt"></i>Calendar</a>
                        </li>
                        <li>
                            <a href="frondend/map.html">
                                <i class="fas fa-map-marker-alt"></i>Maps</a>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="frondend/#">
                                <i class="fas fa-copy"></i>Pages</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="frondend/login.html">Login</a>
                                </li>
                                <li>
                                    <a href="frondend/register.html">Register</a>
                                </li>
                                <li>
                                    <a href="frondend/forget-pass.html">Forget Password</a>
                                </li>
                            </ul>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="frondend/#">
                                <i class="fas fa-desktop"></i>UI Elements</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="frondend/button.html">Button</a>
                                </li>
                                <li>
                                    <a href="frondend/badge.html">Badges</a>
                                </li>
                                <li>
                                    <a href="frondend/tab.html">Tabs</a>
                                </li>
                                <li>
                                    <a href="frondend/card.html">Cards</a>
                                </li>
                                <li>
                                    <a href="frondend/alert.html">Alerts</a>
                                </li>
                                <li>
                                    <a href="frondend/progress-bar.html">Progress Bars</a>
                                </li>
                                <li>
                                    <a href="frondend/modal.html">Modals</a>
                                </li>
                                <li>
                                    <a href="frondend/switch.html">Switchs</a>
                                </li>
                                <li>
                                    <a href="frondend/grid.html">Grids</a>
                                </li>
                                <li>
                                    <a href="frondend/fontawesome.html">Fontawesome Icon</a>
                                </li>
                                <li>
                                    <a href="frondend/typo.html">Typography</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="sub-header-mobile-2 d-block d-lg-none">
            <div class="header__tool">
                <div class="header-button-item has-noti js-item-menu">
                    <i class="zmdi zmdi-notifications"></i>
                    <div class="notifi-dropdown notifi-dropdown--no-bor js-dropdown">
                        <div class="notifi__title">
                            <p>You have 3 Notifications</p>
                        </div>
                        <div class="notifi__item">
                            <div class="bg-c1 img-cir img-40">
                                <i class="zmdi zmdi-email-open"></i>
                            </div>
                            <div class="content">
                                <p>You got a email notification</p>
                                <span class="date">April 12, 2018 06:50</span>
                            </div>
                        </div>
                        <div class="notifi__item">
                            <div class="bg-c2 img-cir img-40">
                                <i class="zmdi zmdi-account-box"></i>
                            </div>
                            <div class="content">
                                <p>Your account has been blocked</p>
                                <span class="date">April 12, 2018 06:50</span>
                            </div>
                        </div>
                        <div class="notifi__item">
                            <div class="bg-c3 img-cir img-40">
                                <i class="zmdi zmdi-file-text"></i>
                            </div>
                            <div class="content">
                                <p>You got a new file</p>
                                <span class="date">April 12, 2018 06:50</span>
                            </div>
                        </div>
                        <div class="notifi__footer">
                            <a href="frondend/#">All notifications</a>
                        </div>
                    </div>
                </div>
                <div class="header-button-item js-item-menu">
                    <i class="zmdi zmdi-settings"></i>
                    <div class="setting-dropdown js-dropdown">
                        <div class="account-dropdown__body">
                            <div class="account-dropdown__item">
                                <a href="frondend/#">
                                    <i class="zmdi zmdi-account"></i>Account</a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="frondend/#">
                                    <i class="zmdi zmdi-settings"></i>Setting</a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="frondend/#">
                                    <i class="zmdi zmdi-money-box"></i>Billing</a>
                            </div>
                        </div>
                        <div class="account-dropdown__body">
                            <div class="account-dropdown__item">
                                <a href="frondend/#">
                                    <i class="zmdi zmdi-globe"></i>Language</a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="frondend/#">
                                    <i class="zmdi zmdi-pin"></i>Location</a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="frondend/#">
                                    <i class="zmdi zmdi-email"></i>Email</a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="frondend/#">
                                    <i class="zmdi zmdi-notifications"></i>Notifications</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="account-wrap">
                    <div class="account-item account-item--style2 clearfix js-item-menu">
                        <div class="image">
                            <img src="frondend/images/icon/avatar-01.jpg" alt="Luna" />
                        </div>
                        <div class="content">
                            <a class="js-acc-btn" href="frondend/#">Luna</a>
                        </div>
                        <div class="account-dropdown js-dropdown">
                            <div class="info clearfix">
                                <div class="image">
                                    <a href="frondend/#">
                                        <img src="frondend/images/icon/avatar-01.jpg" alt="Luna" />
                                    </a>
                                </div>
                                <div class="content">
                                    <h5 class="name">
                                        <a href="frondend/#">Luna</a>
                                    </h5>
                                    <span class="email">dathao280393@gmail.com</span>
                                </div>
                            </div>
                            <div class="account-dropdown__body">
                                <div class="account-dropdown__item">
                                    <a href="frondend/#">
                                        <i class="zmdi zmdi-account"></i>Account</a>
                                </div>
                                <div class="account-dropdown__item">
                                    <a href="frondend/#">
                                        <i class="zmdi zmdi-settings"></i>Setting</a>
                                </div>
                                <div class="account-dropdown__item">
                                    <a href="frondend/#">
                                        <i class="zmdi zmdi-money-box"></i>Billing</a>
                                </div>
                            </div>
                            <div class="account-dropdown__footer">
                                <a href="frondend/#">
                                    <i class="zmdi zmdi-power"></i>Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END HEADER MOBILE -->

        <!-- PAGE CONTENT-->
        <div class="page-content--bgf7" style="height: 70%; ">

            <!-- -------------------------------------------------------------------------------------------
                |
                | layout
                |
             -->
            <div class="container-fluid" style="height: 100%; width: 100%;">
                <!-- component container -->
                <div id="toolbar_container"></div>
                <div style="height: calc(100% - 61px);; width:100%;" id="grid_container"></div>
            </div>



            <!-- COPYRIGHT-->
            <section class="p-t-60 p-b-20">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="copyright">
                                <p>Copyright © 2023 Babana. All rights reserved. Developer by <a href="https://codeclean.org">Tan Doan</a>.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END COPYRIGHT-->
        </div>

    </div>

    <!-- ----------------------------------------------------------------------------------------------------
        |
        | js libraries
        |
     -->

    <script>

    </script>

    <!-- Jquery JS-->
    <!-- <script src="frondend/vendor/jquery-3.2.1.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" ></script>

    <!-- Bootstrap JS-->
    <script src="frondend/vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="frondend/vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="frondend/vendor/slick/slick.min.js">
    </script>
    <script src="frondend/vendor/wow/wow.min.js"></script>
    <script src="frondend/vendor/animsition/animsition.min.js"></script>
    <script src="frondend/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="frondend/vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="frondend/vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="frondend/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="frondend/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="frondend/vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="frondend/vendor/select2/select2.min.js">
    </script>

    <!-- Main JS-->
    <script src="frondend/js/main.js"></script>


    <!-- dhtmlx 8 -->
    <script type="text/javascript" src="<?php echo base_url('suite/codebase/suite.js?v=8.2.1'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('suite/common/data.js?v=8.2.1'); ?>"></script>

    <!-- bootrap js -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> -->

    <!-- template js -->
    <script type="text/javascript" src="<?php echo base_url('templatejs/jsFunctions.js'); ?>"></script>


</body>

</html>
<!-- end document-->