<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cronjob extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function pindahNominal()
    {
        $last_date_start = date('Y-m-01 00:00:00', strtotime('-1 month', now()));
        $last_date_end   = date('Y-m-t 23:59:59', strtotime('-1 month', now()));

        /*============================================
        =	       GET PENERIMA BONUS PENDING         =
        ============================================*/
        $this->db->group_by('w_pending_wallet_id');
        $this->db->where('w_pending_type', 'credit');
        $this->db->where('w_pending_date_add BETWEEN "' . $last_date_start . '" AND "' . $last_date_end . '"');
        $this->db->join('tb_users_wallet', 'wallet_id = w_pending_wallet_id');
        $getPending = $this->db->get('tb_wallet_pending');

        foreach ($getPending->result() as $showww) {
            /*============================================
            =         GET TOTAL SALDO PENDING            =
            ============================================*/
            $amount = $this->walletmodel->walletPending($showww->wallet_address);

            /*==========================================================
            =   USER DENGAN AMOUNT LEBIH BESAR DARI 0 AKAN DI PROSES    =
            ============================================================*/
            if ($amount > 0) {

                /*=============================================================
                =   CEK APAKAH USER SUDAH MEMENUHI KUALIFIKASI MINIMAL 100BV  =
                ===============================================================*/
                if ($this->rank->bulanlaluBV($showww->wallet_user_id) >= 100) {

                    /*==============================================
                    =   AMBIL DATA BONUS / CREDIT PADA BULAN LALU  =
                    ===============================================*/
                    $this->db->where('w_pending_type', 'credit');
                    $this->db->where('w_pending_ket', 'unilevel');
                    $this->db->where('w_pending_wallet_id', $showww->wallet_id);
                    $this->db->where('w_pending_date_add BETWEEN "' . $last_date_start . '" AND "' . $last_date_end . '"');
                    $this->db->join('tb_users_wallet', 'wallet_id = w_pending_wallet_id');
                    $get_Pending = $this->db->get('tb_wallet_pending');
                    foreach ($get_Pending->result() as $ssshow) {
                        /*============================================================
                        =   PROSES CREDIT KE WALLET UTAMA BERDASARKAN WALLET PENDING  =
                        ==============================================================*/
                        $this->db->insert(
                            'tb_wallet_balance',
                            [
                                'w_balance_wallet_id'       => $showww->wallet_id,
                                'w_balance_amount'          => $ssshow->w_pending_amount,
                                'w_balance_type'            => 'credit',
                                'w_balance_desc'            => $ssshow->w_pending_desc,
                                'w_balance_date_add'        => $ssshow->w_pending_date_add,
                                'w_balance_txid'            => random_string('alnum', 64),
                                'w_balance_ket'             => 'unilevel',
                            ]
                        );
                    }

                    /*=======================================================================
                    =   DEBIT SALDO DI WALLET PENDING KARENA SUDAH DIPINDAH KE WALLET UTAMA  =
                    ========================================================================*/
                    $this->db->insert(
                        'tb_wallet_pending',
                        [
                            'w_pending_wallet_id'       => $showww->wallet_id,
                            'w_pending_amount'          => $amount,
                            'w_pending_type'            => 'debit',
                            'w_pending_desc'            => 'Bonus Unilevel Dipindah ke Wallet Utama',
                            'w_pending_date_add'        => sekarang(),
                            'w_pending_txid'            => random_string('alnum', 64),
                            'w_pending_ket'             => 'unilevel',
                        ]
                    );
                } else {
                    /*=======================================================================
                    =   DEBIT SALDO DI WALLET PENDING KARENA USER TIDAK MEMENUGI SYARAT BV  =
                    ========================================================================*/
                    $this->db->insert(
                        'tb_wallet_pending',
                        [
                            'w_pending_wallet_id'       => $showww->wallet_id,
                            'w_pending_amount'          => $amount,
                            'w_pending_type'            => 'debit',
                            'w_pending_desc'            => 'Tidak Memenuhi Syarat BV',
                            'w_pending_date_add'        => sekarang(),
                            'w_pending_txid'            => random_string('alnum', 64),
                            'w_pending_ket'             => 'unilevel',
                        ]
                    );
                }
            }
        }
    }

    /*============================================
    =	       RESET DATA USER SHARE            =
    ============================================*/
    function resetUSHARE()
    {
        $this->db->truncate('tb_usershare');
        $this->db->update(
            'tb_users',
            [
                'user_omset'  => (int)0,
            ]
        );
    }


    /*============================================
    =	            GENERATE RANK               =
    ============================================*/

    function save_RANKING()
    {
        $this->db->where('group_id', (int)2);
        $this->db->join('tb_users_groups', 'tb_users.id = tb_users_groups.user_id');
        $get_USER = $this->db->get('tb_users');

        $this->db->where('usershare_status', 'ranking');
        $this->db->delete('tb_usershare');

        foreach ($get_USER->result() as $showw) {
            $generate_ranking = $this->rank->qualifSP($showw->user_id, 2);
            if (is_array($generate_ranking) && count($generate_ranking) > 1) {
                usort($generate_ranking, function ($a, $b) {
                    return $b['idrank'] - $a['idrank'];
                });
                $generate_ranking = array_slice($generate_ranking, 0, 2);
            }
            foreach ($generate_ranking as $item) {
                $this->db->insert(
                    'tb_usershare',
                    [
                        'usershare_userid'  => $showw->user_id,
                        'usershare_rankid'  => $item['idrank'],
                        'usershare_status'  => 'ranking',
                        'usershare_date'    => sekarang(),
                        'usershare_code'    => strtolower(random_string('alnum', 64)),
                    ]
                );
            }
        }
    }


    // function shareRANKING()
    // {
    //     $startblnlalu   = date('Y-m-01 00:00:00', strtotime('-1 month', now()));
    //     $endlnlalu      = date('Y-m-t 23:59:59', strtotime('-1 month', now()));
    //     // $startblnlalu = date('Y-m-01 00:00:00', strtotime('first day of this month')); // Tanggal pertama bulan ini
    //     // $endlnlalu = date('Y-m-01 00:00:00', strtotime(now())); // Tanggal pertama bulan ini

    //     $nilaiBV         = 1000;

    //     $saldo_bulanlalu = 0;
    //     $this->db->select_sum('omset_amount');
    //     $this->db->where('omset_date BETWEEN "' . $startblnlalu . '" AND "' . $endlnlalu . '"');
    //     $getbulanlalu     = $this->db->get('tb_omset');
    //     $get_bulanlalu    = $getbulanlalu->row()->omset_amount;
    //     if (!empty($get_bulanlalu)) {
    //         $saldo_bulanlalu     = $get_bulanlalu;
    //     }

    //     // AMBIL 30% DARI OMSET BV              
    //     $BVRangking = (30 / 100) * $saldo_bulanlalu;

    //     // GET USER RANKING
    //     $this->db->where('usershare_status', 'ranking');
    //     $this->db->where('tb_rank.rank_ranking !=', 0);
    //     $this->db->join('tb_rank', 'usershare_rankid = rank_id');
    //     $this->db->join('tb_users', 'usershare_userid = id');
    //     $userranking = $this->db->get('tb_usershare');

    //     $totalUSER = $userranking->num_rows();
    //     // CONVER NILAI BV KE IDR (10.000). TOTAL AKAN DIBAGI KE USER
    //     $ShareIDR = ($nilaiBV * $BVRangking) / $totalUSER;

    //     foreach ($userranking->result() as $show) {
    //         // BAGIANKU BERDASARKAN OMSET BULAN LALU
    //         $bagianKU = ($show->rank_ranking / 100) * $ShareIDR;

    //         // Bonus berdasarkan rank
    //         switch ($show->rank_ranking) {
    //             case 10: // APPRENTICE
    //                 $bagianKU *= (10 / 100);
    //                 break;
    //             case 8: // START UP
    //                 $bagianKU *= (8 / 100);
    //                 break;
    //             case 6: // MANAGER
    //                 $bagianKU *= (6 / 100);
    //                 break;
    //             case 4: // CROWN
    //                 $bagianKU *= (4 / 100);
    //                 break;
    //             case 2: // ROYAL
    //                 $bagianKU *= (2 / 100);
    //                 break;
    //             default:
    //                 break;
    //         }

    //         // Bagi dengan jumlah seluruh user dengan rank tertentu
    //         $this->db->where('usershare_status', 'ranking');
    //         $this->db->where('usershare_rankid', $show->rank_id);
    //         $jumlahUserRank = $this->db->count_all_results('tb_usershare');

    //         if ($jumlahUserRank > 0) {
    //             $bagianKU /= $jumlahUserRank;
    //         }

    //         // INPUT WALLET
    //         $wallet = $this->usermodel->userWallet('withdrawal', $show->id);
    //         $this->db->insert(
    //             'tb_wallet_balance',
    //             [
    //                 'w_balance_wallet_id'       => $wallet->wallet_id,
    //                 'w_balance_amount'          => $bagianKU,
    //                 'w_balance_type'            => 'credit',
    //                 'w_balance_desc'            => 'Bonus Ranking dari Omset Bulan ' . date('Y-m-t', strtotime('-1 month', now())),
    //                 'w_balance_date_add'        => sekarang(),
    //                 'w_balance_txid'            => strtolower(random_string('alnum', 64)),
    //                 'w_balance_ket'             => 'ranking',
    //             ]
    //         );
    //         echo $bagianKU;
    //     }
    // }
    function shareRANKING()
{
    $startblnlalu   = date('Y-m-01 00:00:00', strtotime('-1 month', now()));
    $endlnlalu      = date('Y-m-t 23:59:59', strtotime('-1 month', now()));
    
    $nilaiBV         = 1000;

    $saldo_bulanlalu = 0;
    $this->db->select_sum('omset_amount');
    $this->db->where('omset_date BETWEEN "' . $startblnlalu . '" AND "' . $endlnlalu . '"');
    $getbulanlalu     = $this->db->get('tb_omset');
    $get_bulanlalu    = $getbulanlalu->row()->omset_amount;
    if (!empty($get_bulanlalu)) {
        $saldo_bulanlalu     = $get_bulanlalu;
    }

    // AMBIL 30% DARI OMSET BV              
    $BVRangking = (30 / 100) * $saldo_bulanlalu;

    // GET USER RANKING
    $this->db->where('usershare_status', 'ranking');
    $this->db->where('tb_rank.rank_ranking !=', 0);
    $this->db->join('tb_rank', 'usershare_rankid = rank_id');
    $this->db->join('tb_users', 'usershare_userid = id');
    $userranking = $this->db->get('tb_usershare');

    $totalUSER = $userranking->num_rows();
    // CONVER NILAI BV KE IDR (10.000). TOTAL AKAN DIBAGI KE USER
    $ShareIDR = ($nilaiBV * $BVRangking) / $totalUSER;

    foreach ($userranking->result() as $show) {
        // BAGIANKU BERDASARKAN OMSET BULAN LALU
        $bagianKU = ($show->rank_ranking / 100) * $ShareIDR;

        // Bonus berdasarkan rank
        switch ($show->rank_ranking) {
            case 10: // APPRENTICE
                $bagianKU *= 0.10; // 10%
                break;
            case 8: // START UP
                $bagianKU *= (0.05 + 0.10); // 5% + 10%
                break;
            case 6: // MANAGER
                $bagianKU *= (0.05 + 0.05); // 5% + 5%
                break;
            case 4: // CROWN
                $bagianKU *= (0.05 + 0.05); // 5% + 5%
                break;
            case 2: // ROYAL
                $bagianKU *= (0.05 + 0.05); // 5% + 5%
                break;
            default:
                break;
        }

        // Bagi dengan jumlah seluruh user dengan rank tertentu
        $this->db->where('usershare_status', 'ranking');
        $this->db->where('usershare_rankid', $show->rank_id);
        $jumlahUserRank = $this->db->count_all_results('tb_usershare');

        if ($jumlahUserRank > 0) {
            $bagianKU /= $jumlahUserRank;
        }

        // INPUT WALLET
        $wallet = $this->usermodel->userWallet('withdrawal', $show->id);
        $this->db->insert(
            'tb_wallet_balance',
            [
                'w_balance_wallet_id'       => $wallet->wallet_id,
                'w_balance_amount'          => $bagianKU,
                'w_balance_type'            => 'credit',
                'w_balance_desc'            => 'Bonus Ranking dari Omset Bulan ' . date('Y-m-t', strtotime('-1 month', now())),
                'w_balance_date_add'        => sekarang(),
                'w_balance_txid'            => strtolower(random_string('alnum', 64)),
                'w_balance_ket'             => 'ranking',
            ]
        );
        echo $bagianKU;
    }
}


    /*============================================
    =	       GENERATE USER ROYAL               =
    ============================================*/
    function save_USERROYAL()
    {
        $this->db->where('group_id', (int)2);
        $this->db->join('tb_users_groups', 'tb_users.id = tb_users_groups.user_id');
        $get_USER = $this->db->get('tb_users');

        foreach ($get_USER->result() as $showw) {
            // JIKA ID RANK >= 4 KARENA TIDAK SEMUA RANK DAPAT ROYALTI
            if ($this->rank->qualifSP($showw->id, 2)['idrank'] >= 4) {
                $CEK_Data = $this->rank->qualifSP($showw->user_id, 2);

                $this->db->where('usershare_status', 'royalty');
                $this->db->where('usershare_userid', $showw->user_id);
                $cekUSERSHARE = $this->db->get('tb_usershare');
                if ($cekUSERSHARE->num_rows() == 0) {
                    $this->db->insert(
                        'tb_usershare',
                        [
                            'usershare_userid'  => $showw->user_id,
                            'usershare_rankid'  => $CEK_Data['idrank'],
                            'usershare_status'  => 'royalty',
                            'usershare_date'    => sekarang(),
                            'usershare_code'    => strtolower(random_string('alnum', 64)),
                        ]
                    );
                } else {
                    // UPDATE JIKA SUDAH ADA TUJUAN AGAR TIDAK DOUBLE ATAU JIKA SUDAH ADA TAPI RANKID BEDA
                    $this->db->update(
                        'tb_usershare',
                        [
                            'usershare_rankid'  => $CEK_Data['idrank'],
                            'usershare_date'    => sekarang(),
                        ],
                        [
                            'usershare_userid'  => $showw->user_id,
                        ]
                    );
                }
            }
        }
    }

    function shareUSERROYAL()
    {
        // $startblnlalu   = date('Y-m-01 00:00:00', strtotime('-1 month', now()));
        // $endlnlalu      = date('Y-m-t 23:59:59', strtotime('-1 month', now()));
        $startblnlalu = date('Y-m-01 00:00:00', strtotime('first day of this month')); // Tanggal pertama bulan ini
        $endlnlalu = date('Y-m-01 00:00:00', strtotime(now())); // Tanggal pertama bulan ini



        $nilaiBV         = 1000;

        $saldo_bulanlalu = 0;
        $this->db->select_sum('omset_amount');
        $this->db->where('omset_date BETWEEN "' . $startblnlalu . '" AND "' . $endlnlalu . '"');
        $getbulanlalu     = $this->db->get('tb_omset');
        $get_bulanlalu    = $getbulanlalu->row()->omset_amount;
        if (!empty($get_bulanlalu)) {
            $saldo_bulanlalu     = $get_bulanlalu;
        }

        // AMBIL 18% DARI OMSET BV
        $BVRoyal = (18 / 100) * $saldo_bulanlalu;

        // GET USER ROYALTY
        $this->db->where('usershare_status', 'royalty');
        $this->db->where('tb_rank.rank_royalty !=', 0);
        $this->db->join('tb_rank', 'usershare_rankid = rank_id');
        $this->db->join('tb_users', 'usershare_userid = id');
        $userroyal = $this->db->get('tb_usershare');

        $totalUSER = $userroyal->num_rows();

        // CONVER NILAI BV KE IDR (10.000). TOTAL AKAN DIBAGI KE USER
        $ShareIDR = ($nilaiBV * $BVRoyal) / $totalUSER;

        foreach ($userroyal->result() as $show) {
            // BAGIANKU BERDASARKAN OMSET BULAN LALU
            $bagianKU = ($show->rank_royalty / 100) * $ShareIDR;

            // Bonus berdasarkan rank
            switch ($show->rank_royalty) {
                case 8: // MANAGER
                    $bagianKU *= (8 / 100);
                    break;
                case 6: // CROWN
                    $bagianKU *= (6 / 100);
                    break;
                case 4: // ROYAL
                    $bagianKU *= (4 / 100);
                    break;
                default:
                    break;
            }

            // Bagi dengan jumlah seluruh user dengan rank tertentu
            $this->db->where('usershare_status', 'royalty');
            $this->db->where('usershare_rankid', $show->rank_id);
            $jumlahUserRank = $this->db->count_all_results('tb_usershare');

            if ($jumlahUserRank > 0) {
                $bagianKU /= $jumlahUserRank;
            }

            // INPUT WALLET
            $wallet = $this->usermodel->userWallet('withdrawal', $show->id);
            $this->db->insert(
                'tb_wallet_balance',
                [
                    'w_balance_wallet_id'       => $wallet->wallet_id,
                    'w_balance_amount'          => $bagianKU,
                    'w_balance_type'            => 'credit',
                    'w_balance_desc'            => 'Bonus Royalty dari Omset Bulan ' . date('Y-m-t', strtotime('-1 month', now())),
                    'w_balance_date_add'        => sekarang(),
                    'w_balance_txid'            => strtolower(random_string('alnum', 64)),
                    'w_balance_ket'             => 'royalty',
                ]
            );
        }
    }
}
