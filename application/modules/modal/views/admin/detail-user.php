<?php
$code = $this->input->get('code');

$this->db->where('user_code', $code);
$cekkkkkkk = $this->db->get('tb_users');

if ($cekkkkkkk->num_rows() == 0) {
?>
    <center>Data Member Tidak Ditemukan</center>
<?php } else {
    $userdata = $cekkkkkkk->row();


    $this->db->where('id', $userdata->user_provinsi);
    $getprov = $this->db->get('tb_provinsi');


    $this->db->where('id', $userdata->user_kota);
    $getkabkot = $this->db->get('tb_kabupaten');

    $this->db->where('id', $userdata->user_kecamatan);
    $getkec = $this->db->get('tb_kecamatan');

    $this->db->where('id', $userdata->user_kelurahan);
    $getkelurahan = $this->db->get('villages');
?>
    <div class="form-group">
        <label for="">Nama Lengkap</label>
        <input type="text" class="form-control" value="<?php echo $userdata->user_fullname ?>" readonly>
    </div>
    <div class="form-group">
        <label for="">No WhatsApp</label>
        <input type="text" class="form-control" value="<?php echo $userdata->user_phone ?>" readonly>
    </div>
    <div class="form-group">
        <label for="">Alamat Lengkap</label>
        <textarea class="form-control" cols="4" rows="4" readonly><?php echo $userdata->user_alamat ?>, <?php echo ucwords(strtolower($getkelurahan->row()->name)) ?>, <?php echo ucwords(strtolower($getkec->row()->name)) ?>, <?php echo ucwords(strtolower($getkabkot->row()->name)) ?>, <?php echo ucwords(strtolower($getprov->row()->name)) ?></textarea>
    </div>
<?php } ?>