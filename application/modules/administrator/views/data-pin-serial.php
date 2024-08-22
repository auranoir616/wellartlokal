<?php
$this->template->title->set('Data PIN kode');
?>
<div class="row">
    <div class="col-sm-12 col-md-4 col-lg-4">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-title">Form Generate</h3>
                </div>
            </div>
            <div class="card-body">
                <?php echo form_open('', 'id="form-generatepin"'); ?>
                <div class="form-group">
                    <label class="form-label">Paket</label>
                    <select name="pin_package" class="form-control">
                        <option disabled selected>Pilih Paket</option>
                        <?php
                        $getPaket = $this->db->get('tb_packages');
                        foreach ($getPaket->result() as $show) {
                        ?>
                            <option value="<?php echo $show->package_code; ?>"><?php echo $show->package_name; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Total PIN</label>
                    <input class="form-control" placeholder="Total PIN" type="number" autocomplete="off" min="1" name="pin_total">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-group" id="show_hide_password">
                        <input type="password" class="form-control" placeholder="Konfirmasi Password" aria-label="Konfirmasi Password" aria-describedby="basic-addon2" autocomplete="off" name="pin_password" style="border:1px solid #6c5ffc!important">
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
                    <button id='btn010' type="submit" style="font-weight: bold;" class="btn btn-primary btn-block">Buat PIN Sekarang</button>
                    <button id='btn020' type="button" style="font-weight: bold;" class="btn btn-primary btn-block" disabled>Proses Membuat</button>
                </div>
                <?php echo form_close(); ?>
                <script>
                    $('#btn020').hide();
                    $('#form-generatepin').submit(function(event) {
                        event.preventDefault();
                        $('#btn010').hide();
                        $('#btn020').show();

                        $.ajax({
                                url: '<?php echo site_url('postdata/admin_post/serial/newpinserial') ?>',
                                type: 'POST',
                                dataType: 'json',
                                data: $('#form-generatepin').serialize(),
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
                <a data-href="<?php echo site_url('modal/admin/kirim-pin') ?>" data-bs-title="Kirim PIN Serial" data-bs-remote="false" data-bs-toggle="modal" data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false" title="Kirim PIN Serial" style="color:#fff;font-weight: bold;" class="btn btn-block btn-secondary">
                    Kirim PIN Serial
                </a>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-8 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data PIN Serial</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border text-nowrap text-md-nowrap table-striped mb-0">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Paket</th>
                                <th>PIN Kode</th>
                                <th>PIN Serial</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $limit       = 15;
                            $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                            $no          = $offset + 1;

                            $this->db->order_by('pin_date_add', 'DESC');
                            $this->db->where('pin_userid', userid());
                            $this->db->join('tb_packages', 'pin_package_id = package_id');
                            $getdata = $this->db->get('tb_users_pin', $limit, $offset);


                            $this->db->where('pin_userid', userid());
                            $this->db->join('tb_packages', 'pin_package_id = package_id');
                            $Gettotal = $this->db->get('tb_users_pin')->num_rows();
                            foreach ($getdata->result() as $show) {
                            ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $show->package_name ?></td>
                                    <td><span onclick="copyyyPIN(`<?php echo $show->pin_kode; ?>`)"><?php echo $show->pin_kode; ?></span></td>
                                    <td><?php echo $show->pin_date_add ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $this->paginationmodel->paginate('data-pin-serial', $Gettotal, $limit) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function copyyyPIN(text) {
        var copy = document.createElement("textarea");
        document.body.appendChild(copy);
        copy.value = text;
        copy.select();
        document.execCommand("copy");
        document.body.removeChild(copy);


        Swal.fire(
            "Berhasil",
            "PIN Kode <strong>" + text + "</strong> Dicopy",
            "success"
        )
    }
</script>