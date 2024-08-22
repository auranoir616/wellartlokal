<?php echo form_open('', array('id' => 'form-kirimpin')); ?>
<div class="form-group">
    <center>
        <small style="color:red;font-weight:bold">Pastikan Anda Telah Mengecek Username Dan Sudah Sesuai. <br>PIN Serial Yang Sudah Dikirim Tidak Dapat Ditarik Kembali.</small>
    </center>
</div>
<div class="form-group">
    <label class="form-label">Username Tujuan</label>
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Username Tujuan" aria-label="Username Tujuan" aria-describedby="button-addon2" name="username_tujuan" autocomplete="off">
        <button class="btn btn-primary" type="button" id="button-addon2" href="javascript:void(0)" onclick="cekuname('')">Cek Username</button>
    </div>
</div>
<center>
    <div class="form-group">
        <p style="color:red;font-weight:bold;display:none;" id="pesan">USERNAME TIDAK DIKENAL ATAU TIDAK DITEMUKAN</p>
    </div>
</center>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" placeholder="Nama Lengkap" id="show_nama" disabled>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
            <label class="form-label">No WhatsApp</label>
            <input type="text" class="form-control" placeholder="No WhatsApp" id="show_phone" disabled>
        </div>
    </div>
</div>
<script>
    function cekuname() {
        var uname = $("input[name=username_tujuan]").val();
        if (!uname) {
            Swal.fire(
                'Gagal',
                'Bagian Username Tujuan Wajib Diisi',
                'error'
            );
        } else {
            $.ajax({
                    url: '<?php echo site_url('getdata/user_get/getother/cekusername') ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        uname: uname,
                    }
                })
                .done(function(data) {
                    if (data.status) {
                        $("#pesan").hide();
                        document.getElementById("show_nama").value = data.userdata.user_fullname;
                        document.getElementById("show_phone").value = data.userdata.user_phone;
                    } else {
                        $("#pesan").show();
                        document.getElementById("show_nama").value = '';
                        document.getElementById("show_phone").value = '';
                    }
                });
        }
    }
</script>
<div class="row">
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
            <label class="form-label">Paket PIN</label>
            <select name="paket_pin" id="" class="form-control">
                <option disabled selected>Pilih Paket</option>
                <?php
                $getpaket = $this->db->get('tb_packages');

                foreach ($getpaket->result() as $show) {
                ?>
                    <option value="<?php echo $show->package_code ?>"><?php echo $show->package_name ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
        <div class="form-group">
            <label class="form-label">Total PIN</label>
            <input type="text" class="form-control" placeholder="Total PIN" name="total_pin" autocomplete="off">
        </div>
    </div>
</div>
<div class="form-group">
    <label class="form-label">Konfirmasi Password</label>
    <div class="input-group" id="show_hide_password">
        <input type="password" class="form-control" placeholder="Konfirmasi Password" aria-label="Confirm Password" aria-describedby="basic-addon2" autocomplete="off" name="konfirmasi_password">
        <div class="input-group-append">
            <button class="btn btn-outline-primary" type="button" id=""><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#show_hide_password button").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("fa-eye-slash");
                $('#show_hide_password i').removeClass("fa-eye");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("fa-eye-slash");
                $('#show_hide_password i').addClass("fa-eye");
            }
        });
    });
</script>
<div class="form-group">
    <button style="font-weight:bold" type="submit" id="btn01cc" class="btn btn-block btn-primary">KIRIM PIN & SERIAL</button>
    <button style="font-weight:bold" type="button" id="btn02cc" class="btn btn-block btn-primary" disabled>PROSES MENGIRIM</button>
</div>
<?php echo form_close(); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#btn02cc').hide();
        $('#form-kirimpin').submit(function(event) {
            event.preventDefault();
            $('#btn01cc').hide();
            $('#btn02cc').show();
            $.ajax({
                    url: '<?php echo site_url('postdata/user_post/pinserial/cekkirimpin') ?>',
                    type: 'post',
                    dataType: 'json',
                    data: $('#form-kirimpin').serialize(),
                })
                .done(function(data) {

                    myCSRF(data.csrf_data);
                    updateCSRF(data.csrf_data);
                    if (data.status) {
                        Swal.fire({
                            allowOutsideClick: false,
                            title: 'Apakah Anda Yakin?',
                            text: data.message,
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'YA KIRIM',
                            cancelButtonText: 'Batal',
                        }).then((result) => {
                            if (result.value) {

                                $.ajax({
                                        url: '<?php echo site_url('postdata/user_post/pinserial/kirimpinserial') ?>',
                                        type: 'post',
                                        dataType: 'json',
                                        data: $('#form-kirimpin').serialize(),
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

                                    })
                            }
                        });
                    } else {
                        Swal.fire(
                            data.heading,
                            data.message,
                            data.type
                        );
                    }

                    $('#btn01cc').show();
                    $('#btn02cc').hide();

                })
        });
    });
</script>