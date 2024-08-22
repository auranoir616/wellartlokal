<?php
$this->template->title->set('Data Bank');
$this->template->label->set('ADMIN');
$this->template->sublabel->set('Data Bank');

?>
<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Bank</h3>
            </div>
            <div class="card-body">
                <?php echo form_open('', 'id="new-databank"'); ?>
                <div class="form-group">
                    <label for="">Rekening Atasnama</label>
                    <input type="text" class="form-control" placeholder="Rekening Atasnama" name="bank_account" autocomplete="off">
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Nama Bank</label>
                            <input type="text" class="form-control" placeholder="Nama Bank" name="bank_name" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Nomor Rekening</label>
                            <input type="number" class="form-control" placeholder="Nomor Rekening" name="bank_number" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Konfirm Password</label>
                    <div class="input-group" id="show_hide_password">
                        <input type="password" class="form-control" placeholder="Konfirm Password" aria-label="Konfirm Password" aria-describedby="basic-addon2" autocomplete="off" name="confirm_password" autocomplete="off">
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
                    <button id='btn010' type="submit" class="btn btn-primary btn-block">SIMPAN DATA BANK</button>
                    <button id='btn020' type="button" class="btn btn-primary btn-block" disabled>PROSES MENYIMPAN</button>
                </div>
                <?php echo form_close(); ?>
                <script>
                    $('#btn020').hide();
                    $('#new-databank').submit(function(event) {
                        event.preventDefault();
                        $('#btn010').hide();
                        $('#btn020').show();

                        $.ajax({
                                url: '<?php echo site_url('postdata/admin_post/bank/savenewbank') ?>',
                                type: 'POST',
                                dataType: 'json',
                                data: $('#new-databank').serialize(),
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
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Bank</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <td width="5%">#</td>
                                <td width="15%">Nama Bank</td>
                                <td>Rekening Atasnama</td>
                                <td>Nomor Rekening</td>
                                <td width="25%">Aksi</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $limit       = 15;
                            $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                            $no          = $offset + 1;

                            $this->db->order_by('bankadmin_id', 'DESC');
                            $getdata = $this->db->get('tb_bankadmin', $limit, $offset);

                            $Gettotal = $this->db->get('tb_bankadmin')->num_rows();
                            foreach ($getdata->result() as $show) {
                            ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $show->bankadmin_bankname ?></td>
                                    <td><?php echo $show->bankadmin_bankaccount ?></td>
                                    <td><?php echo $show->bankadmin_banknumber ?></td>
                                    <td>
                                        <a data-href="<?php echo site_url('modal/admin/bank-update?code=' . $show->bankadmin_code) ?>" data-bs-title="UPDATE MEMBER" data-bs-remote="false" data-bs-toggle="modal" data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false" title="UPDATE MEMBER" class="btn btn-sm btn-secondary text-white">
                                            Update
                                        </a>
                                        <a href="javascript:void(0)" onclick="hapus('<?php echo $show->bankadmin_code ?>')" class="btn btn-sm btn-danger text-white" title="Remove">
                                            Remove
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $this->paginationmodel->paginate('data-bank', $Gettotal, $limit) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function hapus(code) {
        Swal.fire({
            allowOutsideClick: false,
            title: 'Apakah Anda Yakin?',
            text: "Data Bank Akan Dihapus!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'YA Hapus',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                        url: '<?php echo site_url('postdata/admin_post/bank/hapusbank') ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            code: code,
                            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                        }
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
    }
</script>