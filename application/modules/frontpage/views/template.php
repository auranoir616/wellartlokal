<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
    <meta name="description" content="PT Wellart Daniel Indonesia">
    <title>PT Wellart Daniel Indonesia</title>
    <link rel="shortcut icon" type="image/png" href="img/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/frontpage/css/bootstrap.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/frontpage/css/owl.carousel.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/frontpage/css/slick.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/frontpage/css/fontawesome-all.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/frontpage/css/style.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/frontpage/css/responsive.css'); ?>" />

    <div id="body-wrapper">
        <header id="header">
            <div class="main_navbar" style="margin-top: 0;">
                <nav class="navbar navbar-expand-lg  navbar-dark">
                    <div class="container">
                        <a class="navbar-brand logo-sticky font-600" href=""><img class="header-brand-img" style="max-width: 65px;" src="<?php echo base_url('assets/logo.svg') ?>" alt="WellartDaniel"></a>
                        <button class="navbar-toggler collapsed" data-toggle="collapse" data-target="#navbarNav" aria-expanded="false"><span class="navbar-toggler-icon"></span></button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ml-auto" id="nav">
                                <li class="nav-item">
                                    <a class="nav-link js-scroll-trigger" href="#about">About</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link js-scroll-trigger" href="#contact">Contact</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link js-scroll-trigger" href="#testimonial">Testimonial</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link js-scroll-trigger" href="<?php echo site_url('signup') ?>">Daftar</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </header>

        <?php echo $this->template->content; ?>

        <footer id="footer">
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <img style="max-width: 200px;" src="<?php echo base_url('assets/logo.svg') ?>" alt="WellartDaniel">
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="footer-about">
                                <h3 class="color-white">Address</h3>
                                <p>
                                    <span class="font-weight-bold">Head Office</span><br>
                                    Jl. Klamono 3 No. 53<br>
                                    RT 55 Kel. Muara Rapak<br>
                                    Kec. Balikpapan Utara, Balikpapan<br>
                                    Kalimantan Timur
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="footer-social-area">
                                <h3 class="color-white">Contact Us</h3>
                                <ul>
                                    <li><a href="mailto:ptwellartdaniel@gmail.com"><i class="far fa-envelope"></i></a></li>
                                    <li><a href="tel:+6285754583360"><i class="fas fa-phone"></i></a></li>
                                    <li><a href="https://wa.me/6285754583360"><i class="fab fa-whatsapp"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="footer-about">
                                <h3 class="color-white">Jam Kerja</h3>
                                <p>Senin - Jum'at Pukul 08:00 - 17:00</p>
                                <p>Sabtu Pukul 08:00 - 15:00</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="color-white text-center">Copyright © 2024 <a class="p-color" href="https://wellartdaniel.com">Wellart Daniel Indonesia</a></p>
            </div>

            <div class="click-to-top">
                <a href="#body-wrapper" class="js-scroll-trigger"><i class="fas fa-angle-double-up"></i></a>
            </div>
        </footer>
    </div>

    <script src="<?php echo base_url('assets/frontpage/js/jquery-min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontpage/js/map-scripts.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontpage/js/slick.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontpage/js/waypoints.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontpage/js/counterup.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontpage/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontpage/js/jquery.magnific-popup.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontpage/js/mixitup.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontpage/js/owl.carousel.min.js'); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="<?php echo base_url('assets/frontpage/js/scrolly.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontpage/js/ajax-contact-form.js'); ?>"></script>
    <script src="<?php echo base_url('assets/frontpage/js/custom.js'); ?>"></script>
    </body>
</html>