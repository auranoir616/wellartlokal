<?php $this->template->title->set('Stokis');

$this->db->where('userstokis_userid', userid());
$cekSTOKIS = $this->db->get('tb_users_stokis');

$status = 'member';
if ($cekSTOKIS->num_rows() != 0) {
    $status = $cekSTOKIS->row()->userstokis_type;
}

if ($status != 'master') {
?>
    <div class="row">
        <div class="col-sm-12 col-md-5 col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h3>From Stokis</h3>
                </div>
                <div class="card-divider m-0"></div>
                <div class="card-body">
                    <?php echo form_open('', 'id="form-stokis"'); ?>
                    <span class="text-danger">*MOBILE STOKIS MINIMAL 25 PIN</span>
                    <br>
                    <span class="text-danger">*MASTER STOKIS MINIMAL 125 PIN</span>

                    <div class="row mt-2">
                        <div class="col">
                            <div class="form-group">
                                <label for="">PIN RE</label>
                                <input type="number" id="re" class="form-control">
                            </div>

                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="">PIN RO</label>
                                <input type="number" id="ro" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Sub Total</label>
                        <input id="subtotal" type="text" class="form-control" placeholder="Sub Total" disabled>
                    </div>
                    <input type="hidden" id="totallll" name="subtotal">
                    <input min="25" type="hidden" name="totalpin" id="totalpin">
                    <hr>
                    <div class="form-group">
                        <label for="">Bank Admin</label>
                        <select name="stokis_bankadmin" id="jenisbank" class="form-control" onchange="getdetailbank()" style="color:#000">
                            <option disabled selected>Pilih Bank Admin</option>
                            <?php
                            $getbankk = $this->db->get('tb_bankadmin');
                            foreach ($getbankk->result() as $show) {
                            ?>
                                <option value="<?php echo $show->bankadmin_code ?>"><?php echo $show->bankadmin_bankname ?> - <?php echo $show->bankadmin_bankaccount ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Rekening Atas Nama</label>
                                <input type="text" class="form-control" placeholder="Rekening Atas Nama" id="bankaccount" disabled>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Nomor Rekening</label>
                                <input placeholder="Nomor Rekening" id="banknumber" cols="2" rows="2" class="form-control" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Provinsi</label>
                                <select class="form-control" name="user_provinsi" id="provinsi_id" onchange="getkabkota()" required>
                                    <option selected disabled>Pilih Provinsi</option>
                                    <?php
                                    $getprov = $this->db->query('SELECT * FROM wilayah WHERE CHAR_LENGTH(kode) = 2');
                                    foreach ($getprov->result() as $provinsi) {
                                    ?>
                                        <option value="<?php echo $provinsi->kode ?>"><?php echo $provinsi->nama ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Kab/Kota</label>
                                <select class="form-control" name="user_kota" id="kabkota_id" onchange="getkecamatan()" required>
                                    <option selected disabled>Pilih Kabupaten/Kota</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Kecamatan</label>
                                <select class="form-control" name="user_kecamatan" id="kecamatan_id" onchange="getkelurahan()" required>
                                    <option selected disabled>Pilih Kecamatan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label>Desa/Kelurahan</label>
                                <select class="form-control" name="user_kelurahan" id="kelurahan_id" required>
                                    <option selected disabled>Pilih Desa/Kelurahan</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Detail Alamat</label>
                        <textarea name="user_alamat" rows="3" class="form-control" placeholder="Detail Alamat"></textarea>
                    </div>
                    <script>
                        function getkabkota() {
                            var provinsi_id = $('#provinsi_id').val();

                            $.ajax({
                                url: '<?php echo site_url('getdata/user_get/getother/getwilayahKabKota') ?>',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    provinsi_id: provinsi_id,
                                }
                            }).done(function(data) {
                                $('#kabkota_id').empty();
                                $('#kabkota_id').append('<option disabled selected>Select Regency/City</option>');
                                $.each(data.result, function(index, val) {
                                    $('#kabkota_id').append('<option value="' + val.kode + '">' + val.nama + '</option>');
                                });
                            })
                        }

                        function getkecamatan() {
                            var kabkota_id = $('#kabkota_id').val();

                            $.ajax({
                                url: '<?php echo site_url('getdata/user_get/getother/getwilayahKec') ?>',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    kabkota_id: kabkota_id,
                                }
                            }).done(function(data) {
                                $('#kecamatan_id').empty();
                                $('#kecamatan_id').append('<option disabled selected>Select Subdistrict</option>');
                                $.each(data.result, function(index, val) {
                                    $('#kecamatan_id').append('<option value="' + val.kode + '">' + val.nama + '</option>');
                                });
                            })
                        }

                        function getkelurahan() {
                            var kecamatan_id = $('#kecamatan_id').val();

                            $.ajax({
                                url: '<?php echo site_url('getdata/user_get/getother/getwilayahKel') ?>',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    kecamatan_id: kecamatan_id,
                                }
                            }).done(function(data) {
                                $('#kelurahan_id').empty();
                                $('#kelurahan_id').append('<option disabled selected>Select District</option>');
                                $.each(data.result, function(index, val) {
                                    $('#kelurahan_id').append('<option value="' + val.kode + '">' + val.nama + '</option>');
                                });
                            })
                        }

                        function getdetailbank() {
                            var jenisbank = $('#jenisbank').val();
                            document.getElementById("bankaccount").value = '';
                            document.getElementById("banknumber").value = '';
                            $.ajax({
                                url: '<?php echo site_url('getdata/user_get/getother/getbankadmin') ?>',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    jenisbank: jenisbank,
                                }
                            }).done(function(data) {
                                document.getElementById("bankaccount").value = data.result.bankadmin_bankaccount;
                                document.getElementById("banknumber").value = data.result.bankadmin_banknumber;
                            })
                        }
                        $('#re').on('input', function() {

                            $('#re').off('input');
                            updateResults();
                            $('#re').on('input', updateResults);
                        });
                        $('#ro').on('input', function() {
                            $('#ro').off('input');
                            updateResults();
                            $('#ro').on('input', updateResults);
                        });

                        function updateResults() {
                            if ($('#re').val() === '') {
                                $('#re').val('0');
                            }
                            if ($('#ro').val() === '') {
                                $('#ro').val('0');
                            }
                            var re = $("#re").val();
                            var ro = $("#ro").val();
                            $('#subtotal').val('');

                            $.ajax({
                                url: '<?php echo site_url('getdata/user_get/getother/notalpin')                                            ?>', // Ganti dengan URL endpoint Ajax Anda
                                method: "GET",
                                data: {
                                    re: re,
                                    ro: ro,
                                }
                            }).done(function(data) {
                                $("#totalpin").val(data.totalpin);
                                var setharga = data.total.toString().split('').reverse().join(''),
                                    hargaaaaa = setharga.match(/\d{1,3}/g);
                                hargaaaaa = hargaaaaa.join('.').split('').reverse().join('');
                                $('#subtotal').val("Rp. " + hargaaaaa);
                                $('#totallll').val(data.total);
                            });
                        }

                        function GetHARGA() {
                            var paket_code = $('#paketid').val();
                            $.ajax({
                                url: '<?php echo site_url('getdata/user_get/getother/getpktstokis') ?>',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    paket_code: paket_code,
                                }
                            }).done(function(data) {

                                var setharga = data.toString().split('').reverse().join(''),
                                    hargaaaaa = setharga.match(/\d{1,3}/g);
                                hargaaaaa = hargaaaaa.join('.').split('').reverse().join('');
                                $('#subtotal').val("Rp. " + hargaaaaa);
                                $('#total').val("Rp. " + hargaaaaa); // kali total harga dengan 25
                                // document.getElementById("subtotal").value = "Rp. " + hargaaaaa;
                            })
                        }
                    </script>

                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <div class="input-group" id="show_hide_password">
                            <input style="border:1px solid #007bff;" type="password" class="form-control" placeholder="Konfirmasi Password" aria-label="Confirm Password" aria-describedby="basic-addon2" autocomplete="off" name="stokis_password" autocomplete="off">
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
                        <button id='btn010' type="submit" style="background:#007bff!important;border-radius: 0px;border-color:#53a6fa!important;color:#fff" class="btn btn-md btn-primary btn-block font-weight-bold">DAFTAR SEKARANG</button>
                        <button id='btn020' type="button" style="background:#53a6fa!important;border-radius: 0px;border-color:#53a6fa!important;color:#fff  " class="btn btn-md btn-primary btn-block" disabled>SEDANG MEMPROSES</button>
                    </div>
                    <?php echo form_close(); ?>
                    <script>
                        $('#btn020').hide();
                        $('#form-stokis').submit(function(event) {
                            event.preventDefault();
                            $('#btn010').hide();
                            $('#btn020').show();

                            $.ajax({
                                    url: '<?php echo site_url('postdata/user_post/invoice/request_newstokist') ?>',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: $('#form-stokis').serialize(),
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
        <div class="col-sm-12 col-md-7 col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h3>Histori Transaksi</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Stokis</tg>
                                <th>Admin Bank</tg>
                                <th>SubTotal</tg>
                                <th width="20%">Tanggal</tg>
                                <th width="20%">Aksi</tg>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $limit       = 15;
                            $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                            $no          = $offset + 1;

                            $this->db->order_by('invoice_stokis_date_add', 'DESC');
                            $this->db->where('invoice_stokis_userid', userid());
                            $this->db->join('tb_pktstokist', 'pktstokist_id = invoice_stokis_package_id');
                            $getdata = $this->db->get('tb_users_invoice_stokis', $limit, $offset);

                            $this->db->where('invoice_stokis_userid', userid());
                            $this->db->join('tb_pktstokist', 'pktstokist_id = invoice_stokis_package_id');
                            $Gettotal = $this->db->get('tb_users_invoice_stokis')->num_rows();
                            foreach ($getdata->result() as $show) {
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?php echo $show->pktstokist_name ?></td>
                                    <td>
                                        <?php echo $show->invoice_stokis_adbankaccount ?>
                                        <br>
                                        <?php echo $show->invoice_stokis_adbankname ?> - <?php echo $show->invoice_stokis_adbanknumber ?>
                                    </td>
                                    <td>Rp. <?php echo number_format($show->invoice_stokis_amount, 0, ',', '.'); ?></td>
                                    <td>
                                        <?php if ($show->invoice_stokis_status == "pending") { ?>
                                            <span class="badge badge-danger">Pending</span>
                                        <?php } elseif ($show->invoice_stokis_status == "process") { ?>
                                            <span class="badge badge-primary text-white">Process</span>
                                        <?php } else { ?>
                                            <span class="badge badge-success">Success</span>
                                        <?php } ?>
                                        <br>
                                        <?php echo $show->invoice_stokis_date_add ?>
                                    </td>
                                    <td>
                                        <?php if ($show->invoice_stokis_status == "pending") { ?>
                                            <a data-href="<?php echo site_url('modal/member/confirm-joinstokist?code=' . $show->invoice_stokis_code) ?>" data-bs-title="Konfirmasi" data-bs-remote="false" data-bs-toggle="modal" data-bs-target="#dinamicModal" data-bs-backdrop="static" data-bs-keyboard="false" title="Konfirmasi" class="btn btn-sm btn-primary text-white p-1 m-1">
                                                CONFIRM
                                            </a>
                                        <?php } else { ?>
                                            -
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-2">
                    <?php echo $this->paginationmodel->paginate('stokist', $Gettotal, $limit) ?>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="card">
        <div class="card-header">
            <h3>Histori Transaksi</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Stokis</tg>
                        <th>Admin Bank</tg>
                        <th>SubTotal</tg>
                        <th width="20%">Tanggal</tg>
                        <th width="20%">Aksi</tg>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit       = 15;
                    $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                    $no          = $offset + 1;

                    $this->db->order_by('invoice_stokis_date_add', 'DESC');
                    $this->db->where('invoice_stokis_userid', userid());
                    $this->db->join('tb_pktstokist', 'pktstokist_id = invoice_stokis_package_id');
                    $getdata = $this->db->get('tb_users_invoice_stokis', $limit, $offset);

                    $this->db->where('invoice_stokis_userid', userid());
                    $this->db->join('tb_pktstokist', 'pktstokist_id = invoice_stokis_package_id');
                    $Gettotal = $this->db->get('tb_users_invoice_stokis')->num_rows();
                    foreach ($getdata->result() as $show) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?php echo $show->pktstokist_name ?></td>
                            <td>
                                <?php echo $show->invoice_stokis_adbankaccount ?>
                                <br>
                                <?php echo $show->invoice_stokis_adbankname ?> - <?php echo $show->invoice_stokis_adbanknumber ?>
                            </td>
                            <td>Rp. <?php echo number_format($show->invoice_stokis_amount, 0, ',', '.'); ?></td>
                            <td>
                                <?php if ($show->invoice_stokis_status == "pending") { ?>
                                    <span class="rounded-pill badge badge-warning bg-warning">Pending</span>
                                <?php } elseif ($show->invoice_stokis_status == "process") { ?>
                                    <span class="rounded-pill badge badge-info bg-info">Process</span>
                                <?php } else { ?>
                                    <span class="rounded-pill badge badge-success bg-success">Success</span>
                                <?php } ?>
                                <br>
                                <?php echo $show->invoice_stokis_date_add ?>
                            </td>
                            <td>
                                <?php if ($show->invoice_stokis_status == "pending") { ?>
                                    <a data-href="<?php echo site_url('modal/member/confirm-joinstokist?code=' . $show->invoice_stokis_code) ?>" data-title="Konfirmasi" data-remote="false" data-toggle="modal" data-target="#dinamicModal" data-backdrop="static" data-keyboard="false" class="btn btn-sm btn-primary text-white p-1" title="Konfirmasi">
                                        COFIRM
                                    </a>
                                <?php } else { ?>
                                    -
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="p-2">
            <?php echo $this->paginationmodel->paginate('stokist', $Gettotal, $limit) ?>
        </div>
    </div>
<?php } ?>