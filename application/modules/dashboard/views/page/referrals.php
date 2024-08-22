<?php $this->template->title->set('Referrals'); ?>

<div class="modal fade" id="linkreferral" aria-hidden="true" aria-labelledby="linkreferralLabel2" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkreferralLabel2">Referral</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-primary" role="alert" style="font-weight: bold;text-align: center;">
                    Gunakan Tautan atau Kode Referensi Untuk Merekrut Anggota
                </div>
                <div class="form-group">
                    <label>Link Referral</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="copylink" value="<?php echo site_url('signup?ref=' . $userdata->user_referral_code); ?>" readonly style="border-color: #716aca !important;" style="border:1px solid #6c5ffc!important">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button" onclick="copylink()" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">Salin tautan</button>
                        </div>
                    </div>
                </div>
                <script>
                    function copylink() {
                        var copyText = document.getElementById("copylink");
                        copyText.select();
                        copyText.setSelectionRange(0, 99999);
                        document.execCommand("copy");
                        Swal.fire(
                            "Berhasil",
                            "Link Berhasil Salin",
                            "success"
                        )
                    }
                </script>

                <div class="form-group">
                    <label>Code Referral</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="codereferral" value="<?php echo $userdata->user_referral_code ?>" readonly style="border-color: #716aca !important;" style="border:1px solid #6c5ffc!important">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button" onclick="copycode()" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">salin code</button>
                        </div>
                    </div>
                </div>
                <script>
                    function copycode() {
                        var copyText = document.getElementById("codereferral");
                        copyText.select();
                        copyText.setSelectionRange(0, 99999);
                        document.execCommand("copy");
                        Swal.fire(
                            "Berhasil",
                            "Referral Kode Berhasil Salin",
                            "success"
                        )
                    }
                </script>
                <hr>
                <button type="button" class="btn btn-block btn-warning" data-bs-dismiss="modal" aria-label="Close">tutup modal</button>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <a class="btn btn-primary btn-md btn-block" data-bs-toggle="modal" href="#linkreferral" role="button">LINK & KODE REFERRAL</a>
</div>
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Referrals</h3>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Omset Tertinggi
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table border text-nowrap text-md-nowrap table-striped mb-0">
                <thead>
                    <tr>
                        <td width="5%">#</td>
                        <td>Userdata</td>
                        <td>Kode</td>
                        <td>Kontak</td>
                        <td>Omset</td>
                        <td>Tanggal</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $limit       = 15;
                    $offset      = ($this->input->get('page')) ? $this->input->get('page') : 0;
                    $no          = $offset + 1;

                    $this->db->order_by('created_on', 'DESC');
                    $this->db->where('referral_id', userid());
                    $this->db->where('group_id', (int)2);
                    $this->db->join('tb_users_groups', 'user_id = tb_users.id');
                    $getdata = $this->db->get('tb_users', $limit, $offset);


                    $this->db->where('referral_id', userid());
                    $this->db->where('group_id', (int)2);
                    $this->db->join('tb_users_groups', 'user_id = tb_users.id');
                    $Gettotal = $this->db->get('tb_users')->num_rows();
                    foreach ($getdata->result() as $show) {
                        $refdata    = userdata(['id' => $show->referral_id]);
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $show->user_fullname ?>
                                <br>
                                <small>@<?php echo $show->username; ?></small>
                            </td>
                            <td><span onclick="copyyyKODE(`<?php echo $show->user_referral_code; ?>`)"><?php echo $show->user_referral_code; ?></span></td>
                            <td>
                                <?php echo $show->email ?>
                                <br>
                                <?php echo $show->user_phone ?>
                            </td>
                            <td>
                                <?php echo $show->user_omset; ?> BV
                            </td>
                            <td>
                                <?php echo $this->rank->myrank($show->user_id); ?>
                                <br>
                                <?php echo date('d-M-Y', $show->created_on) ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php echo $this->paginationmodel->paginate('referrals', $Gettotal, $limit) ?>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Omset Teratas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i></button>
            </div>
            <div class="modal-body pb-0">
                <table class="table text-center">
                    <thead class="bg-dark text-white">
                        <tr>
                            <td>User</td>
                            <td>Omset</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $this->db->limit(2);
                        $this->db->order_by('user_omset', 'DESC');
                        $this->db->where('upline_id', userid());
                        $this->db->where('group_id', (int)2);
                        $this->db->join('tb_users_groups', 'user_id = tb_users.id');
                        $leaderboard = $this->db->get('tb_users');
                        foreach ($leaderboard->result() as $showlead) {
                        ?>
                            <tr>
                                <td><?php echo $showlead->user_fullname ?></td>
                                <td><?php echo $showlead->user_omset ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer border-0 p-2">
                <button type="button" class="btn btn-secondary btn-block" data-bs-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<script>
    function copyyyKODE(text) {
        var copy = document.createElement("textarea");
        document.body.appendChild(copy);
        copy.value = text;
        copy.select();
        document.execCommand("copy");
        document.body.removeChild(copy);


        Swal.fire(
            "Berhasil",
            "User Kode <strong>" + text + "</strong> Dicopy",
            "success"
        )
    }
</script>