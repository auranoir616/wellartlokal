<?php
$code = $this->input->get('code');

$this->db->where('user_code', $code);
$cekuser = $this->db->get('tb_users');
if ($cekuser->num_rows() == 0) {
?>
    <center>DATA USER TIDAK VALID</center>
<?php } else { ?>
    <?php $userdata = $cekuser->row(); ?>
    <?php echo form_open('', array('id' => 'change_userdata')); ?>
    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <div class="form-group">
        <label for="exampleInputEmail1">Nama Lengkap</label>
        <input type="text" name="user_fullname" class="form-control" placeholder="Nama Lengkap" value="<?php echo $userdata->user_fullname; ?>" autocomplete="off">
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1">Alamat Email</label>
                <input type="text" name="email" class="form-control" placeholder="Alamat Email" value="<?php echo $userdata->email; ?>" autocomplete="off">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1">Nomor WhatsApp</label>
                <input type="text" name="user_phone" class="form-control" placeholder="Nomor WhatsApp" value="<?php echo $userdata->user_phone; ?>" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-block btn-primary">Update Data Member</button>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#change_userdata').submit(function(event) {
                event.preventDefault();

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/userlist/updatedatamember') ?>',
                        type: 'post',
                        dataType: 'json',
                        data: $('#change_userdata').serialize(),
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
            });
        });
    </script>
    <hr>
    <h4>Update Bank</h4>
    <hr style="border-bottom:1px solid #ccc">
    <?php echo form_open('', array('id' => 'updatebank')); ?>
    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <div class="form-group">
        <label for="exampleInputEmail1">Rekening Atas Nama</label>
        <input type="text" name="user_bank_account" class="form-control" placeholder="Rekening Atas Nama" value="<?php echo $userdata->user_bank_account; ?>" autocomplete="off">
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1">Nama Bank</label>
                <input type="text" name="user_bank_name" class="form-control" value="<?php echo $userdata->user_bank_name; ?>" placeholder="Janis Bank" autocomplete="off">
                <small>Contoh: BCA, BNI, BRI</small>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="exampleInputEmail1">No Rekening</label>
                <input type="text" name="user_bank_number" class="form-control" value="<?php echo $userdata->user_bank_number; ?>" placeholder="No Rekening" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-block btn-primary">Update Bank</button>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#updatebank').submit(function(event) {
                event.preventDefault();

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/userlist/updatebank') ?>',
                        type: 'post',
                        dataType: 'json',
                        data: $('#updatebank').serialize(),
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
            });
        });
    </script>
    <hr>
    <h4>Update Password</h4>
    <hr style="border-bottom:1px solid #ccc">
    <?php echo form_open('', array('id' => 'change_password')); ?>
    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <div class="form-group">
        <label for="exampleInputEmail1">New Password</label>
        <input type="text" name="password" class="form-control" placeholder="Password" autocomplete="off">
    </div>
    <div class="form-group">
        <button class="btn btn-block btn-primary">Update Password</button>
    </div>
    <?php echo form_close(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#change_password').submit(function(event) {
                event.preventDefault();

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/userlist/updatepasswordmember') ?>',
                        type: 'post',
                        dataType: 'json',
                        data: $('#change_password').serialize(),
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
            });
        });
    </script>
<?php } ?>