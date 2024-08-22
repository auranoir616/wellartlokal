<?php
$userdata = userdata();
$code = $this->input->get('code');

$this->db->where('reward_code', $code);
$cekkkkrw = $this->db->get('tb_reward');

if ($cekkkkrw->num_rows() == 0) {
?>
    <center>Reward Data Not Found</center>
<?php } else {
    $datareward = $cekkkkrw->row();

    $fee = (10 / 100) * $datareward->reward_amount;

    $pajaktotal = $datareward->reward_amount - $fee;
?>
    <?php echo form_open('', 'id="form-klaimreward"'); ?>
    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <center>
        <span class="h5">Klaim Reward Senilai</span>
        <p class="mt-4 h2 text-danger" style="font-weight: bold;">Rp. <?php echo number_format($pajaktotal, 0, ',', '.'); ?> <small><s>Rp. <?= number_format($datareward->reward_amount, 0, ',', '.') ?></s></small></p>
    </center>
    <hr>
    <div class="form-group">
        <label for="">Rekening Atas Nama</label>
        <input type="text" class="form-control" placeholder="Rekening Atas Nama" value="<?php echo $userdata->user_bank_account ?>" name="reward_bank_account" autocomplete="off">
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="">Nama Bank</label>
                <input type="text" class="form-control" placeholder="Nama Bank" name="reward_bank_name" value="<?php echo $userdata->user_bank_name; ?>" autocomplete="off">
                <small>Contoh: BCA, BNI, BRI</small>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6">
            <div class="form-group">
                <label for="">Nomor Rekening</label>
                <input type="text" class="form-control" placeholder="Nomor Rekening" name="reward_bank_number" value="<?php echo $userdata->user_bank_number; ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="">No WhatsApp</label>
        <input id="nowa" type="text" class="form-control" placeholder="No WhatsApp" name="reward_phone" value="<?php echo $userdata->user_phone; ?>" autocomplete="off">
    </div>
    <script>
        $('#nowa').on('input', function() {
            if (this.value.startsWith('08')) {
                this.value = "628";
            }
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
        });
    </script>
    </script>
    <div class="form-group">
        <label class="form-label">Konfirmasi Password</label>
        <div class="input-group" id="show_hide_password">
            <input type="password" class="form-control" placeholder="Konfirmasi Password" aria-label="Konfirmasi Password" aria-describedby="basic-addon2" autocomplete="off" name="konfirmasi_password" style="border:1px solid #6c5ffc!important">
            <div class="input-group-append">
                <button style="border-top-left-radius: 0;border-bottom-left-radius: 0;" class="btn btn-outline-primary" type="button" id=""><i class="fa fa-eye-slash" aria-hidden="true"></i></button>
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
        <button id='btn010' type="submit" style="font-weight: bold;" class="btn btn-success btn-block">KLAIM REWARD</button>
        <button id='btn020' type="button" style="font-weight: bold;" class="btn btn-success btn-block" disabled>SEDANG MEMPROSES</button>
    </div>
    <?php echo form_close(); ?>
    <p class="mb-0">
        Informasi
    <ol>
        <li>Poin Akan Berkurang Sesuai Dengan Reward Yang Diklaim.</li>
        <li>Pastikan Data Rekening dan No WhatsApp Sudah Benar.</li>
    </ol>
    </p>
    <script>
        $('#btn020').hide();
        $('#form-klaimreward').submit(function(event) {
            event.preventDefault();
            $('#btn010').hide();
            $('#btn020').show();

            $.ajax({
                    url: '<?php echo site_url('postdata/user_post/profile/klaimreward') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: $('#form-klaimreward').serialize(),
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
<?php } ?>