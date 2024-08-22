<style>
	.my-min {
		min-width: 100px !important;
		max-width: 400px !important;
	}

	@media (max-width: 1000px) {
		.my-min {
			min-width: 100% !important;
		}

	}
</style>
<div class="login-img">
	<div class="page">
		<div class="">
			<div class="col col-login mx-auto mt-7">
				<div class="text-center">
					<a href="<?php echo site_url('') ?>" title="WellartDaniel">
						<img src="<?php echo base_url('assets/logo.svg') ?>" class="header-brand-img" alt="WellartDaniel" style="max-width: 250px;">
					</a>
				</div>
			</div>

			<div class="container-login100">
				<div class="wrap-login100 p-6 my-min">
					<?php echo form_open('', array('id' => 'login-form', 'class' => 'login100-form validate-form')); ?>
					<div class="pb-2">
						<span class="login100-form-title" style="text-align: left;padding-bottom:0px">
							Pendaftaran
						</span>
						<p>
							Form Pendaftaran Member Baru
						</p>
					</div>
					<?php
					$referral_readonly = (!empty($username_referral)) ? 'readonly' : false;
					?>
					<hr style="border-top: 1px solid red;margin:0px">
					<div class="panel panel-primary">
						<div class="panel-body tabs-menu-body p-0 pt-5">
							<div class="tab-content">
								<div class="tab-pane active">
									<div class="form-group">
										<label for="">PIN Kode</label>
										<div class="input-group">
											<input name="user_pinkode" class="input100 form-control ms-0" type="text" placeholder="PIN Kode" autocomplete="off" onkeypress="return event.charCode != 32">
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label for="">Nama Lengkap</label>
												<div class="input-group">
													<input name="user_fullname" class="input100 form-control ms-0" type="text" placeholder="Nama Lengkap" autocomplete="off">
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label for="">Username</label>
												<div class="input-group">
													<input name="user_username" class="input100 form-control ms-0" type="text" placeholder="Username" autocomplete="off">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label for="">Alamat Email</label>
												<div class="input-group">
													<input name="user_email" class="input100 form-control ms-0" type="text" placeholder="Alamat Email" autocomplete="off">
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label for="">No Whatsapp</label>
												<div class="input-group">
													<input name="user_phone" class="input100 form-control ms-0" type="text" placeholder="No Whatsapp" autocomplete="off">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label for="">Referral Kode</label>
												<div class="input-group">
													<input <?php echo $referral_readonly ?> value="<?php echo $username_referral ?>" name="user_referral" class="input100 form-control ms-0" type="text" placeholder="Referral Kode" autocomplete="off" onkeypress="return event.charCode != 32">
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label for="">User Kode</label>
												<div class="input-group">
													<input name="user_upline" class="input100 form-control ms-0" type="text" placeholder="Upline Kode" autocomplete="off" onkeypress="return event.charCode != 32">
												</div>
											</div>
										</div>
									</div>
									<div class="mt-5">
										<label for="">Password</label>
										<div class="wrap-input100 validate-input input-group" id="Password-toggle">
											<a href="javascript:void(0)" class="input-group-text bg-white text-muted">
												<i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
											</a>
											<input name="user_password" class="input100 border-start-0 form-control ms-0" type="password" placeholder="Password" autocomplete="off" onkeypress="return event.charCode != 32">
										</div>
									</div>
									<div class="container-login100-form-btn">
										<button type="submit" class="login100-form-btn" style="background: #38B6FF;border:1px solid #38B6FF;color:#000;font-weight: bold;" id="btn01">Daftar Sekarang</button>
										<button type="submit" class="login100-form-btn" style="background: #38B6FF;border:1px solid #38B6FF;color:#000;font-weight: bold;" disabled id="btn02">Proses Mendaftar</button>
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
										<p class="text-dark mb-0">Sudah punya akun? <a href="<?php echo site_url('login') ?>" class="text-primary ms-1">MASUK</a></p>
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
										url: '<?php echo site_url('postdata/public_post/auth/do_register') ?>',
										type: 'post',
										dataType: 'json',
										data: $('#login-form').serialize(),
									})
									.done(function(data) {

										updateCSRF(data.csrf_data);
										// grecaptcha.reset();
										if (data.status) {
											location.href = '<?php echo site_url('login') ?>';
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