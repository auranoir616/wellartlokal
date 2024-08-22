<?php
$userdata = userdata();
$this->template->title->set('Statement Bonus');
$this->template->label->set('ADMIN');
$this->template->sublabel->set('Statement Bonus');
?>

<div class="page-wrapper">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title" id="statement_bonus">Statement Bonus</h3>
            <form method="get">
                <div class="d-flex">
                    <div class="mr-3 mb-3">
                        <label for="start_date" id="start_date">Tanggal Awal</label>
                        <input type="date" id="start_date" name="start_date" class="form-control">
                    </div>
                    <div class="mr-3">
                        <label for="end_date" id="end_date">Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date" class="form-control">
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success" id="tampilkan">Tampilkan</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body table-border-style">
            <?php
            if ($this->input->get('start_date') != NULL && $this->input->get('end_date') != NULL) {
                $start_date     = $this->input->get('start_date');
                $end_date       = $this->input->get('end_date');

                $this->db->where('w_balance_date_add >=', $start_date . ' 00:00:00');
                $this->db->where('w_balance_date_add <=', $end_date . ' 23:59:59');
                $get_data = $this->db->get('tb_wallet_balance');

                if ($get_data->num_rows() > 0) {
            ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td>Nama</td>
                                    <td>Peringkat</td>
                                    <td>Bonus Sponsor</td>
                                    <td>Bonus Unilevel</td>
                                    <td>Bonus Peringkat</td>
                                    <td>Bonus Royalty</td>
                                    <td>Total Bonus</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php echo $userdata->user_fullname ?>
                                        <br>
                                        <small>@<?php echo $userdata->username; ?></small>
                                    </td>
                                    <td><?php echo $this->rank->ranking()['myrank']; ?></td>

                                    <?php
                                    $wallet = $this->usermodel->userWallet('withdrawal');

                                    $this->db->where('w_balance_wallet_id', $wallet->wallet_id);
                                    $this->db->where('w_balance_type', 'credit');
                                    $this->db->where('w_balance_ket', 'sponsor');
                                    $get_data = $this->db->get('tb_wallet_balance');
                                    $total_bonus_sponsor = 0;
                                    foreach ($get_data->result() as $row) {
                                        $total_bonus_sponsor += (int) $row->w_balance_amount;
                                    }
                                    ?>

                                    <td><?php echo $total_bonus_sponsor; ?></td>

                                    <?php
                                    $this->db->where('w_balance_wallet_id', $wallet->wallet_id);
                                    $this->db->where('w_balance_type', 'credit');
                                    $this->db->where('w_balance_ket', 'unilevel');
                                    $get_data = $this->db->get('tb_wallet_balance');
                                    $total_bonus_unilevel = 0;
                                    foreach ($get_data->result() as $row) {
                                        $total_bonus_unilevel += (int) $row->w_balance_amount;
                                    }
                                    ?>

                                    <td><?php echo $total_bonus_unilevel; ?></td>

                                    <?php
                                    $this->db->where('w_balance_wallet_id', $wallet->wallet_id);
                                    $this->db->where('w_balance_type', 'credit');
                                    $this->db->where('w_balance_ket', 'ranking');
                                    $get_data = $this->db->get('tb_wallet_balance');
                                    $total_bonus_peringkat = 0;
                                    foreach ($get_data->result() as $row) {
                                        $total_bonus_peringkat += (int) $row->w_balance_amount;
                                    }
                                    ?>

                                    <td><?php echo $total_bonus_peringkat; ?></td>

                                    <?php
                                    $this->db->where('w_balance_wallet_id', $wallet->wallet_id);
                                    $this->db->where('w_balance_type', 'credit');
                                    $this->db->where('w_balance_ket', 'royalty');
                                    $get_data = $this->db->get('tb_wallet_balance');
                                    $total_bonus_royalty = 0;
                                    foreach ($get_data->result() as $row) {
                                        $total_bonus_royalty += (int) $row->w_balance_amount;
                                    }
                                    ?>

                                    <td><?php echo $total_bonus_royalty; ?></td>

                                    <?php
                                    $total_bonus = $total_bonus_sponsor + $total_bonus_unilevel + $total_bonus_peringkat + $total_bonus_royalty;
                                    ?>

                                    <td><?php echo $total_bonus ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php
                } else {
                ?>
                    <p>Tidak ada bonus pada rentang tanggal tersebut</p>
            <?php
                }
            }
            ?>
            <div class="d-flex justify-content-end">
                <button class="btn btn-success" id="cetak" onclick="printToPDF()">Cetak</button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        #statement_bonus,
        #tampilkan,
        #start_date,
        #end_date,
        #cetak {
            display: none;
        }
    }
</style>

<script>
    function tampilkanBonus() {
        var startDate = document.getElementById('start_date').value;
        var endDate = document.getElementById('end_date').value;

        window.location.href = '<?php echo site_url('statement-bonus') ?>' + '?action=tampilkan&start_date=' + startDate + '&end_date=' + endDate;
    }

    function printToPDF() {
        window.print();
    }
</script>