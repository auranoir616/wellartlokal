<div class="login-img">
    <!-- <div id="global-loader">
        <img src="<?php //echo base_url('assets/backend/images/loader.svg') 
                    ?>" class="loader-img" alt="Loader">
    </div> -->
    <div class="page">
        <div class="">

            <!-- CONTAINER OPEN -->
            <div class="col col-login mx-auto mt-7">
                <div class="text-center">
                    <a href="<?php echo site_url('') ?>" title="WellartDaniel">
                        <img src="<?php echo base_url('assets/logo.svg') ?>" class="header-brand-img" alt="WellartDaniel" style="max-width: 200px;">
                    </a>
                </div>
            </div>

            <div class="container-login100">
                <div class="wrap-login100 p-6">
                    <?php echo form_open('', array('id' => 'login-form', 'class' => 'login100-form validate-form')); ?>
                    <div class="pb-2">
                        <span class="login100-form-title" style="text-align: left;padding-bottom:0px">
                            Login
                        </span>
                        <p>
                            Login Untuk Mengakses Member Area
                        </p>
                    </div>
                    <hr style="border-top: 1px solid red;margin:0px">
                    <div class="panel panel-primary">
                        <div class="panel-body tabs-menu-body p-0 pt-5">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <label for="">Username</label>
                                    <div class="wrap-input100 validate-input input-group">
                                        <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                            <i class="zmdi zmdi-account text-muted" aria-hidden="true"></i>
                                        </a>
                                        <input name="authentication_id" class="input100 border-start-0 form-control ms-0" type="text" placeholder="Username" autocomplete="off">
                                    </div>
                                    <div class="mt-5">
                                        <label for="">Password</label>
                                        <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                                <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                            </a>
                                            <input name="authentication_password" class="input100 border-start-0 form-control ms-0" type="password" placeholder="Password" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="text-end pt-4">
                                        <p class="mb-0"><a href="forgot-password.html" class="text-primary ms-1">Forgot Password?</a></p>
                                    </div>
                                    <div class="container-login100-form-btn">
                                        <button type="submit" class="login100-form-btn" style="background: #38B6FF;border:1px solid #38B6FF;color:#000;font-weight: bold;" id="btn01">LOGIN</button>
                                        <button type="submit" class="login100-form-btn" style="background: #38B6FF;border:1px solid #38B6FF;color:#000;font-weight: bold;" disabled id="btn02">MEMPROSES</button>
                                    </div>
                                    <!-- <script>
                                        document.getElementById('btn01').addEventListener('click', function(event) {
                                            event.preventDefault();

                                            Swal.fire({
                                                title: 'Sedang Dalam Proses Update',
                                                text: 'Mohon maaf, saat ini kami sedang dalam proses update',
                                                type: 'info'
                                            });
                                        });
                                    </script> -->
                                    <div class="text-center pt-3">
                                        <p class="text-dark mb-0">Belum Menjadi Member?<a href="<?php echo site_url('signup'); ?>" class="text-primary ms-1" title="DAFTAR">DAFTAR</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function($) {
                            $('#btn02').hide();
                            $('#login-form').submit(function(event) {
                                event.preventDefault();
                                $('#btn01').hide();
                                $('#btn02').show();

                                $('#login-form').loading();

                                $.ajax({
                                        url: '<?php echo site_url('postdata/public_post/auth/do_login') ?>',
                                        type: 'post',
                                        dataType: 'json',
                                        data: $('#login-form').serialize(),
                                    })
                                    .done(function(data) {

                                        updateCSRF(data.csrf_data);
                                        // grecaptcha.reset();
                                        if (data.status) {
                                            location.href = '<?php echo site_url('dashboard') ?>';
                                        } else {
                                            swal({
                                                html: true,
                                                title: data.heading,
                                                text: data.message,
                                                type: data.type
                                            })
                                        }

                                    })

                                    .always(function() {
                                        $('#login-form').loading('stop');
                                    });
                                $('#btn01').show();
                                $('#btn02').hide();


                            });

                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>