<?php
$this->template->title->set('Repeat Order');

$this->db->where('pin_package_id', (int)2);
$this->db->where('pin_userid', userid());
$get_pin2 = $this->db->get('tb_users_pin');

$wallet_address = $this->usermodel->userWallet('withdrawal')->wallet_address;
$info_pending = $this->walletmodel->walletPending($wallet_address);


// $this->db->where('pin_userid != ' , 1);
// $this->db->where('pin_package_id', (int)2);
// $this->db->join('tb_users', 'pin_userid = id');
// $get_pin = $this->db->get('tb_users_pin');
// echo '<pre>';
// print_r($get_pin->result());
// echo '</pre>';
?>
<div class="alert alert-danger" role="alert">
    Anda Memiliki <?php echo 'Rp. ' . number_format($info_pending, 0, ',', '.'); ?> Bonus Unilevel <strong>PENDING</strong> lakukan Repeat Order Untuk Menerima Bonus Tersebut di Tanggal 01, Jika Sampai Pada Tanggal 01 Bulan Berikutnya Anda Tidak Melakukan Repeat Order Bonus Akan Hangus.
    <a href="<?php echo site_url('wallet-pending'); ?>" class="text-dark">CEK BONUS PENDING</a>
</div>
<div class="row">
    <div class="col-sm-12 col-md-4 col-lg-4">
        <div class="card bg-primary img-card box-info-shadow">
            <div class="card-body">
                <div class="d-flex">
                    <div class="text-white">
                        <h2 class="mb-0 number-font h6"><?php echo $get_pin2->num_rows() ?> PIN</h2>
                        <p class="text-white mb-0">PIN Repeat Order</p>
                    </div>
                    <div class="ms-auto"> <i class="fa fa-key fa-3x text-white fs-50 me-2 mt-2"></i> </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Repeat Order</h3>
            </div>
            <div class="card-body">
                <?php echo form_open('', 'id="form-ro"'); ?>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="username_tujuan" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
                    <button class="btn btn-outline-dark" type="button" id="button-addon2" onclick="cekuname()">Cek Member</button>
                    <center>
                        <div class="form-group">
                            <p style="color:red;font-weight:bold;display:none;" id="pesan">USERNAME TIDAK DIKENAL ATAU TIDAK DITEMUKAN</p>
                        </div>
                    </center>

                </div>
                <div class="row">
                    <div class="col">
                        <input type="text" id="show_nama" class="form-control" disabled placeholder="Name" aria-label="First name">
                    </div>
                    <div class="col">
                        <input type="text" id="show_phone" class="form-control" disabled placeholder="Phone" aria-label="Last name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Total Repeat Order</label>
                    <input class="form-control" name="ro_total" placeholder="Total Repeat Order" type="number" min="1">
                    <input type="hidden" id="show_userid" name="ro_userid" class="form-control" placeholder="Name" aria-label="First name">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-group" id="show_hide_password">
                        <input type="password" class="form-control" placeholder="Konfirmasi Password" aria-label="Konfirmasi Password" aria-describedby="basic-addon2" autocomplete="off" name="ro_password" style="border:1px solid #6c5ffc!important">
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
                                        document.getElementById("show_userid").value = data.userdata.id;
                                    } else {
                                        $("#pesan").show();
                                        document.getElementById("show_nama").value = '';
                                        document.getElementById("show_phone").value = '';
                                        document.getElementById("show_userid").value = '';
                                    }
                                });
                        }
                    }
                </script>

                <div class="form-group">
                    <button id='btn010' type="submit" style="font-weight: bold;" class="btn btn-primary btn-block">REPEAT ORDER</button>
                    <button id='btn020' type="button" style="font-weight: bold;" class="btn btn-primary btn-block" disabled>PROSES PENARIKAN</button>
                </div>
                <?php echo form_close(); ?>
                <script>
                    $('#btn020').hide();
                    $('#form-ro').submit(function(event) {
                        console.log($('#form-ro').serialize());
                        event.preventDefault();
                        $('#btn010').hide();
                        $('#btn020').show();

                        $.ajax({
                                url: '<?php echo site_url('postdata/user_post/repatorder/new_rofromadmin') ?>',
                                type: 'POST',
                                dataType: 'json',
                                data: $('#form-ro').serialize(),
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
                <h3 class="card-title">Pin RO Member</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <td width="5%">#</td>
                                <td>Username</td>
                                <td width="20%">Total Pins</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $limit  = 5;
                            $offset = ($this->input->get('page')) ? $this->input->get('page') : 0;
                            $no     = $offset + 1;

                            // Query untuk mendapatkan data username dan total pins
                            $query = "SELECT tb_users.username, COUNT(tb_users_pin.pin_userid) AS total_pins
                  FROM tb_users
                  LEFT JOIN tb_users_pin ON tb_users.id = tb_users_pin.pin_userid
                  WHERE tb_users.id <> 1 AND tb_users_pin.pin_package_id = 2
                  GROUP BY tb_users.id
                  LIMIT $limit OFFSET $offset";

                            $getdata = $this->db->query($query);

                            // Query untuk mendapatkan total rows
                            $countQuery = "SELECT COUNT(DISTINCT tb_users.id) AS total_rows
                       FROM tb_users
                       LEFT JOIN tb_users_pin ON tb_users.id = tb_users_pin.pin_userid
                       WHERE tb_users.id <> 1 AND tb_users_pin.pin_package_id = 2";

                            $total_rows = $this->db->query($countQuery)->row()->total_rows;

                            foreach ($getdata->result() as $show) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $show->username; ?></td>
                                    <td><?php echo $show->total_pins; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <?php
                    echo $this->paginationmodel->paginate('repeat-order-member', $total_rows, $limit);
                    ?>
                </div>
            </div>
        </div>
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">History</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td width="5%">#</td>
                                    <td>Total</td>
                                    <td width="20%">Tanggal</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $limit       = 15;
                                $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                                $no          = $offset + 1;

                                $this->db->order_by('historiro_date', 'DESC');
                                $this->db->where('historiro_userid', userid());
                                $getdata = $this->db->get('tb_historiro', $limit, $offset);

                                $this->db->where('historiro_userid', userid());
                                $Gettotal = $this->db->get('tb_historiro')->num_rows();
                                foreach ($getdata->result() as $show) {
                                ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo number_format($show->historiro_amount, 0, ',', '.'); ?>X Repeat Order</td>
                                        <td><?php echo $show->historiro_date ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php echo $this->paginationmodel->paginate('repeat-order', $Gettotal, $limit) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>