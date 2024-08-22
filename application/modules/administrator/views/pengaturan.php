<?php
$this->template->title->set('Pengaturan');
$this->template->label->set('ADMIN');
$this->template->sublabel->set('Pengaturan');
$this->db->where('package_id', (int)1);
$getpaket = $this->db->get('tb_packages');
?>
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Sistem</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <input type="checkbox" <?php if (option('wd')['option_desc1'] == 'yes') {
                                                echo "checked";
                                            } ?> data-toggle="toggle" data-width="100" onchange="updatefungsi('wd')">
                    <label class="mt-2 h2" for="" style="display: block;font-size:14px!important">Fungsi Transaksi Penarikan atau Withdrawal</label>
                </div>
                <div class="form-group">
                    <input type="checkbox" <?php if (option('reward')['option_desc1'] == 'yes') {
                                                echo "checked";
                                            } ?> data-toggle="toggle" data-width="100" onchange="updatefungsi('reward')">
                    <label class="mt-2 h2" for="" style="display: block;font-size:14px!important">Fungsi Transaksi Klaim Reward</label>
                </div>
                <div class="form-group">
                    <input type="checkbox" <?php if (option('login')['option_desc1'] == 'yes') {
                                                echo "checked";
                                            } ?> data-toggle="toggle" data-width="100" onchange="updatefungsi('login')">
                    <label class="mt-2 h2" for="" style="display: block;font-size:14px!important">Fungsi Login Untuk Member</label>
                </div>
                <div class="form-group">
                    <p class="text-danger">Fungsi Pengaturan Akan Berpengaruh Untuk Semua Member, Kecuali Fungsi Login Tidak Berlaku Untuk ADMIN.</p>
                </div>
            </div>
        </div>
        <script>
            function updatefungsi(fungsi) {

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/custom/updatefungsi') ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            fungsi: fungsi
                        }
                    })

                    .done(function(data) {
                        Swal.fire(
                            data.heading,
                            data.message,
                            data.type
                        )
                    });
            }
        </script>
    </div>
    <!-- <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Pembonusan</div>
            </div>
            <div class="card-body">
                <?php if ($getpaket->num_rows() != 0) { ?>
                    <div class="alert alert-danger" role="alert" style="background:red;color:#fff;border-left: 4px solid yellow;font-weight: bold;">
                        Setiap Kolom Tidak Boleh Kosong
                    </div>
                    <?php echo form_open('', 'id="updatepaket"'); ?>
                    <input type="hidden" value="<?php echo $getpaket->row()->package_code ?>" name="code">
                    <div class="form-group">
                        <label for="">Bonus Sponsor</label>
                        <input name="package_sponsor" value="<?php echo $getpaket->row()->package_sponsor ?>" type="text" class="form-control" placeholder="Bonus Sponsor" autocomplete="off">
                        <small style="color:red">Harap Masukkan Angka Tanpa Simbol Persen (%)</small>
                    </div>
                    <div class="form-group">
                        <label for="">Bonus Pasangan</label>
                        <input name="package_pasangan" value="<?php echo $getpaket->row()->package_pasangan ?>" type="text" class="form-control" placeholder="Bonus Pasangan" autocomplete="off">
                        <small style="color:red">Harap Masukkan Angka Tanpa Simbol Persen (%)</small>
                    </div>
                    <div class="form-group">
                        <label for="">Flush Out Bonus Pasangan</label>
                        <input name="package_flushout" value="<?php echo $getpaket->row()->package_flushout ?>" type="text" class="form-control" placeholder="Flush Out Bonus Pasangan" autocomplete="off">
                        <small style="color:red">Batas Bonus Pasangan Dalam Sehari</small>
                    </div>
                    <div class="form-group">
                        <button id='btn010' type="submit" class="btn btn-primary btn-block">SIMPAN BONUS</button>
                        <button id='btn020' type="button" class="btn btn-primary btn-block" disabled>PROSES MENYIMPAN</button>
                    </div>
                    <?php echo form_close(); ?>
                    <script>
                        $('#btn020').hide();
                        $('#updatepaket').submit(function(event) {
                            event.preventDefault();
                            $('#btn010').hide();
                            $('#btn020').show();

                            $.ajax({
                                    url: '<?php echo site_url('postdata/admin_post/custom/updatepaket') ?>',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: $('#updatepaket').serialize(),
                                })
                                .done(function(data) {

                                    updateCSRF(data.csrf_data);
                                    Swal.fire(
                                        data.heading,
                                        data.message,
                                        data.type
                                    ).then(function() {
                                        if (data.status) {
                                            location.reload();
                                        }
                                    });
                                    $('#btn010').show();
                                    $('#btn020').hide();
                                })
                        });
                    </script>
                    <div class="form-group">
                        <p class="text-danger">Bonus Yang Sudah Dikirim Tidak Akan Berubah, Jika Persentase Bonus dirubah Perubahan akan Terjadi Saat Member Menerima Bonus Baru.</p>
                    </div>
                <?php } else { ?>
                    <center>Hubungi Developer</center>
                <?php } ?>
            </div>
        </div>
    </div> -->
</div>