<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="WellartDaniel">
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('assets/logo-kakbah-green.png') ?>">
    <meta name="theme-color" content="#38B6FF">
    <meta name="google" content="notranslate" />
    <title><?php echo $this->template->title ?> - WellartDaniel</title>
    <link href="<?php echo base_url('assets/backend/plugins/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" id="style" />
    <link href="<?php echo base_url('assets/backend/css/style.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/backend/css/dark-style.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/backend/css/transparent-style.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/backend/css/skin-modes.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/backend/css/icons.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/backend/colors/color1.css') ?>" id="theme" rel="stylesheet" type="text/css" media="all" />

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/backend/jquery-easy-loading/dist/jquery.loading.min.css') ?>">
    <script src="https://code.jquery.com/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/backend/jquery-easy-loading/dist/jquery.loading.min.js') ?>"></script>
    <link href="<?php echo base_url("assets/backend/sweetalert2/dist/sweetalert2.min.css") ?>" rel="stylesheet">
    <script src="<?php echo base_url("assets/backend/sweetalert2/dist/sweetalert2.min.js") ?>"></script>
    <script type="text/javascript" charset="utf-8" async defer>
        function updateCSRF(value) {
            return $('input[name=csrf_myapp]').val(value);
        }

        function myCSRF(value) {
            return $('input[name=csrf_cadangan]').val(value);
        }
    </script>
    <style>
        .swal2-container {
            z-index: 20000 !important;
        }

        .page {
            min-height: auto !important;
        }

        .blink {
            animation: blinker 1.5s linear infinite;
            color: red;
            font-family: sans-serif;
        }

        .activeeee {
            background: #485460;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }

        .side-menu__item {
            color: #fff !important;
        }

        .side-menu__item:hover,
        .side-menu__item:focus {
            color: #fff !important;
        }

        .side-menu .side-menu__icon {
            color: #fff !important;
        }

        .side-menu__item:hover .side-menu__icon,
        .side-menu__item:hover .side-menu__label,
        .side-menu__item:focus .side-menu__icon,
        .side-menu__item:focus .side-menu__label {
            color: #fff !important;
        }

        .side-menu__item.active {
            background: #1e272e !important;
            color: #000 !important;
        }
    </style>
</head>

<body class="app sidebar-mini ltr">

    <div class="page">
        <div class="page-main">

            <!-- app-Header -->
            <div class="app-header header sticky" style="background: #1e272e!important;">
                <div class="container-fluid main-container">
                    <div class="d-flex">
                        <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)" style="color:#38B6FF"></a>

                        <a class="logo-horizontal " href="<?php echo site_url('dashboard') ?>" title="WellartDaniel">
                            <img src="<?php echo base_url('assets/logo-text.svg') ?>" class="header-brand-img desktop-logo" alt="logo" style="max-width: 230px;">
                            <img src="<?php echo base_url('assets/logo-text.svg') ?>" class="header-brand-img light-logo1" alt="logo" style="max-width: 230px;">
                        </a>

                        <div class="d-flex order-lg-2 ms-auto header-right-icons">
                            <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon fe fe-more-vertical" style="color:#38B6FF"></span>
                            </button>
                            <div class="navbar navbar-collapse responsive-navbar p-0">
                                <div class="collapse navbar-collapse" id="navbarSupportedContent-4" style="background: #1e272e!important;">
                                    <style>
                                        .menu-nav {
                                            display: block;
                                        }

                                        @media (max-width: 1000px) {

                                            .menu-nav {
                                                display: flex !important;
                                                justify-content: center !important;
                                            }

                                        }
                                    </style>
                                    <div class="d-flex order-lg-2 menu-nav">

                                        <div class="dropdown d-flex">
                                            <a href="<?php echo site_url('wallet') ?>" class="nav-link icon nav-link-bg" style="color:#38B6FF">
                                                <i class="ti-wallet" style="color:#38B6FF"></i>
                                            </a>
                                        </div>

                                        <div class="dropdown d-flex profile-1">
                                            <a href="javascript:void(0)" data-bs-toggle="dropdown" class="nav-link leading-none d-flex">
                                                <img src="<?php echo base_url('assets/user-white.png') ?>" alt="profile-user" class="avatar  profile-user brround cover-image">
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                <div class="drop-heading">
                                                    <div class="text-center">
                                                        <h5 class="text-dark mb-0 fs-14 fw-semibold"><?php echo $userdata->user_fullname ?></h5>
                                                        <small class="text-muted"><?php echo $userdata->username ?></small>
                                                    </div>
                                                </div>
                                                <div class="dropdown-divider m-0"></div>
                                                <a class="dropdown-item" href="<?php echo site_url('settings') ?>" title="Settings">
                                                    <i class="dropdown-icon fe fe-user"></i> Settings
                                                </a>
                                                <a class="dropdown-item" href="javascript:" onclick="logout_confirm()" title="Logout">
                                                    <i class="dropdown-icon fe fe-alert-circle"></i> Logout
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sticky">
                <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
                <div class="app-sidebar" style="background: #1e272e!important;">
                    <div class="side-header" style="background: #1e272e!important;border:none!important">
                        <a class="header-brand1" href="<?php echo site_url('dashboard') ?>" title="WellartDaniel">
                            <img style="max-width:80%" src="<?php echo base_url('assets/logo-text.svg') ?>" class="header-brand-img desktop-logo" alt="logo">
                            <img style="max-width:80%" src="<?php echo base_url('assets/favicon.svg') ?>" class="header-brand-img toggle-logo" alt="logo">
                            <img style="max-width:80%" src="<?php echo base_url('assets/favicon.svg') ?>" class="header-brand-img light-logo" alt="logo">
                            <img style="max-width:80%" src="<?php echo base_url('assets/logo-text.svg') ?>" class="header-brand-img light-logo1" alt="logo">
                        </a>
                    </div>
                    <div class="main-sidemenu">
                        <div class="slide-left disabled" id="slide-left">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                            </svg>
                        </div>
                        <?php
                        $user_group     = $this->ion_auth->get_users_groups()->row();

                        $this->db->where('referral_id', userid());
                        $totref = $this->db->get('tb_users')->num_rows();

                        if ($user_group->id == 1) {
                            $array_menu   =  array(
                                array(
                                    'heading'       => 'DASHBOARD',
                                    'data'          => array(
                                        array(
                                            'title'     => 'Dashboard',
                                            'icon'      => 'ti-home',
                                            'url'       => 'dashboard',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Wallet',
                                            'icon'      => 'ti-wallet',
                                            'url'       => 'wallet',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Referrals',
                                            'icon'      => 'fe fe-users',
                                            'url'       => 'referrals',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Level',
                                            'icon'      => 'fe fe-users',
                                            'url'       => 'level',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Repeat Order Member',
                                            'icon'      => 'fe fe-users',
                                            'url'       => 'admin/repeat-order-member',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                    )
                                ),
                                array(
                                    'heading'       => 'PIN Kode',
                                    'data'          => array(
                                        array(
                                            'title'     => 'PIN Kode',
                                            'icon'      => 'fa fa-key',
                                            'url'       => 'pin-serial',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Histori',
                                            'icon'      => 'fa fa-history',
                                            'url'       => 'histori-pin',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                    )
                                ),
                                array(
                                    'heading'       => 'ADMIN',
                                    'data'          => array(
                                        array(
                                            'title'     => 'Data Member',
                                            'icon'      => 'ti-folder',
                                            'url'       => 'admin/data-member',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Data Omset (BV)',
                                            'icon'      => 'ti-folder',
                                            'url'       => 'admin/data-omset',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Data Profit Share',
                                            'icon'      => 'ti-folder',
                                            'url'       => 'admin/data-profit-share',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Data PIN Kode',
                                            'icon'      => 'ti-folder',
                                            'url'       => 'admin/data-pin-serial',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Data Withdrawal',
                                            'icon'      => 'ti-folder',
                                            'url'       => 'admin/data-withdrawal',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Data Sale',
                                            'icon'      => 'ti-folder',
                                            'url'       => 'admin/data-sale',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Data INV Stokis',
                                            'icon'      => 'ti-folder',
                                            'url'       => 'admin/data-inv-stokis',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                    )
                                ),
                                array(
                                    'heading'       => 'Profile',
                                    'data'          => array(
                                        array(
                                            'title'     => 'Settings',
                                            'icon'      => 'fe fe-user',
                                            'url'       => 'settings',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Logout',
                                            'icon'      => 'fe fe-log-out',
                                            'url'       => 'withdrawal',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                    )
                                ),
                            );
                        } else {
                            $array_menu   =  array(
                                array(
                                    'heading'       => 'DASHBOARD',
                                    'data'          => array(
                                        array(
                                            'title'     => 'Dashboard',
                                            'icon'      => 'ti-home',
                                            'url'       => 'dashboard',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Repeat Order',
                                            'icon'      => 'fe fe-repeat',
                                            'url'       => 'repeat-order',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Orders',
                                            'icon'      => 'fa fa-shopping-bag',
                                            'url'       => 'orders',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Referrals',
                                            'icon'      => 'fe fe-users',
                                            'url'       => 'referrals',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Level',
                                            'icon'      => 'fe fe-users',
                                            'url'       => 'level',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),

                                    )
                                ),
                                // array(
                                //     'heading'       => 'TRANSACTION',
                                //     'data'          => array(

                                //     )
                                // ),
                                array(
                                    'heading'       => 'PIN Kode',
                                    'data'          => array(
                                        array(
                                            'title'     => 'PIN Kode',
                                            'icon'      => 'fa fa-key',
                                            'url'       => 'pin-serial',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Histori',
                                            'icon'      => 'fa fa-history',
                                            'url'       => 'histori-pin',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                    )
                                ),
                                array(
                                    'heading'       => 'WALLET',
                                    'data'          => array(
                                        array(
                                            'title'     => 'Wallet',
                                            'icon'      => 'ti-wallet',
                                            'url'       => 'wallet',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Withdrawal',
                                            'icon'      => 'ti-wallet',
                                            'url'       => 'withdrawal',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                    )
                                ),
                                array(
                                    'heading'       => 'BONUS',
                                    'data'          => array(
                                        array(
                                            'title'     => 'Sponsor',
                                            'icon'      => 'ti-gift',
                                            'url'       => 'sponsor',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Unilevel',
                                            'icon'      => 'ti-gift',
                                            'url'       => 'unilevel',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Peringkat',
                                            'icon'      => 'ti-gift',
                                            'url'       => 'peringkat',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Royalty',
                                            'icon'      => 'ti-gift',
                                            'url'       => 'royalty',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Stament Bonus',
                                            'icon'      => 'ti-gift',
                                            'url'       => 'statement-bonus',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                    )
                                ),
                                array(
                                    'heading'       => 'Profile',
                                    'data'          => array(
                                        array(
                                            'title'     => 'Join Stokis',
                                            'icon'      => 'fe fe-users',
                                            'url'       => 'stokist',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Settings',
                                            'icon'      => 'fe fe-user',
                                            'url'       => 'settings',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                        array(
                                            'title'     => 'Logout',
                                            'icon'      => 'fe fe-log-out',
                                            'url'       => 'logout',
                                            'notif'     => 0,
                                            'typenotif' => null,
                                            'submenu'   => FALSE,
                                        ),
                                    )
                                ),
                            );
                        }

                        foreach ($array_menu as $menus) :
                        ?>
                            <ul class="side-menu">
                                <li class="sub-category">
                                    <h3><?php echo $menus['heading'] ?></h3>
                                </li>
                                <?php
                                foreach ($menus['data'] as $submenu) {

                                    $active         = ($this->uri->uri_string() == $submenu['url']) ? 'activeeee' : false;
                                    $logout         = ($submenu['title'] == "Logout") ? 'onclick="logout_confirm()"' : false;
                                ?>
                                    <li class="slide">
                                        <a href="<?php if ($submenu['title'] != "Logout") {
                                                        echo site_url($submenu['url']);
                                                    } else {
                                                        echo 'javascript:';
                                                    } ?>" <?php echo $logout ?> class="side-menu__item <?php echo $active; ?>" data-bs-toggle="slide" style="padding-top: 5px;padding-bottom: 5px;">
                                            <i class="side-menu__icon <?php echo $submenu['icon']; ?>" style="font-weight: bold;"></i>
                                            <span class="side-menu__label"><?php echo $submenu['title']; ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <span class="slide-left"></span>
            <span class="slide-right"></span>
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">
                        <?php if ($this->session->userdata('admin_userid') && ($this->session->userdata('admin_userid') != userid())) :
                            $getgroups = $this->ion_auth->get_users_groups($this->session->userdata('admin_userid'))->row();
                            echo form_hidden('csrf_cadangan', $this->security->get_csrf_hash());
                        ?>
                            <div class="alert alert-danger mt-5" role="alert" style="background: #e74c3c;color:#fff">
                                <?php echo 'ANDA LOGIN SEBAGAI <u><b>' . strtoupper($userdata->user_fullname) . '</b></u> <a href="javascript:" id="login-back-admin" class="badge bg-success p-2" style="color:#fff">KLIK DISINI</a> UNTUK KEMBALI KE ' . strtoupper($getgroups->name); ?>
                            </div>
                            <script type="text/javascript" charset="utf-8" async defer>
                                jQuery(document).ready(function($) {

                                    $('#login-back-admin').click(function(event) {

                                        $.ajax({
                                                url: '<?php echo site_url('postdata/public_post/auth/login_back_admin') ?>',
                                                type: 'post',
                                                dataType: 'json',
                                                data: {
                                                    userid: 1,
                                                    csrf_myapp: $('input[name=csrf_cadangan]').val()
                                                }
                                            })
                                            .done(function(data) {

                                                swal(
                                                    data.heading,
                                                    data.message,
                                                    data.type
                                                ).then(function() {
                                                    location.href =
                                                        '<?php echo site_url('admin/data-member') ?>';
                                                });

                                            });

                                    });

                                });
                            </script>
                        <?php endif ?>
                        <div class="page-header">
                            <h1 class="page-title"><?php echo $this->template->title ?></h1>
                            <!-- <div> -->
                            <!-- <ol class="breadcrumb"> -->
                            <!-- <li class="breadcrumb-item"><a href="javascript:void(0)"><?php //echo $this->template->title 
                                                                                            ?></a></li> -->
                            <!-- <li class="breadcrumb-item active" aria-current="page"><?php //echo $this->template->sublabel 
                                                                                        ?></li> -->
                            <!-- </ol>
                            </div> -->
                        </div>


                        <?php echo $this->template->content ?>
                    </div>
                    <!-- CONTAINER CLOSED -->

                </div>
            </div>
            <!--app-content closed-->
        </div>
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-md-12 col-sm-12 text-center">
                        Copyright © <?php echo date('Y') ?> All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a> -->
    <div class="sidebar-right"></div>
    <div class="modal fade" id="dinamicModals" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="card-title" id="myModalLabel">Modal Title</div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-spinner fa-spin"></i> loading ...
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="dinamicModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-spinner fa-spin"></i> loading ...
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/backend/plugins/bootstrap/js/popper.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/plugins/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/jquery.sparkline.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/circle-progress.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/plugins/charts-c3/d3.v5.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/plugins/charts-c3/c3-chart.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/plugins/input-mask/jquery.mask.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/plugins/sidebar/sidebar.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/plugins/sidemenu/sidemenu.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/plugins/p-scroll/perfect-scrollbar.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/plugins/p-scroll/pscroll.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/plugins/p-scroll/pscroll-1.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/themeColors.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/sticky.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/js/custom.js') ?>"></script>
    <script src="<?php echo base_url('assets/backend/sweetalert2/dist/sweetalert2.min.js') ?>"></script>

    <script>
        $("#dinamicModals").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-body").load(link.attr("data-href"));
            $(this).find("#myModalLabel").text(link.attr("data-title"));
        });
        $("#dinamicModal").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-body").load(link.attr("data-href"));
            $(this).find("#myModalLabel").text(link.attr("data-bs-title"));
        });

        function logout_confirm() {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Anda akan keluar dari sesi dan kembali ke halaman login!",
                type: 'warning',
                showCancelButton: true,
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'YA, Logout',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.value) {
                    location.href = '<?php echo site_url('authentication/logout') ?>';
                }
            })
        }
    </script>
</body>

</html>