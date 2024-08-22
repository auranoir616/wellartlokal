<?php
$this->template->title->set('Withdrawal');
$this->template->label->set('WALLET');
$this->template->sublabel->set('Withdrawal');

$userdata = userdata();

$wallet_withdrawal              = $this->usermodel->userWallet('withdrawal')->wallet_address;
$info_walletwd                  = $this->walletmodel->walletAddressBalance($wallet_withdrawal);

?>
<div class="row">
    <div class="col-sm-12 col-md-4 col-lg-4">
        <div class="card bg-info img-card box-info-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font h6"><?php echo 'Rp. ' . number_format($info_walletwd, 0, ',', '.'); ?></h2>
                        <p class="text-white mb-0">Saldo Saat Ini</p>
                    </div>
                    <div class="ms-auto"> <i class="ti-wallet text-white fs-50 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Withdrawal</h3>
            </div>
            <div class="card-body">
                <?php echo form_open('', 'id="form-wd"'); ?>
                <div class="form-group">
                    <label class="form-label">Rekening Atas Nama</label>
                    <input value="<?php echo $userdata->user_bank_account ?>" class="form-control" disabled="" placeholder="Rekening Atas Nama" type="text">
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">Nama Bank</label>
                            <input value="<?php echo $userdata->user_bank_name ?>" class="form-control" disabled="" placeholder="Nama Bank" type="text">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="form-label">Nomor Rekening</label>
                            <input value="<?php echo $userdata->user_bank_number ?>" class="form-control" disabled="" placeholder="Nomor Rekening" type="text">
                        </div>
                    </div>
                </div>
                <center class="mt-3 mb-3">
                    <a href="<?php echo site_url('settings') ?>" title="UPDATE DATA BANK" class="btn btn-block btn-danger">UPDATE BANK</a>
                </center>
                <div class="form-group">
                    <label class="form-label">Total Withdrawals</label>
                    <input class="form-control form-control-lg" placeholder="Total Withdrawals" type="text" name="wd_total" onkeypress="return event.charCode != 32" autocomplete="off">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-group" id="show_hide_password">
                        <input type="password" class="form-control" placeholder="Konfirmasi Password" aria-label="Konfirmasi Password" aria-describedby="basic-addon2" autocomplete="off" name="wd_password" style="border:1px solid #6c5ffc!important">
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
                    <button id='btn010' type="submit" style="font-weight: bold;" class="btn btn-primary btn-block">TARIK SALDO</button>
                    <button id='btn020' type="button" style="font-weight: bold;" class="btn btn-primary btn-block" disabled>MEMPROSES</button>
                </div>
                <?php echo form_close(); ?>
                <div class="form-group">
                    <label for="">Informasi</label>
                    <ol>
                        <li>Minimal Withdrawal Rp. 50.000</li>
                        <li>Biaya Admin Rp. 10.000 + PPh 2.5%</li>
                    </ol>
                </div>
                <script>
                    $('#btn020').hide();
                    $('#form-wd').submit(function(event) {
                        event.preventDefault();
                        $('#btn010').hide();
                        $('#btn020').show();

                        $.ajax({
                                url: '<?php echo site_url('postdata/user_post/withdrawal/reqest_new') ?>',
                                type: 'POST',
                                dataType: 'json',
                                data: $('#form-wd').serialize(),
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
    <div class="col-sm-12 col-md-8 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Histori Withdrawal</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <td width="5%">#</td>
                                <td>Data Bank</td>
                                <td>Total</td>
                                <td>SubTotal</td>
                                <td width="20%">Status</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $limit       = 15;
                            $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                            $no          = $offset + 1;

                            $this->db->order_by('withdrawl_date', 'DESC');
                            $this->db->where('withdrawl_userid', userid());
                            $getdata = $this->db->get('tb_withdrawl', $limit, $offset);

                            $this->db->where('withdrawl_userid', userid());
                            $Gettotal = $this->db->get('tb_withdrawl')->num_rows();
                            foreach ($getdata->result() as $show) {
                            ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td>
                                        <?php echo $show->withdrawl_account ?>
                                        <br>
                                        <?php echo $show->withdrawl_bank_name ?> - <?php echo $show->withdrawl_bank_number ?>
                                    </td>
                                    <td>Rp. <?php echo number_format($show->withdrawl_amount, 0, ',', '.'); ?></td>
                                    <td>Rp. <?php echo number_format($show->withdrawl_will_get, 0, ',', '.'); ?></td>
                                    <td>
                                        <?php if ($show->withdrawl_status == "Pending") { ?>
                                            <span class="badge bg-danger">Pending</span>
                                        <?php } elseif ($show->withdrawl_status == "Rejected") { ?>
                                            <span class="badge bg-warning text-white">Rejected</span>
                                        <?php } else { ?>
                                            <span class="badge bg-success">Success</span>
                                        <?php } ?>
                                        <br>
                                        <?php echo $show->withdrawl_date ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $this->paginationmodel->paginate('withdrawal', $Gettotal, $limit) ?>
                </div>
            </div>
        </div>
    </div>
</div>