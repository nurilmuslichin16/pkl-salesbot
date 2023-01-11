<?php

ini_set('date.timezone', 'Asia/Jakarta');

if (!defined('HS')) {
    die('Tidak boleh diakses langsung.');
}


/*
 *  Programmer  : Abdul Hakim Hassan
 *  Email       : abdulhakimhsn@gmail.com
 *  Telegram    : @abdulhakimhs
 *
 *  Pembuatan   : November 2019
 *
 *  ____________________________________________________________
*/

function prosesApiMessage($sumber)
{
    $updateid = $sumber['update_id'];

    // if ($GLOBALS['debug']) mypre($sumber);

    if (isset($sumber['message'])) {
        $message = $sumber['message'];

        if (isset($message['text']) || isset($message['location']['latitude'])) {
            prosesPesanTeks($message);
        } elseif (isset($message['sticker'])) {
            prosesPesanSticker($message);
        } else {
            // gak di proses silakan dikembangkan sendiri
        }
    }

    if (isset($sumber['callback_query'])) {
        prosesCallBackQuery($sumber['callback_query']);
    }

    return $updateid;
}

function prosesPesanSticker($message)
{
    // if ($GLOBALS['debug']) mypre($message);
}

function prosesCallBackQuery($message)
{
    // if ($GLOBALS['debug']) mypre($message);
    include 'bot-koneksi.php';
    $message_id = $message['message']['message_id'];
    $chatid     = $message['message']['chat']['id'];
    $data       = $message['data'];
    $meid       = $message['message']["message_id"];
    $meidd      = $meid - 1;
    $time       = date('Y-m-d H:i:s');
    $fromfname      = $message['from']['first_name'];
    $fromname       = $message['from']['username'];
    $telegramname   = is_null($fromfname) ? str_replace("'", "", $fromname) : str_replace("'", "", $fromfname);

    $pecah = explode("-", $data);

    if ($data == '1' || $data == '2' || $data == '3') {
        $text = 'Maaf fungsi ini sedang dimatikan!';
        sendApiMsg($chatid, $text);
        sendApiHideKeyboard($chatid, 'Keyboard dihapus!');

        /*
        $cekkat = mysqli_query($koneksi, "SELECT kategori FROM tb_sales WHERE message_id = '$meidd' AND kategori IS NOT NULL");
        if (mysqli_num_rows($cekkat) > 0) {
            $ambil = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE message_id = '$meidd'");
            if (mysqli_num_rows($ambil) > 0) {
                while ($d = mysqli_fetch_array($ambil)) {
                    if ($d['kategori'] == 1) {
                        $kategori = 'DEAL, ODP READY';
                    } elseif ($d['kategori'] == 2) {
                        $kategori = 'DEAL, ODP NOT READY';
                    } elseif ($d['kategori'] == 3) {
                        $kategori = 'UNSC';
                    }

                    $text = "❗️ Order JA$d[sales_id] sudah dipilih untuk kategori $kategori";
                    sendApiMsg($chatid, $text);
                }
            }
        } else {
            $update = mysqli_query($koneksi, "UPDATE tb_sales SET kategori='$data' WHERE message_id = $meidd AND message_from = $fromid");
            if ($update) {
                $ambil = mysqli_query($koneksi, "SELECT * FROM  tb_sales s JOIN tb_salesman u ON u.s_telegram_id = s.message_from WHERE message_id = '$meidd' AND message_from = $fromid");
                if (mysqli_num_rows($ambil) > 0) {
                    while ($d = mysqli_fetch_array($ambil)) {
                        if ($d['kategori'] == 1) {
                            $kategori = 'DEAL, ODP READY';
                        } elseif ($d['kategori'] == 2) {
                            $kategori = 'DEAL, ODP NOT READY';
                        } elseif ($d['kategori'] == 3) {
                            $kategori = 'UNSC';
                        }
                        $pecahloc       = explode(',', $d['lat_long']);
                        $lat            = str_replace(" ", "", $pecahloc[0]);
                        $lng            = str_replace(" ", "", $pecahloc[1]);
                        $text = "ORDER\n";
                        $text  .= "JA$d[sales_id]\n";
                        $text  .= "NAMA PELANGGAN : $d[nama_pelanggan]\n";
                        $text  .= "CP : $d[cp]\n";
                        $text  .= "ODP : $d[odp]\n";
                        $text  .= "ALAMAT : $d[alamat]\n";
                        $text  .= "MYIR : $d[myir]\n";
                        $text  .= "PAKET : $d[paket]\n";
                        $text  .= "KATEGORI : $kategori\n";
                        $text  .= "SALES : $d[fullname]\n";
                        $text  .= "LOKASI : \nhttps://www.google.co.id/maps/search/$lat,+$lng";
                        sendApiMsg($chatid, $text);

                        $savelog = mysqli_query($koneksi, "INSERT INTO tb_log (sales_id, action_by, action_on, action_status) VALUES ($d[sales_id], '$d[fullname]', '$time', 1)");
                        if ($d['kategori'] == 1) {
                            $dtl = $d['datel'];

                            $text = "ORDER\n";
                            $text .= "JA$d[sales_id] \n";
                            $text .= "NAMA PELANGGAN : $d[nama_pelanggan]\n";
                            $text .= "CP : $d[cp]\n";
                            $text .= "ODP : $d[odp]\n";
                            $text .= "ALAMAT : $d[alamat]\n";
                            $text .= "JARAK TIANG : $d[jarak_tiang]\n";
                            $text .= "KATEGORI : $kategori\n";
                            $text .= "MYIR : $d[myir]\n";
                            $text .= "PAKET : $d[paket]\n";
                            $text .= "SALES : $d[fullname]\n";
                            $text .= "LOKASI : \nhttps://www.google.co.id/maps/search/$lat,+$lng";

                            switch ($dtl) {
                                case 'PKL':
                                    $chatid = -263944966;
                                    break;
                                case 'BRB':
                                    $chatid = -341677349;
                                    break;
                                case 'BTG':
                                    $chatid = -280898518;
                                    break;
                                case 'TEG':
                                    $chatid = -338613303;
                                    break;
                                case 'SLW':
                                    $chatid = -336766109;
                                    break;
                                case 'PML':
                                    $chatid = -371367732;
                                    break;

                                default:

                                    break;
                            }
                            sendApiMsg($chatid, $text);
                        }
                    }
                }
            } else {
                $text = 'Gagal Update Data! Silahkan coba beberapa saat lagi..';
                sendApiMsg($chatid, $text);
            }
        }
        */
    } else if ($pecah[0] == 'ODP' || $pecah[0] == 'PELANGGAN') {
        if ($pecah[0] == 'ODP') {
            $text = "KENDALA\n";
            $text .= "JA$pecah[1]\n";
            $text .= "$pecah[2]\n";
            $text .= "Share lokasi ODP:";
            sendApiMsgReply($chatid, $text);
        } else {
            $text = "KENDALA\n";
            $text .= "JA$pecah[1]\n";
            $text .= "$pecah[2]\n";
            $text .= "Share lokasi pelanggan:";
            sendApiMsgReply($chatid, $text);
        }
    } else if ($pecah[0] == 'KENDALA PELANGGAN' || $pecah[0] == 'KENDALA INSTALASI' || $pecah[0] == 'MAINTENANCE' || $pecah[0] == 'KENDALA TEKNIS') {
        $sales_id = $pecah[1];

        if ($pecah[0] == 'KENDALA PELANGGAN') {
            $inkeyboard = [
                [
                    ['text' => '🔙 KEMBALI', 'callback_data' => "KEMBALI-$sales_id"],
                ],
                [
                    ['text' => 'RNA', 'callback_data' => "RNA-$sales_id"],
                ],
                [
                    ['text' => 'ALAMAT', 'callback_data' => "ALAMAT-$sales_id"],
                ],
                [
                    ['text' => 'BATAL', 'callback_data' => "BATAL-$sales_id"],
                ],
                [
                    ['text' => 'PENDING', 'callback_data' => "PENDING-$sales_id"],
                ],
                [
                    ['text' => 'IJIN TANAM TIANG', 'callback_data' => "IJIN TANAM TIANG-$sales_id"],
                ],
                [
                    ['text' => 'NJKI', 'callback_data' => "NJKI-$sales_id"],
                ]
            ];
        } else if ($pecah[0] == 'KENDALA INSTALASI') {
            $inkeyboard = [
                [
                    ['text' => '🔙 KEMBALI', 'callback_data' => "KEMBALI-$sales_id"],
                ],
                [
                    ['text' => 'TIANG', 'callback_data' => "TIANG-$sales_id"],
                ],
                [
                    ['text' => 'RUTE INSTALASI', 'callback_data' => "RUTE INSTALASI-$sales_id"],
                ],
                [
                    ['text' => 'PENDING INSTALASI', 'callback_data' => "PENDING INSTALASI-$sales_id"],
                ]
            ];
        } else if ($pecah[0] == 'MAINTENANCE') {
            $inkeyboard = [
                [
                    ['text' => '🔙 KEMBALI', 'callback_data' => "KEMBALI-$sales_id"],
                ],
                [
                    ['text' => 'ODP LOSS', 'callback_data' => "ODP LOSS-$sales_id"],
                ],
                [
                    ['text' => 'ODP RETI', 'callback_data' => "ODP RETI-$sales_id"],
                ],
                [
                    ['text' => 'ONU > 32', 'callback_data' => "ONU > 32-$sales_id"],
                ]
            ];
        } else {
            $inkeyboard = [
                [
                    ['text' => '🔙 KEMBALI', 'callback_data' => "KEMBALI-$sales_id"],
                ],
                [
                    ['text' => 'ODP FULL', 'callback_data' => "ODP FULL-$sales_id"],
                ],
                [
                    ['text' => 'PT2', 'callback_data' => "PT2-$sales_id"],
                ],
                [
                    ['text' => 'NO FO/ODP', 'callback_data' => "NO FO/ODP-$sales_id"],
                ],
                [
                    ['text' => 'ODP BLM LIVE', 'callback_data' => "ODP BLM LIVE-$sales_id"],
                ]
            ];
        }

        sendApiKeyboard($chatid, 'Silahkan pilih kendala nya :', $inkeyboard, true);
    } else if ($pecah[0] == 'KEMBALI') {
        $sales_id = $pecah[1];

        $inkeyboard = [
            [
                ['text' => 'KENDALA PELANGGAN', 'callback_data' => "KENDALA PELANGGAN-$sales_id"],
            ],
            [
                ['text' => 'KENDALA INSTALASI', 'callback_data' => "KENDALA INSTALASI-$sales_id"],
            ],
            [
                ['text' => 'MAINTENANCE', 'callback_data' => "MAINTENANCE-$sales_id"],
            ],
            [
                ['text' => 'KENDALA TEKNIS', 'callback_data' => "KENDALA TEKNIS-$sales_id"],
            ]
        ];

        sendApiKeyboard($chatid, 'Silahkan pilih jenis kendala nya :', $inkeyboard, true);
    } else {
        $sales_id   = $pecah[1];
        $kendala    = $pecah[0];

        if ($kendala == 'RNA' || $kendala == 'ALAMAT' || $kendala == 'BATAL' || $kendala == 'PENDING' || $kendala == 'PENDING INSTALASI') {
            $text = "KENDALA\n";
            $text .= "JA$sales_id\n";
            $text .= "$kendala\n";
            $text .= "Oke sekarang tulis keterangan tentang kendala ini:";
            sendApiMsgReply($chatid, $text);
        } else if ($kendala == 'IJIN TANAM TIANG' || $kendala == 'NJKI' || $kendala == 'TIANG' || $kendala == 'RUTE INSTALASI' || $kendala == 'ODP FULL' || $kendala == 'PT2' || $kendala == 'NO FO/ODP') {
            $text = "KENDALA\n";
            $text .= "JA$sales_id\n";
            $text .= "$kendala\n";
            $text .= "Share lokasi pelanggan:";

            sendApiMsgReply($chatid, $text);
        } else {
            $text = "KENDALA\n";
            $text .= "JA$sales_id\n";
            $text .= "$kendala\n";
            $text .= "Silahkan pilih lokasi Pelanggan atau ODP:";

            $inkeyboard = [
                [
                    ['text' => 'PELANGGAN', 'callback_data' => "PELANGGAN-$sales_id-$kendala"],
                ],
                [
                    ['text' => 'ODP', 'callback_data' => "ODP-$sales_id-$kendala"],
                ]
            ];

            sendApiKeyboard($chatid, $text, $inkeyboard, true);
        }
    }
}


function prosesPesanTeks($message)
{
    // if ($GLOBALS['debug']) mypre($message);
    include 'bot-koneksi.php';
    $pesan      = $message['text'];
    $chatid     = $message['chat']['id'];
    $fromid     = $message['from']['id'];
    $fromfname      = $message['from']['first_name'];
    $fromname       = $message['from']['username'];
    $telegramname   = is_null($fromfname) ? $fromname : $fromfname;
    $grupname   = !empty($message["chat"]["title"]) ? $message["chat"]["title"] : 'null';
    $str        = !empty($message["reply_to_message"]["text"]) ? strtok($message["reply_to_message"]["text"], "\n") : 'NOTHING';
    $replyp     = '!create data';
    $meid       = $message["message_id"];
    $meidd      = $meid - 3;
    $meiddd     = $meid - 5;
    $type_ms    = $message["chat"]["type"];
    $time       = date('Y-m-d H:i:s');
    $masuk_switch_r = false;
    $masuk_switch_m = true;

    if (isset($message['reply_to_message']['text'])) {
        $masuk_switch_r = true;
        $masuk_switch_m = false;
    }

    if ($masuk_switch_r) {
        $reply      = $message["reply_to_message"]["text"];
        $reqsc      = array();

        switch (true) {

                /*register helpdesk*/
            case $reply == 'Silahkan tulis siapa nama kamu?':

                $query = mysqli_query($koneksi, "INSERT INTO tb_helpdesk (h_telegram_id, nama_hd) VALUES ($fromid, '$pesan')");
                sendApiAction($chatid);
                if ($query) {
                    $text = "Halo $pesan 👋🏻, pendaftaran berhasil dilakukan, kamu akan diberitahu kembali jika akun ini sudah diapprove oleh admin :)";
                } else {
                    $text = "❗️ Maaf pendaftaran gagal dilakukan. Silahkan coba beberapa saat lagi..";
                }
                sendApiMsg($chatid, $text);
                break;
                /*end register helpdesk*/

                /*register sales*/
            case $reply == 'Silahkan masukan nama kamu':
                $pesan = strtoupper($pesan);
                $query = mysqli_query($koneksi, "INSERT INTO tb_salesman (s_telegram_id, fullname) VALUES ($fromid, '$pesan')");
                sendApiAction($chatid);
                if ($query) {
                    $text = '📱 Masukan No HP';
                    sendApiMsgReply($chatid, $text);
                } else {
                    $text = "❗️ Maaf pendaftaran gagal dilakukan. Silahkan coba beberapa saat lagi..";
                    $text .= "Error : " . mysqli_error($koneksi);
                    sendApiMsg($chatid, $text);
                }
                break;

            case $reply == '📱 Masukan No HP':

                if (is_numeric($pesan) == false) {
                    $text = 'Isi NO HP hanya dengan angka!';
                    sendApiMsg($chatid, $text);
                    $text = '📱 Masukan No HP';
                    sendApiMsgReply($chatid, $text);
                } else {
                    if (strlen($pesan) < 10) {
                        $text = 'Masukan NO HP dengan benar!';
                        sendApiMsg($chatid, $text);
                        $text = '📱 Masukan No HP';
                        sendApiMsgReply($chatid, $text);
                    } else {
                        $query = mysqli_query($koneksi, "UPDATE tb_salesman SET no_hp = '$pesan' WHERE s_telegram_id=$fromid");
                        sendApiAction($chatid);
                        if ($query) {
                            $text = 'Masukan Kode Sales';
                            sendApiMsgReply($chatid, $text);
                        } else {
                            $text = "❗️ Maaf pendaftaran gagal dilakukan. Silahkan coba beberapa saat lagi..";
                            sendApiMsg($chatid, $text);
                        }
                    }
                }

                break;

            case $reply == 'Masukan Kode Sales':
                $pesan = strtoupper($pesan);
                $query = mysqli_query($koneksi, "UPDATE tb_salesman SET kode = '$pesan' WHERE s_telegram_id=$fromid");
                sendApiAction($chatid);
                if ($query) {
                    $text = 'Masukan No Mobi';
                    sendApiMsgReply($chatid, $text);
                } else {
                    $text = "❗️ Maaf pendaftaran gagal dilakukan. Silahkan coba beberapa saat lagi..";
                    sendApiMsg($chatid, $text);
                }
                break;

            case $reply == 'Masukan No Mobi':
                $pesan = strtoupper($pesan);
                $query = mysqli_query($koneksi, "UPDATE tb_salesman SET no_mobi = '$pesan' WHERE s_telegram_id=$fromid");
                sendApiAction($chatid);
                if ($query) {
                    $text = 'Masukan Nama Mitra';
                    sendApiMsgReply($chatid, $text);
                    $text = "Isi Mitra dengan nama perusahaan. Tanpa PT. misal : KOPEGTEL, INFOMEDIA";
                    sendApiMsg($chatid, $text);
                } else {
                    $text = "❗️ Maaf pendaftaran gagal dilakukan. Silahkan coba beberapa saat lagi..";
                    sendApiMsg($chatid, $text);
                }
                break;

            case $reply == 'Masukan Nama Mitra':
                $pesan = strtoupper($pesan);
                $query = mysqli_query($koneksi, "UPDATE tb_salesman SET mitra = '$pesan' WHERE s_telegram_id=$fromid");
                $ambil = mysqli_query($koneksi, "SELECT fullname FROM tb_salesman WHERE s_telegram_id = '$fromid'");
                sendApiAction($chatid);
                if ($query) {
                    while ($d = mysqli_fetch_array($ambil)) {
                        $text = "Halo $d[fullname] 👋🏻, pendaftaran berhasil dilakukan, kamu akan diberitahu kembali jika akun ini sudah diapprove oleh admin :)";
                    }
                } else {
                    $text = "❗️ Maaf pendaftaran gagal dilakukan. Silahkan coba beberapa saat lagi..";
                }
                sendApiMsg($chatid, $text);
                $text = "👋 Ada yang mendaftar sebagai sales, segera approve user supaya dapat mengirim data deal sales.";
                sendApiMsg(31575810, $text);
                break;
                /*end register sales*/

                /*register teknisi*/
            case $reply == 'Silahkan masukan nik kamu :':

                if (is_numeric($pesan) == false) {
                    $text = 'Isi NIK hanya dengan angka!';
                    sendApiMsg($chatid, $text);
                    $text = 'Silahkan masukan nik kamu :';
                    sendApiMsgReply($chatid, $text);
                } else {
                    if (strlen($pesan) != 8) {
                        $text = 'Isi NIK dengan 8 digit angka!';
                        sendApiMsg($chatid, $text);
                        $text = 'Silahkan masukan nik kamu :';
                        sendApiMsgReply($chatid, $text);
                    } else {
                        $query = mysqli_query($koneksi, "INSERT INTO tb_teknisi (t_telegram_id, nik) VALUES ($fromid, '$pesan')");
                        sendApiAction($chatid);
                        if ($query) {
                            $text = '👤 Masukan nama kamu :';
                            sendApiMsgReply($chatid, $text);
                        } else {
                            $text = "❗️ Maaf pendaftaran gagal dilakukan. Silahkan coba beberapa saat lagi.. \n";
                            $text .= "Error : " . mysqli_error($koneksi) . " ";
                            sendApiMsg($chatid, $text);
                        }
                    }
                }

                break;

            case $reply == '👤 Masukan nama kamu :':

                $query = mysqli_query($koneksi, "UPDATE tb_teknisi SET nama_teknisi = '$pesan' WHERE t_telegram_id=$fromid");
                sendApiAction($chatid);
                if ($query) {
                    $text = "Masukan KODE DATEL dengan, Contoh : PKL1, PKL2, SLW, BTG, TEG, BRB, PML";
                    sendApiMsg($chatid, $text);
                    $text = '👥 Masukan KODE DATEL :';
                    sendApiMsgReply($chatid, $text);
                } else {
                    $text = "❗️ Maaf pendaftaran gagal dilakukan. Silahkan coba beberapa saat lagi..";
                    sendApiMsg($chatid, $text);
                }
                break;

            case $reply == '👥 Masukan KODE DATEL :':
                $pesan = strtoupper($pesan);
                if (($pesan == 'PKL1') || ($pesan == 'PKL2') || ($pesan == 'SLW') || ($pesan == 'BTG') || ($pesan == 'TEG') || ($pesan == 'BRB') || ($pesan == 'PML')) {
                    $query = mysqli_query($koneksi, "UPDATE tb_teknisi SET datel = '$pesan' WHERE t_telegram_id=$fromid");
                    sendApiAction($chatid);
                    if ($query) {
                        $text = 'Masukan KODE CREW :';
                        sendApiMsgReply($chatid, $text);
                    } else {
                        $text = "❗️ Maaf pendaftaran gagal dilakukan. Silahkan coba beberapa saat lagi..";
                        sendApiMsg($chatid, $text);
                    }
                } else {
                    $text = "KODE DATEL yang kamu masukan salah. Contoh KODE DATEL: PKL1, PKL2, SLW, BTG, TEG, BRB, PML";
                    sendApiMsg($chatid, $text);
                    $text = '👥 Masukan KODE DATEL :';
                    sendApiMsgReply($chatid, $text);
                }
                break;

            case $reply == 'Masukan KODE CREW :':
                $pesan = strtoupper($pesan);
                $query = mysqli_query($koneksi, "UPDATE tb_teknisi SET crew = '$pesan' WHERE t_telegram_id=$fromid");
                sendApiAction($chatid);
                if ($query) {
                    $text = 'Masukan Mitra :';
                    sendApiMsgReply($chatid, $text);
                    $text = "Isi Mitra dengan nama perusahaan. misal : HCP, TA, GLOBAL, KOPEGTEL, ZAG, KJS";
                    sendApiMsg($chatid, $text);
                } else {
                    $text = "❗️ Maaf pendaftaran gagal dilakukan. Silahkan coba beberapa saat lagi..";
                    sendApiMsg($chatid, $text);
                }
                break;

            case $reply == 'Masukan Mitra :':
                $pesan = strtoupper($pesan);
                $query = mysqli_query($koneksi, "UPDATE tb_teknisi SET mitra = '$pesan' WHERE t_telegram_id=$fromid");
                $ambil = mysqli_query($koneksi, "SELECT nama_teknisi FROM tb_teknisi WHERE t_telegram_id = '$fromid'");
                sendApiAction($chatid);
                if ($query) {
                    while ($d = mysqli_fetch_array($ambil)) {
                        $text = "Halo $d[nama_teknisi] 👋🏻, pendaftaran berhasil dilakukan, kamu akan diberitahu kembali jika akun ini sudah diapprove oleh admin :)";
                    }
                } else {
                    $text = "❗️ Maaf pendaftaran gagal dilakukan. Silahkan coba beberapa saat lagi..";
                }
                sendApiMsg($chatid, $text);
                break;
                /*end register teknisi*/

            case $str   == '!create data':
                if ($pesan == 'wait') {
                    sendApiAction($chatid);

                    $replymid   = $message["reply_to_message"]["message_id"];
                    $replygid   = $message["reply_to_message"]["chat"]["id"];
                    $hd         = $message["from"]["username"];
                    $tgl_up     = date('Y-m-d H:i:s');
                    $cekhd      = mysqli_query($koneksi, "SELECT status_id FROM tb_provisioning WHERE message_id=$replymid AND group_id=$replygid AND status_id >= 2");
                    $cekdata    = mysqli_query($koneksi, "SELECT status_id,sc_baru FROM tb_provisioning WHERE message_id=$replymid AND group_id=$replygid");
                    $cekuh      = mysqli_query($koneksi, "SELECT h_telegram_id FROM tb_helpdesk WHERE h_telegram_id = '$fromid'");
                    $cekha      = mysqli_query($koneksi, "SELECT h_telegram_id,active FROM tb_helpdesk WHERE h_telegram_id = '$fromid' AND active = 1");
                    if (mysqli_num_rows($cekuh) > 0) {
                        if (mysqli_num_rows($cekha) > 0) {
                            if (mysqli_num_rows($cekhd) > 0) {
                                $text = "order sudah ada yang progress.. silahkan progress yg lain ya";
                            } else {
                                if (mysqli_num_rows($cekdata) > 0) {
                                    $queryupp = mysqli_query($koneksi, "UPDATE tb_provisioning SET 
                                    hd_penerima='$fromid', 
                                    tgl_update='$tgl_up',
                                    status='progress', status_id=2 WHERE message_id=$replymid AND group_id=$replygid");

                                    while ($z = mysqli_fetch_array($cekdata)) {
                                        $sc_sales = $z['sc_baru'];
                                        $queryupp_sales = mysqli_query($koneksi, "UPDATE tb_sales SET 
                                        tgl_update='$tgl_up',
                                        status='prog act', status_id=71 WHERE new_sc = " . $sc_sales . " ");
                                    }

                                    if ($queryupp) {
                                        $text  = "Order di progress @$hd";
                                    } else {
                                        $text  = "Maaf ada masalah! Gagal Update Data!";
                                    }
                                } else {
                                    $text = "Datanya belum masuk HD 🙈";
                                }
                            }
                        } else {
                            $text = 'Kamu sudah terdaftar sebagai Help Desk, namun belum di approve admin..';
                        }
                    } else {
                        $text = 'Kamu belum terdaftar sebagai HD, silahkan register dengan /registerhd';
                    }
                    sendApiMsg($chatid, $text, $meid);
                } else {
                    /*bersih bersih pesan*/
                    $pesan = strtoupper($message["reply_to_message"]["text"]);
                    $pesan = str_replace('A/N', 'AN', $pesan);
                    $pesan = str_replace('A/N ', 'AN', $pesan);
                    $pesan = str_replace('ID VALINS', 'IDVALINS', $pesan);
                    $pesan = str_replace('NAMA', 'AN', $pesan);
                    $pesan = str_replace('NO.TELP', 'TELP', $pesan);
                    $pesan = str_replace('NO.INET', 'INET', $pesan);
                    $pesan = str_replace('STB.ID', 'STB ID', $pesan);
                    $pesan = str_replace('SPL 1/2', 'SPL 1:2', $pesan);
                    $pesan = str_replace('SPLITER 1/2', 'SPL 1:2', $pesan);
                    $pesan = str_replace('SPLITTER 1/2', 'SPL 1:2', $pesan);
                    $pesan = str_replace('SPLITTER 1:2', 'SPL 1:2', $pesan);
                    $pesan = str_replace('SPL 1/4', 'SPL 1:4', $pesan);
                    $pesan = str_replace('SPLITER 1/4', 'SPL 1:4', $pesan);
                    $pesan = str_replace('SPLITTER 1/4', 'SPL 1:4', $pesan);
                    $pesan = str_replace('SPLITTER 1:4', 'SPL 1:4', $pesan);
                    $pesan = str_replace('SPL 1/8', 'SPL 1:8', $pesan);
                    $pesan = str_replace('SPLITER 1/8', 'SPL 1:8', $pesan);
                    $pesan = str_replace('SPLITTER 1/8', 'SPL 1:8', $pesan);
                    $pesan = str_replace('SPLITTER 1:8', 'SPL 1:8', $pesan);
                    $pesan = str_replace('CASET', 'CASSETE', $pesan);
                    $pesan = str_replace('KASET', 'CASSETE', $pesan);
                    $pesan = str_replace('CASETTE', 'CASSETE', $pesan);
                    $pesan = str_replace('BRIKET', 'BREKET', $pesan);
                    $pesan = str_replace('BRACET', 'BREKET', $pesan);
                    $pesan = str_replace('S CLAM', 'SCLAM', $pesan);
                    $pesan = str_replace('SN ONT', 'SN', $pesan);
                    $pesan = str_replace('STB ID', 'STBID', $pesan);
                    $pesan = str_replace('IP CAM', 'IPCAM', $pesan);
                    $pesan = str_replace('WIFI EXT', 'WIFIEXT', $pesan);
                    $pesan = str_replace('ASS TIANG', 'ASSTIANG', $pesan);

                    /* Data Provi */
                    preg_match('/IDVALINS\s{0,1}:(.+)/i', $pesan, $id_valins);
                    preg_match('/ORDER\s{0,1}:(.+)/i', $pesan, $order);
                    preg_match('/SC\s{0,1}:(.+)/i', $pesan, $sc);
                    preg_match('/AN\s{0,1}:(.+)/i', $pesan, $an);
                    preg_match('/ALAMAT\s{0,1}:(.+)/i', $pesan, $alamat);
                    preg_match('/CP\s{0,1}:(.+)/i', $pesan, $cp);
                    preg_match('/TELP\s{0,1}:(.+)/i', $pesan, $telp);
                    preg_match('/INET\s{0,1}:(.+)/i', $pesan, $inet);
                    preg_match('/ODP\s{0,1}:(.+)/i', $pesan, $odp);
                    preg_match('/PORT\s{0,1}:(.+)/i', $pesan, $port);
                    preg_match('/SISA\s{0,1}:(.+)/i', $pesan, $sisa);
                    preg_match('/SN\s{0,1}:(.+)/i', $pesan, $sn);
                    preg_match('/STBID\s{0,1}:(.+)/i', $pesan, $stb_id);
                    preg_match('/MITRA\s{0,1}:(.+)/i', $pesan, $mitra);
                    preg_match('/TEKNISI\s{0,1}:(.+)/i', $pesan, $teknisi);
                    preg_match('/CREW\s{0,1}:(.+)/i', $pesan, $crew);
                    preg_match('/BC\s{0,1}:(.+)/i', $pesan, $bc);

                    /* Material */
                    preg_match('/DC\s{0,1}:(.+)/i', $pesan, $dc);
                    preg_match('/SCLAM\s{0,1}:(.+)/i', $pesan, $sclam);
                    preg_match('/BREKET\s{0,1}:(.+)/i', $pesan, $breket);
                    preg_match('/T7\s{0,1}:(.+)/i', $pesan, $t7);
                    preg_match('/IPCAM\s{0,1}:(.+)/i', $pesan, $ip_cam);
                    preg_match('/WIFIEXT\s{0,1}:(.+)/i', $pesan, $wifi_ext);
                    preg_match('/ORBIT\s{0,1}:(.+)/i', $pesan, $orbit);
                    preg_match('/SOC\s{0,1}:(.+)/i', $pesan, $soc);
                    preg_match('/PRECON\s{0,1}:(.+)/i', $pesan, $precon);
                    preg_match('/ASSTIANG\s{0,1}:(.+)/i', $pesan, $ass_tiang);
                    preg_match('/ROS\s{0,1}:(.+)/i', $pesan, $ros);
                    preg_match('/SPL 1:2\s{0,1}:(.+)/i', $pesan, $spl12);
                    preg_match('/SPL 1:4\s{0,1}:(.+)/i', $pesan, $spl14);
                    preg_match('/SPL 1:8\s{0,1}:(.+)/i', $pesan, $spl18);
                    preg_match('/CASSETE\s{0,1}:(.+)/i', $pesan, $cassete);
                    preg_match('/ADAPTER\s{0,1}:(.+)/i', $pesan, $adapter);
                    preg_match('/UTP\s{0,1}:(.+)/i', $pesan, $utp);
                    preg_match('/RJ45\s{0,1}:(.+)/i', $pesan, $rj45);
                    preg_match('/REDAMAN\s{0,1}:(.+)/i', $pesan, $redaman);
                    preg_match('/PREKSO\s{0,1}:(.+)/i', $pesan, $prekso);
                    preg_match('/OTP\s{0,1}:(.+)/i', $pesan, $otp);

                    $order      = (trim($order[1]) != '' ? trim($order[1]) : '-');
                    $an         = (trim($an[1]) != '' ? trim($an[1]) : '-');
                    $alamat     = (trim($alamat[1]) != '' ? trim($alamat[1]) : '-');
                    $telp       = (trim($telp[1]) != '' ? trim($telp[1]) : '-');
                    $inet       = (trim($inet[1]) != '' ? trim($inet[1]) : '-');
                    $odp        = (trim($odp[1]) != '' ? trim($odp[1]) : '-');
                    $port       = (trim($port[1]) != '' ? trim($port[1]) : '-');
                    $sisa       = (trim($sisa[1]) != '' ? trim($sisa[1]) : '-');
                    $sn         = (trim($sn[1]) != '' ? trim($sn[1]) : '-');
                    $sc         = intval(preg_replace('/[^0-9]+/', '', trim($sc[1])), 10);
                    $dc         = (trim($dc[1]) != '' ? trim($dc[1]) : '-');
                    $mitra      = (trim($mitra[1]) != '' ? trim($mitra[1]) : '-');
                    $teknisi    = (trim($teknisi[1]) != '' ? trim($teknisi[1]) : '-');
                    $crew       = (trim($crew[1]) != '' ? trim($crew[1]) : '-');
                    $bc         = (trim($bc[1]) != '' ? trim($bc[1]) : '-');
                    $sclam      = !empty($sclam) ? (trim($sclam[1]) != '' ? trim($sclam[1]) : '-') : '-';
                    $breket     = !empty($breket) ? (trim($breket[1]) != '' ? trim($breket[1]) : '-') : '-';
                    $ros        = !empty($ros) ? (trim($ros[1]) != '' ? trim($ros[1]) : '-') : '-';
                    $t7         = !empty($t7) ? (trim($t7[1]) != '' ? trim($t7[1]) : '-') : '-';
                    $cp         = (trim($cp[1]) != '' ? trim($cp[1]) : '-');
                    $stbid      = !empty($stb_id) ? (trim($stb_id[1]) != '' ? trim($stb_id[1]) : '-') : '-';
                    $spl12      = !empty($spl12) ? (trim($spl12[1]) != '' ? trim($spl12[1]) : '-') : '-';
                    $spl14      = !empty($spl14) ? (trim($spl14[1]) != '' ? trim($spl14[1]) : '-') : '-';
                    $spl18      = !empty($spl18) ? (trim($spl18[1]) != '' ? trim($spl18[1]) : '-') : '-';
                    $cassete    = !empty($cassete) ? (trim($cassete[1]) != '' ? trim($cassete[1]) : '-') : '-';
                    $adapter    = !empty($adapter) ? (trim($adapter[1]) != '' ? trim($adapter[1]) : '-') : '-';
                    $utp        = !empty($utp) ? (trim($utp[1]) != '' ? trim($utp[1]) : '-') : '-';
                    $rj45       = !empty($rj45) ? (trim($rj45[1]) != '' ? trim($rj45[1]) : '-') : '-';
                    $redaman    = !empty($redaman) ? (trim($redaman[1]) != '' ? trim($redaman[1]) : '-') : '-';
                    $prekso     = !empty($prekso) ? (trim($prekso[1]) != '' ? trim($prekso[1]) : '-') : '-';
                    $otp        = !empty($otp) ? (trim($otp[1]) != '' ? trim($otp[1]) : '-') : '-';
                    $ip_cam     = !empty($ip_cam) ? (trim($ip_cam[1]) != '' ? trim($ip_cam[1]) : '-') : '-';
                    $wifi_ext   = !empty($wifi_ext) ? (trim($wifi_ext[1]) != '' ? trim($wifi_ext[1]) : '-') : '-';
                    $orbit      = !empty($orbit) ? (trim($orbit[1]) != '' ? trim($orbit[1]) : '-') : '-';
                    $soc        = !empty($soc) ? (trim($soc[1]) != '' ? trim($soc[1]) : '-') : '-';
                    $precon     = !empty($precon) ? (trim($precon[1]) != '' ? trim($precon[1]) : '-') : '-';
                    $ass_tiang  = !empty($ass_tiang) ? (trim($ass_tiang[1]) != '' ? trim($ass_tiang[1]) : '-') : '-';

                    if (isset($message['location']['latitude'])) {
                        sendApiAction($chatid);

                        $latitude   = $message["location"]["latitude"];
                        $longitude  = $message["location"]["longitude"];
                        $lokasiodp  = $latitude . ',' . $longitude;
                        $meid       = $message["message_id"];

                        $replyfromid     = $message["reply_to_message"]['from']['id'];

                        if ($fromid == $replyfromid) {
                            $cekScProvi      = mysqli_query($koneksi, "SELECT sc_baru,status_id FROM tb_provisioning WHERE sc_baru = '$sc'");
                            $cekScTeknisi    = mysqli_query($koneksi, "SELECT sales_id,progress_to FROM tb_sales WHERE new_sc = '$sc'");
                            if (mysqli_num_rows($cekScProvi) > 0) {
                                while ($d = mysqli_fetch_array($cekScTeknisi)) {
                                    $sales_id = $d['sales_id'];
                                    $ambilt = mysqli_query($koneksi, "SELECT nama_teknisi FROM tb_teknisi WHERE t_telegram_id = '$fromid'");
                                    while ($t = mysqli_fetch_array($ambilt)) {
                                        $udatasales = mysqli_query($koneksi, "UPDATE tb_sales SET loc_cust = '$lokasiodp', tgl_update = '$time' WHERE sales_id='$sales_id'");
                                    }
                                }

                                $alert = "SC : $sc\n";
                                $alert .= "Tikor Pelanggan : $lokasiodp\n\n";
                                $alert .= "Oke, tikor pelanggan berhasil diupdate.";
                                sendApiMsg($chatid, $alert, $meid);
                            } else {
                                // variable pelengkap
                                $odp            = str_replace(' ', '', $odp);
                                $tgl            = date('Y-m-d H:i:s');
                                $post_name      = $message["from"]["username"];
                                $m_id           = $message["reply_to_message"]["message_id"];
                                $g_id           = $chatid;
                                $datel          = getDatel($odp);

                                //$cekmitra       = mysqli_query($koneksi, "SELECT nama_mitra FROM tb_mitra WHERE singkat = '$mitra' OR nama_mitra LIKE '%$mitra%'");
                                $cekut          = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid'");
                                $cekscsales     = mysqli_query($koneksi, "SELECT sales_id,new_sc,message_from,message_id FROM tb_sales WHERE new_sc = '$sc'");
                                $cekScProvi     = mysqli_query($koneksi, "SELECT sc_baru,status_id FROM tb_provisioning WHERE sc_baru = '$sc'");
                                $ceksckendala   = mysqli_query($koneksi, "SELECT new_sc,status_id FROM tb_sales WHERE (new_sc = '$sc') AND (status_id = 6 OR status_id = 12 OR status_id = 13) AND (progress_to IS NULL)");
                                $cekbc          = mysqli_query($koneksi, "SELECT qrcode FROM tb_qrcode WHERE qrcode='" . $bc . "'");
                                $cekbcpakai     = mysqli_query($koneksi, "SELECT barcode FROM tb_provisioning WHERE barcode = '$bc'");

                                $jadwal_sholat  = file_get_contents("https://api.banghasan.com/sholat/format/json/jadwal/kota/723/tanggal/" . date('Y-m-d') . "");
                                $json           = json_decode($jadwal_sholat, TRUE);
                                $tanggal        =  $json['jadwal']['data']['tanggal'];
                                $status_api     =  $json['jadwal']['status'];
                                $ashar          =  str_replace(':', '', $json['jadwal']['data']['ashar']);
                                $dzuhur         =  str_replace(':', '', $json['jadwal']['data']['dzuhur']);
                                $isya           =  str_replace(':', '', $json['jadwal']['data']['isya']);
                                $maghrib        =  str_replace(':', '', $json['jadwal']['data']['maghrib']);
                                $subuh          =  str_replace(':', '', $json['jadwal']['data']['subuh']);

                                /* else if (mysqli_num_rows($cekmitra) <= 0) {
                                        $text = "⚠️ Mitra tidak ditemukan, silahkan perbaiki nama mitra!";
                                    } */
                                if (mysqli_num_rows($cekut) <= 0) {
                                    $text = '❗️ Kamu belum mendaftar sebagai teknisi pada @slo_jarvisid_bot. Silahkan lakukan pendaftaran dengan klik @slo_jarvisid_bot kemudian ketik /regteknisi atau /help untuk infromasi bantuan.';
                                } else if ($order != 'PSB' && $order != 'MIGRASI' && $order != 'MIG' && $order != 'MO') {
                                    $text = "⚠️ Hanya gunakan PSB, MIG/MIGRASI, dan MO untuk isian ORDER!";
                                } else if ((mysqli_num_rows($cekscsales) <= 0) && (strpos($order, 'PSB') !== false)) {
                                    $text = '❌ SC tidak valid! Mohon Cek kembali nomor SC nya, atau silahkan koordinasi dengan inputers.';
                                } else if (mysqli_num_rows($ceksckendala) > 0) {
                                    while ($k = mysqli_fetch_array($ceksckendala)) {
                                        $sc     = $k['new_sc'];
                                        $status = statusSales($k['status_id']);
                                        $text   = "❌ SC$sc tidak ditolak! Status SC $status, atau silahkan koordinasi dengan TL.";
                                    }
                                } else if (mysqli_num_rows($cekScProvi) > 0) {
                                    while ($d = mysqli_fetch_array($cekScProvi)) {
                                        $text = "SC $sc sudah ada, statusnya : " . statusSC($d['status_id']) . "";
                                    }
                                } elseif (strlen($odp) < 14 || strlen($odp) > 15) {
                                    $text = "⚠️ ODP tidak valid! tulis ODP dengan ODP : ODP-PKL-FAA/001";
                                } else if (strpos($odp, '/') == false) {
                                    $text = "tanda / nya mana? misal : ODP-PKL-FAA/001";
                                } else if (cekStoOdp(substr($odp, 4, 3)) == false) {
                                    $text = "⚠️ Pastikan format ODP, STO nya sudah benar!";
                                } else if ((mysqli_num_rows($cekbc) <= 0) && (strpos($order, 'MIG') !== false || strpos($order, 'PSB') !== false || strpos($order, 'MIGRASI') !== false)) {
                                    $text  = "⚠️ Barcode $bc tidak valid, silahkan perbaiki/ganti barcode nya! *lampirkan foto barcode jika barcode sudah sesuai.";
                                } else if ((mysqli_num_rows($cekbcpakai) > 0) && (strpos($order, 'MIG') !== false || strpos($order, 'PSB') !== false || strpos($order, 'MIGRASI') !== false)) {
                                    $text = "⚠️ Barcode $bc sudah digunakan, silahkan ganti yang lain ya..";
                                } else {
                                    $an = str_replace("Â", "", $an);
                                    $query = mysqli_query($koneksi, "INSERT INTO tb_provisioning (datel, order_type, atas_nama, alamat, cp, voice, internet, odp, port, sisa, sn, sc, sc_baru, mitra, teknisi, crew, barcode, stb_id, redaman, post_by, group_id, message_id, tgl_masuk) VALUES ('$datel', '$order', '$an', '$alamat', '$cp', '$telp', '$inet', '$odp', '$port', '$sisa', '$sn', '$sc', '$sc', '$mitra', '$teknisi', '$crew', '$bc', '$stbid', '$redaman', '$fromid', '$g_id','$m_id', '$tgl')");
                                    $idcreate = mysqli_insert_id($koneksi);
                                    if ($query) {
                                        $insmtr = mysqli_query($koneksi, "INSERT INTO tb_material (create_id, dropcore, sclam, breket, ros, t_tujuh, spl1_2, spl1_4, spl1_8, cassete, adapter, utp, rj45, prekso, otp, ip_cam, wifi_ext, orbit, soc, ass_tiang, precon) VALUES ('$idcreate', '$dc', '$sclam', '$breket', '$ros', '$t7', '$spl12', '$spl14', '$spl18', '$cassete', '$adapter', '$utp', '$rj45', '$prekso', '$otp', '$ip_cam', '$wifi_ext', '$orbit', '$soc', '$ass_tiang', '$precon')");
                                        if ($insmtr) {
                                            if ($status_api != 'error') {
                                                if (date("Hi") > "$dzuhur" && date("Hi") < "1235" && date("N") == 5) {
                                                    $text = "📢 ayoo sholatt jum'at dulu 🕌";
                                                } elseif (date("Hi") > "$dzuhur" && date("Hi") < "1220") {
                                                    $text = "📢 sudah masuk waktu dhuhur.. yuk sholat dulu ya 🕌 \n";
                                                } else if (date("Hi") > "$ashar" && date("Hi") < "1530") {
                                                    $text = "📢 sudah masuk waktu ashar.. yuk sholat dulu ya 🕌";
                                                } else if (date("Hi") > "1800") {
                                                    $text = "lanjut besok lagi ya.. langsung pulang kerumah, hindari kerumunan, jangan lupa mandi sebelum berinteraksi dengan keluarga anda dirumah :)";
                                                } else if (date("Hi") > "$maghrib" && date("Hi") < "1820") {
                                                    $text = "📢 sudah masuk waktu maghrib.. yuk sholat dulu ya 🕌";
                                                } else if (date("Hi") > "$isya" && date("Hi") < "2000") {
                                                    $text = "📢 sudah masuk waktu isya.. yuk sholat dulu ya 🕌";
                                                } else {
                                                    $text  = "yuk lanjut rekan HD 🤙";
                                                }
                                            } else {
                                                $text  = "yuk lanjut rekan HD 🤙";
                                            }

                                            while ($d = mysqli_fetch_array($cekscsales)) {
                                                $sales_id = $d['sales_id'];
                                                $s_telegram_id = $d['message_from'];
                                                $s_m_id = $d['message_id'];
                                                $ambilt = mysqli_query($koneksi, "SELECT nama_teknisi FROM tb_teknisi WHERE t_telegram_id = '$fromid'");
                                                while ($t = mysqli_fetch_array($ambilt)) {
                                                    $savelog = mysqli_query($koneksi, "INSERT INTO tb_log (sales_id, action_by, action_on, action_status) VALUES ('$sales_id','$t[nama_teknisi]','$tgl',7)");
                                                    $udatasales = mysqli_query($koneksi, "UPDATE tb_sales SET loc_cust = '$lokasiodp', status = 'wait_act', status_id = '7', tgl_update = '$tgl', tgl_req_act = '$tgl' WHERE sales_id='$sales_id'");
                                                    $pesantosales = "⚠️ Order dimintakan create oleh teknisi $t[nama_teknisi]";
                                                    sendApiMsg($s_telegram_id, $pesantosales, $s_m_id, 'Markdown');
                                                }
                                            }
                                        } else {
                                            $text  = "Error! Data Material Gagal Disimpan!";
                                            $text .= "Error : " . mysqli_error($koneksi);
                                        }
                                    } else {
                                        $text  = "Error! Data Gagal Disimpan! \n";
                                        $text .= "Error : " . mysqli_error($koneksi);
                                    }
                                }

                                $alert = "SC : $sc\n";
                                $alert .= "Tikor Pelanggan : $lokasiodp";
                                sendApiMsg($chatid, $alert, $meid);
                                sendApiMsg($chatid, $text);
                            }
                        } else {
                            $alert = "Maaf, anda bukan teknisi yang mengirim pesan Request Aktivasi SC $sc.";
                            sendApiMsg($chatid, $alert, $meid);
                        }
                    }
                }

                break;

            case $str == 'KENDALA':
                $reply_message  = $message["reply_to_message"]["text"];
                $reply_message  = preg_replace('/^.+\n/', '', $reply_message);
                $sales_id       = str_replace('JA', '', strtok($reply_message, "\n"));
                $kendala        = strtok("\n");
                $tikor1         = strtok("\n");
                $tikor2         = strtok("\n");

                if (strpos($reply, 'keterangan') !== false) {
                    $jenis_kendala  = cekJenisKendala($kendala);
                    $ket_kendala    = $kendala . '-' . $pesan;

                    if ($kendala == 'RNA' || $kendala == 'ALAMAT' || $kendala == 'BATAL' || $kendala == 'PENDING' || $kendala == 'PENDING INSTALASI') {
                        $update = mysqli_query($koneksi, "UPDATE tb_sales SET tgl_update = '$time', tgl_lapor_k = '$time', `status` = '$jenis_kendala', status_id = 6, keterangan='$pesan', kendala = '$kendala' WHERE sales_id = '$sales_id'");
                        $savelog = mysqli_query($koneksi, "INSERT INTO tb_log (sales_id, action_by, action_on, action_status, a_keterangan) VALUES ($sales_id, '$fromname', '$time', 6, '$ket_kendala')");

                        sendApiAction($chatid);

                        if ($update) {
                            // Send to Sales
                            $ceksales = mysqli_query($koneksi, "SELECT message_from, sales_id, message_id FROM tb_sales WHERE sales_id = '$sales_id'");
                            while ($d = mysqli_fetch_array($ceksales)) {
                                $sales_id       = $d['sales_id'];
                                $s_telegram_id  = $d['message_from'];
                                $s_m_id         = $d['message_id'];

                                $pesantosales = "ORDER SET TO KENDALA : ($kendala) \n";
                                $pesantosales .= "JA$sales_id\n";
                                $pesantosales .= "KET :\n";
                                $pesantosales .= "$pesan \n\n";
                                $pesantosales .= " ~ $fromname";

                                sendApiMsg($s_telegram_id, $pesantosales, $s_m_id, 'Markdown');
                            }

                            $text = "Oke kendala berhasil dilaporkan! Terima kasih :)";
                            sendApiMsg($chatid, $text);
                        } else {
                            $text = "❗️ Maaf laporan gagal dilakukan. Silahkan coba beberapa saat lagi.. $sales_id";
                            $text .= "Error : " . mysqli_error($koneksi) . " ";
                            sendApiMsg($chatid, $text);
                        }
                    } else if ($kendala == 'IJIN TANAM TIANG' || $kendala == 'NJKI' || $kendala == 'TIANG' || $kendala == 'RUTE INSTALASI' || $kendala == 'ODP FULL' || $kendala == 'PT2' || $kendala == 'NO FO/ODP') {
                        $tikor_pelanggan = str_replace(" ", "", explode(":", $tikor1));

                        $update = mysqli_query($koneksi, "UPDATE tb_sales SET tgl_update = '$time', tgl_lapor_k = '$time', loc_cust='$tikor_pelanggan[1]', `status` = '$jenis_kendala', status_id = 6, keterangan='$pesan', kendala = '$kendala' WHERE sales_id = '$sales_id'");
                        $savelog = mysqli_query($koneksi, "INSERT INTO tb_log (sales_id, action_by, action_on, action_status, a_keterangan) VALUES ($sales_id, '$fromname', '$time', 6, '$ket_kendala')");

                        sendApiAction($chatid);

                        if ($update) {
                            // Send to Sales
                            $ceksales = mysqli_query($koneksi, "SELECT message_from, sales_id, message_id FROM tb_sales WHERE sales_id = '$sales_id'");
                            while ($d = mysqli_fetch_array($ceksales)) {
                                $sales_id       = $d['sales_id'];
                                $s_telegram_id  = $d['message_from'];
                                $s_m_id         = $d['message_id'];

                                $pesantosales = "ORDER SET TO KENDALA : ($kendala) \n";
                                $pesantosales .= "JA$sales_id\n";
                                $pesantosales .= "KET :\n";
                                $pesantosales .= "$pesan \n\n";
                                $pesantosales .= " ~ $fromname";

                                sendApiMsg($s_telegram_id, $pesantosales, $s_m_id, 'Markdown');
                            }

                            $text = "Oke kendala berhasil dilaporkan! Terima kasih :)";
                            sendApiMsg($chatid, $text);
                        } else {
                            $text = "❗️ Maaf laporan gagal dilakukan. Silahkan coba beberapa saat lagi.. $sales_id";
                            $text .= "Error : " . mysqli_error($koneksi) . " ";
                            sendApiMsg($chatid, $text);
                        }
                    } else {
                        $tikor_pelanggan    = str_replace(" ", "", explode(":", $tikor1));
                        $tikor_odp          = str_replace(" ", "", explode(":", $tikor2));
                        $ket                = "$pesan | Lokasi ODP : $tikor_odp[1]";

                        $update     = mysqli_query($koneksi, "UPDATE tb_sales SET tgl_update = '$time', tgl_lapor_k = '$time', loc_cust='$tikor_pelanggan[1]', `status` = '$jenis_kendala', status_id = 6, keterangan='$ket', kendala = '$kendala' WHERE sales_id = '$sales_id'");
                        $savelog    = mysqli_query($koneksi, "INSERT INTO tb_log (sales_id, action_by, action_on, action_status, a_keterangan) VALUES ($sales_id, '$fromname', '$time', 6, '$ket_kendala')");

                        sendApiAction($chatid);

                        if ($update) {
                            // Send to Sales
                            $ceksales = mysqli_query($koneksi, "SELECT message_from, sales_id, message_id FROM tb_sales WHERE sales_id = '$sales_id'");
                            while ($d = mysqli_fetch_array($ceksales)) {
                                $sales_id       = $d['sales_id'];
                                $s_telegram_id  = $d['message_from'];
                                $s_m_id         = $d['message_id'];

                                $pesantosales = "ORDER SET TO KENDALA : ($kendala) \n";
                                $pesantosales .= "JA$sales_id\n";
                                $pesantosales .= "KET :\n";
                                $pesantosales .= "$pesan \n\n";
                                $pesantosales .= " ~ $fromname";

                                sendApiMsg($s_telegram_id, $pesantosales, $s_m_id, 'Markdown');
                            }

                            $text = "Oke kendala berhasil dilaporkan! Terima kasih :)";
                            sendApiMsg($chatid, $text);
                        } else {
                            $text = "❗️ Maaf laporan gagal dilakukan. Silahkan coba beberapa saat lagi.. $sales_id";
                            $text .= "Error : " . mysqli_error($koneksi) . " ";
                            sendApiMsg($chatid, $text);
                        }
                    }
                } elseif (strpos($reply, 'lokasi pelanggan') !== false) {
                    if (isset($message['location']['latitude'])) {
                        $latitude   = $message["location"]["latitude"];
                        $longitude  = $message["location"]["longitude"];
                        $lokasicust = $latitude . ',' . $longitude;

                        $alert = "Oke, lokasi pelanggan diterima.";
                        sendApiMsg($chatid, $alert);

                        if ($kendala == 'IJIN TANAM TIANG' || $kendala == 'NJKI' || $kendala == 'TIANG' || $kendala == 'RUTE INSTALASI' || $kendala == 'ODP FULL' || $kendala == 'PT2' || $kendala == 'NO FO/ODP') {
                            sendApiAction($chatid);

                            $text = "KENDALA\n";
                            $text .= "JA$sales_id\n";
                            $text .= "$kendala\n";
                            $text .= "Tikor Pelanggan : $lokasicust\n";
                            $text .= "Oke sekarang tulis keterangan tentang kendala ini:";

                            sendApiMsgReply($chatid, $text);
                        } else {
                            if (strpos($reply, 'Lanjut') !== false) {
                                $tikor_odp = str_replace(" ", "", explode(":", $tikor1));

                                sendApiAction($chatid);

                                $text = "KENDALA\n";
                                $text .= "JA$sales_id\n";
                                $text .= "$kendala\n";
                                $text .= "Tikor Pelanggan : $lokasicust\n";
                                $text .= "Tikor ODP : $tikor_odp[1]\n";
                                $text .= "Oke sekarang tulis keterangan tentang kendala ini:";

                                sendApiMsgReply($chatid, $text);
                            } else {
                                sendApiAction($chatid);

                                $text = "KENDALA\n";
                                $text .= "JA$sales_id\n";
                                $text .= "$kendala\n";
                                $text .= "Tikor Pelanggan : $lokasicust\n";
                                $text .= "Lanjut share lokasi ODP:";

                                sendApiMsgReply($chatid, $text);
                            }
                        }
                    } else {
                        $alert = "Anda tidak mengirmkan lokasi.";
                        sendApiMsg($chatid, $alert);

                        $tikor_odp = str_replace(" ", "", explode(":", $tikor1));

                        $text = "KENDALA\n";
                        $text .= "JA$sales_id\n";
                        $text .= "$kendala\n";
                        $text .= (strpos($reply, 'Lanjut') !== false) ? "Tikor ODP : $tikor_odp[1]\n" : "";
                        $text .= (strpos($reply, 'Lanjut') !== false) ? "Lanjut share lokasi pelanggan:" : "Share lokasi pelanggan:";

                        sendApiMsgReply($chatid, $text);
                    }
                } elseif (strpos($reply, 'lokasi ODP') !== false) {
                    if (isset($message['location']['latitude'])) {
                        $latitude   = $message["location"]["latitude"];
                        $longitude  = $message["location"]["longitude"];
                        $lokasiodp  = $latitude . ',' . $longitude;

                        $alert = "Oke, lokasi ODP diterima.";
                        sendApiMsg($chatid, $alert);

                        if (strpos($reply, 'Lanjut') !== false) {
                            $tikor_pelanggan = str_replace(" ", "", explode(":", $tikor1));

                            sendApiAction($chatid);

                            $text = "KENDALA\n";
                            $text .= "JA$sales_id\n";
                            $text .= "$kendala\n";
                            $text .= "Tikor Pelanggan : $tikor_pelanggan[1]\n";
                            $text .= "Tikor ODP : $lokasiodp\n";
                            $text .= "Oke sekarang tulis keterangan tentang kendala ini:";

                            sendApiMsgReply($chatid, $text);
                        } else {
                            sendApiAction($chatid);

                            $text = "KENDALA\n";
                            $text .= "JA$sales_id\n";
                            $text .= "$kendala\n";
                            $text .= "Tikor ODP : $lokasiodp\n";
                            $text .= "Lanjut share lokasi pelanggan:";

                            sendApiMsgReply($chatid, $text);
                        }
                    } else {
                        $alert = "Anda tidak mengirmkan lokasi.";
                        sendApiMsg($chatid, $alert);

                        $tikor_pelanggan = str_replace(" ", "", explode(":", $tikor1));

                        $text = "KENDALA\n";
                        $text .= "JA$sales_id\n";
                        $text .= "$kendala\n";
                        $text .= (strpos($reply, 'Lanjut') !== false) ? "Tikor ODP : $tikor_pelanggan[1]\n" : "";
                        $text .= (strpos($reply, 'Lanjut') !== false) ? "Lanjut share lokasi ODP:" : "Share lokasi ODP:";

                        sendApiMsgReply($chatid, $text);
                    }
                }

                break;

            case $str == 'ORDER':
                //$pesan      = str_replace(' ', '', $message['text']);
                if ($pesan == '/lapor') {

                    /*
                    sendApiAction($chatid);
                    $text = 'Pelaporan kendala dilakukan via jarvis web. Silahkan laporkan kendala order kepada TL anda.';
                    sendApiMsg($chatid, $text);
                    */

                    $reply_message  = $message["reply_to_message"]["text"];
                    $reply_message  = preg_replace('/^.+\n/', '', $reply_message);
                    $sales_id       = str_replace('JA', '', strtok($reply_message, "\n"));

                    /*
                    $inkeyboard = [
                        [
                            ['text' => 'A. RNA', 'callback_data' => "RNA-$sales_id"],
                        ],
                        [
                            ['text' => 'B. ALAMAT', 'callback_data' => "ALAMAT-$sales_id"],
                        ],
                        [
                            ['text' => 'C. BATAL', 'callback_data' => "BATAL-$sales_id"],
                        ],
                        [
                            ['text' => 'D. PENDING', 'callback_data' => "PENDING-$sales_id"],
                        ],
                        [
                            ['text' => 'E. ODP FULL', 'callback_data' => "ODP FULL-$sales_id"],
                        ],
                        [
                            ['text' => 'F. ODP LOSS', 'callback_data' => "ODP LOSS-$sales_id"],
                        ],
                        [
                            ['text' => 'G. ODP RETI', 'callback_data' => "ODP RETI-$sales_id"],
                        ],
                        [
                            ['text' => 'H. TIANG', 'callback_data' => "TIANG-$sales_id"],
                        ],
                        [
                            ['text' => 'I. PT2', 'callback_data' => "PT2-$sales_id"],
                        ],
                        [
                            ['text' => 'J. NO FO/ODP', 'callback_data' => "NO FO/ODP-$sales_id"],
                        ],
                        [
                            ['text' => 'K. RUTE INSTALASI', 'callback_data' => "RUTE INSTALASI-$sales_id"],
                        ],
                        [
                            ['text' => 'L. ODP BLM LIVE', 'callback_data' => "ODP BLM LIVE-$sales_id"],
                        ],
                        [
                            ['text' => 'M. IJIN TANAM TIANG', 'callback_data' => "IJIN TANAM TIANG-$sales_id"],
                        ],
                        [
                            ['text' => 'N. ONU > 32', 'callback_data' => "ONU > 32-$sales_id"],
                        ],
                        [
                            ['text' => 'O. PENDING INSTALASI', 'callback_data' => "PENDING INSTALASI-$sales_id"],
                        ],
                        [
                            ['text' => 'P. NJKI', 'callback_data' => "NJKI-$sales_id"],
                        ]
                    ];
                    */

                    $inkeyboard = [
                        [
                            ['text' => 'KENDALA PELANGGAN', 'callback_data' => "KENDALA PELANGGAN-$sales_id"],
                        ],
                        [
                            ['text' => 'KENDALA INSTALASI', 'callback_data' => "KENDALA INSTALASI-$sales_id"],
                        ],
                        [
                            ['text' => 'MAINTENANCE', 'callback_data' => "MAINTENANCE-$sales_id"],
                        ],
                        [
                            ['text' => 'KENDALA TEKNIS', 'callback_data' => "KENDALA TEKNIS-$sales_id"],
                        ]
                    ];

                    //$cekid      = mysqli_query($koneksi,"SELECT sales_id,kendala FROM tb_sales WHERE sales_id = '$sales_id'");
                    $cekorder   = mysqli_query($koneksi, "SELECT sales_id,kendala FROM tb_sales WHERE sales_id = '$sales_id' AND status_id = 6");

                    if (mysqli_num_rows($cekorder) > 0) {
                        while ($d = mysqli_fetch_array($cekorder)) {
                            $text = '❗️ Order ID JA' . $sales_id . ' sudah dilaporkan sebagai kendala ' . $d['kendala'] . '';
                            sendApiMsg($chatid, $text, false, 'Markdown');
                        }
                    } else {
                        $cekja   = mysqli_query($koneksi, "SELECT sales_id FROM tb_sales WHERE sales_id = '$sales_id'");

                        if (mysqli_num_rows($cekja) > 0) {
                            sendApiKeyboard($chatid, 'Silahkan pilih jenis kendala nya :', $inkeyboard, true);
                        } else {
                            $text = "❌ JA$sales_id belum diorder oleh TL!";
                            sendApiMsg($chatid, $text, false, 'Markdown');
                        }
                    }
                } elseif (strtok($pesan, "\n") == '/reqsc' || strtok($pesan, "\n") == '/reqsc ') {
                    sendApiAction($chatid);
                    $reply_message  = $message["reply_to_message"]["text"];
                    $reply_message  = preg_replace('/^.+\n/', '', $reply_message);
                    $sales_id       = str_replace('JA', '', strtok($reply_message, "\n"));

                    $cekid    = mysqli_query($koneksi, "SELECT status_id,sales_id FROM tb_sales WHERE sales_id = '$sales_id' AND status_id > 2 AND status_id != 1");
                    $ceksales = mysqli_query($koneksi, "SELECT message_from,sales_id,message_id FROM tb_sales WHERE sales_id = '$sales_id'");
                    //$cekorder = mysqli_query($koneksi,"SELECT sales_id FROM tb_sales WHERE sales_id = '$sales_id' AND status_id = 6");
                    $datenow  = date('Y-m-d H:i:s');

                    if (mysqli_num_rows($cekid) > 0) {
                        while ($d = mysqli_fetch_array($cekid)) {
                            if ($d['status_id'] == 6) {
                                $text = '❌ Order JA' . $sales_id . ' tidak dapat dilakukan request SC, karena statusnya sekarang adalah ' . statusSales($d['status_id']) . '. Silahkan hubungi TL untuk memfollow up KENDALA order ini.';
                            } else {
                                $text = '❌ Order JA' . $sales_id . ' tidak dapat dilakukan request SC, karena statusnya sekarang adalah ' . statusSales($d['status_id']) . '';
                            }
                        }
                    } else {
                        $cekProg    = mysqli_query($koneksi, "SELECT status_id,sales_id FROM tb_sales WHERE sales_id = '$sales_id' AND (status = 'otw' OR status = 'ogp')");
                        if (mysqli_num_rows($cekProg) > 0) {
                            $pesan = strtoupper($message['text']);
                            $arrpsn = explode("\n", $pesan);
                            $datenow = date('Y-m-d H:i:s');

                            if (sizeof($arrpsn) != 4) {
                                $text = 'ERROR request SC! Request SC hanya dengan /reqsc [enter] ODP [enter] PORT no_port [enter] DC';
                            } else {
                                $odp    = str_replace(' ', '', $arrpsn[1]);
                                $port   = str_replace(' ', '', $arrpsn[2]);
                                $dc     = str_replace(' ', '', $arrpsn[3]);
                                if (strlen($odp) < 14 || strlen($odp) > 16) {
                                    $text = 'Penulisan ODP tidak valid. Seharusnya ODP-PKL-FAA/001';
                                } elseif (stripos($odp, '/') == false) {
                                    $text = 'Kurang tanda / nya. Seharusnya ODP-PKL-FAA/001';
                                } elseif (stripos($port, ':') == true) {
                                    $text = 'Perbaiki penulisan port! PORT tanpa titi dua. Seharusnya PORT 1';
                                } elseif (stripos($port, '=') == true) {
                                    $text = 'Perbaiki penulisan port! PORT tanpa sama dengan. Seharusnya PORT 1';
                                } elseif (strlen($port) < 5) {
                                    $text = 'Perbaiki penulisan port! Seharusnya PORT 1';
                                } elseif (stripos($dc, ':') == true) {
                                    $text = 'Perbaiki penulisan dropcore! Seharusnya DC 150 (satuan meter)';
                                } elseif (strlen($dc) < 4) {
                                    $text = 'Perbaiki penulisan dropcore! Seharusnya DC 150 (satuan meter)';
                                } else {
                                    $port  = str_replace("PORT", "", $port);
                                    $dc    = str_replace("DC", "", $dc);
                                    $query = mysqli_query($koneksi, "UPDATE tb_sales SET req_sc_odp='$odp', req_sc_port='$port', req_sc_dc='$dc', status_id = 3, status = 'waitsc', req_sc_by = '$fromid', tgl_req_sc = '$datenow', tgl_update = '$datenow' WHERE sales_id = '$sales_id'");
                                    if ($query) {
                                        $ambilt = mysqli_query($koneksi, "SELECT nama_teknisi FROM tb_teknisi WHERE t_telegram_id = '$fromid'");
                                        while ($d = mysqli_fetch_array($ceksales)) {
                                            $sales_id = $d['sales_id'];
                                            $s_telegram_id = $d['message_from'];
                                            $s_m_id = $d['message_id'];
                                            while ($t = mysqli_fetch_array($ambilt)) {
                                                $savelog = mysqli_query($koneksi, "INSERT INTO tb_log (sales_id, action_by, action_on, action_status) VALUES ('$sales_id','$t[nama_teknisi]','$datenow',3)");
                                                $pesantosales = "⚠️ Order dimintakan SC oleh $t[nama_teknisi]";
                                                sendApiMsg($s_telegram_id, $pesantosales, $s_m_id, 'Markdown');
                                            }
                                        }
                                        $text = '✅ Request SC berhasil dilakukan, silahkan tunggu. Agar lebih efektif pastikan permintaan SC dilakukan saat jaringan dinyatakan feasible, sebelum proses tarik. Tks';
                                    } else {
                                        $text = "❗️ Order JA$sales_id tidak ditemukan.";
                                    }
                                }
                            }
                        } else {
                            $text = "❗️ Order JA$sales_id belum dilakukan update ke OTW & OGP. Lakukan update ke OTW & OGP dahulu. Kemudian silahkan reqsc ulang.";
                        }
                    }

                    $meid     = $message["message_id"];
                    sendApiMsg($chatid, $text, $meid);
                } elseif (strtok($pesan, "\n") == '/create') {
                    sendApiAction($chatid);
                    $reply_message  = $message["reply_to_message"]["text"];
                    $reply_message  = preg_replace('/^.+\n/', '', $reply_message);
                    preg_match_all('/(.*):(.*)/', $reply_message, $output);
                    $ja     = str_replace('JA', '', strtok($reply_message, "\n"));
                    $plgn   = trim(strtoupper($output["2"]["0"]));
                    $cp     = trim(strtoupper($output["2"]["1"]));
                    $odp    = trim(strtoupper($output["2"]["2"]));
                    $alamat = trim(strtoupper($output["2"]["3"]));

                    $text = "!create data\n";
                    $text .= "ORDER : \n";
                    $text .= "JA : $ja\n";
                    $text .= "A/N : $plgn\n";
                    $text .= "ALAMAT : $alamat\n";
                    $text .= "TLP : \n";
                    $text .= "INET : \n";
                    $text .= "ODP : $odp\n";
                    $text .= "ODP REAL :\n";
                    $text .= "PORT : \n";
                    $text .= "SISA : \n";
                    $text .= "SN : \n";
                    $text .= "SC : \n";
                    $text .= "DC : \n";
                    $text .= "MITRA : \n";
                    $text .= "TEKNISI : \n";
                    $text .= "CREW : \n";
                    $text .= "BC : \n";
                    $text .= "SCLAM : \n";
                    $text .= "BREKET : \n";
                    $text .= "ROS : \n";
                    $text .= "T7 : \n";
                    $text .= "CP : \n";
                    $text .= "STB ID : \n";
                    $text .= "SPL 1:2 : \n";
                    $text .= "SPL 1:4 : \n";
                    $text .= "SPL 1:8 : \n";
                    $text .= "CASSETE : \n";
                    $text .= "ADAPTER : \n";
                    $text .= "UTP : \n";
                    $text .= "RJ45 : \n";
                    sendApiMsg($chatid, $text, false, 'Markdown');
                } elseif ($pesan == '/status') {
                    sendApiAction($chatid);
                    $reply_message  = $message["reply_to_message"]["text"];
                    $reply_message  = preg_replace('/^.+\n/', '', $reply_message);
                    $sales_id       = str_replace('JA', '', strtok($reply_message, "\n"));
                    $cekid    = mysqli_query($koneksi, "SELECT * FROM tb_sales LEFT JOIN tb_salesman ON tb_salesman.s_telegram_id=tb_sales.message_from WHERE sales_id = '$sales_id'");
                    if (mysqli_num_rows($cekid) > 0) {
                        while ($d = mysqli_fetch_array($cekid)) {
                            if ($d['fullname'] == null) {
                                $salesman = 'ANONIM';
                            } else {
                                $salesman = $d['fullname'];
                            }
                            $text = "[" . statusSales($d['status_id']) . "]\n";
                            $text  .= "JA$d[sales_id]\n";
                            $text  .= "NAMA PELANGGAN : $d[nama_pelanggan]\n";
                            $text  .= "CP : $d[cp]\n";
                            $text  .= "SC : $d[new_sc]\n";
                            $text  .= "ODP : $d[odp]\n";
                            $text  .= "ALAMAT : $d[alamat]\n";
                            $text  .= "MYIR : $d[myir]\n";
                            $text  .= "PAKET : $d[paket]\n";
                            $text  .= "SALES : $salesman\n";
                            $text  .= "KETERANGAN : \n";
                            $text  .= $d['keterangan'] == '' ? '-' : $d['keterangan'];
                        }
                    } else {
                        $text = "❗️ Maaf sedang tidak dapat mengambil data. Silahkan coba beberapa saat lagi..";
                    }
                    $meid     = $message["message_id"];
                    sendApiMsg($chatid, $text, $meid);
                } elseif ($pesan == '/cekonuid') {
                    sendApiAction($chatid);
                    $reply_message  = $message["reply_to_message"]["text"];
                    $reply_message  = preg_replace('/^.+\n/', '', $reply_message);
                    $sales_id       = str_replace('JA', '', strtok($reply_message, "\n"));
                    $datenow  = date('Y-m-d H:i:s');
                    $cekid    = mysqli_query($koneksi, "SELECT * FROM tb_sales WHERE sales_id = '$sales_id'");
                    if (mysqli_num_rows($cekid) > 0) {
                        $cekreq    = mysqli_query($koneksi, "SELECT * FROM tb_cekonu WHERE sales_id = '$sales_id' AND status = 'REQUEST'");
                        if (mysqli_num_rows($cekreq) <= 0) {
                            while ($d = mysqli_fetch_array($cekid)) {
                                $nama_pelanggan = $d['nama_pelanggan'];
                                $cp             = $d['cp'];
                                $odp            = $d['odp'];
                            }
                            $telegramname = str_replace("'", "", $telegramname);
                            $query = mysqli_query($koneksi, "INSERT INTO tb_cekonu (sales_id,nama_pelanggan,cp,odp,created_at,req_by,group_id,message_id) VALUES ('$sales_id','$nama_pelanggan','$cp','$odp','$datenow','$telegramname','$chatid','$meid')");
                            if ($query) {
                                $text = "✅ Request cek onu untuk JA$sales_id berhasil dilakukan. Mohon menunggu pengecekan HD.";
                            } else {
                                $text = "Error. SIlahkan coba beberapa saat lagi. \n";
                                $text .= "Error : " . mysqli_error($koneksi) . " ";
                            }
                        } else {
                            $text = "JA$sales_id sedang dalam pengecekan HD. Mohon ditunggu.";
                        }
                    } else {
                        $text = "JA$sales_id tidak ditemukan.";
                    }
                    $meid     = $message["message_id"];
                    sendApiMsg($chatid, $text, $meid);
                } elseif ($pesan == '/otw') {
                    sendApiAction($chatid);
                    $reply_message  = $message["reply_to_message"]["text"];
                    $reply_message  = preg_replace('/^.+\n/', '', $reply_message);
                    $sales_id       = str_replace('JA', '', strtok($reply_message, "\n"));
                    $datenow  = date('Y-m-d H:i:s');
                    $cekid    = mysqli_query($koneksi, "SELECT * FROM tb_sales WHERE sales_id = '$sales_id'");
                    while ($d = mysqli_fetch_array($cekid)) {
                        $status_id = $d['status_id'];
                        $progress_to = $d['progress_to'];
                    }
                    if (mysqli_num_rows($cekid) > 0) {
                        $cekTek    = mysqli_query($koneksi, "SELECT * FROM tb_teknisi WHERE t_telegram_id = '$progress_to'");
                        while ($e = mysqli_fetch_array($cekTek)) {
                            $crew = $e['crew'];
                        }
                        $cekCrew   = mysqli_query($koneksi, "SELECT * FROM tb_teknisi WHERE crew = '$crew'");
                        $crewNya = "";
                        foreach ($cekCrew as $row) {
                            $crewNya .= $row['t_telegram_id'] . ",";
                        }
                        $permitTek = explode(",", $crewNya);
                        if ($fromid == $progress_to || $fromid == $permitTek[0] || $fromid == $permitTek[1]) {
                            $cekreq    = mysqli_query($koneksi, "SELECT * FROM tb_sales WHERE sales_id = '$sales_id' AND `status` = 'ordered'");
                            if (mysqli_num_rows($cekreq) > 0) {
                                $query = mysqli_query($koneksi, "UPDATE tb_sales SET status = 'otw', tgl_update = '$datenow' WHERE sales_id=$sales_id");
                                if ($query) {
                                    $text = "✅ JA$sales_id berhasil diupdate ke OTW ke lokasi";
                                } else {
                                    $text = 'Error. Silahkan coba beberapa saat lagi.';
                                }
                            } else {
                                $text = "JA$sales_id tidak dalam status untuk di OTW. Statusnya : " . statusSales($status_id) . "";
                            }
                        } else {
                            $text = "❌ JA$sales_id bukan order anda. Silahkan koordinasi dengan TL";
                        }
                    } else {
                        $text = "JA$sales_id tidak ditemukan.";
                    }
                    $meid     = $message["message_id"];
                    sendApiMsg($chatid, $text, $meid);
                } elseif ($pesan == '/ogp') {
                    sendApiAction($chatid);
                    $reply_message  = $message["reply_to_message"]["text"];
                    $reply_message  = preg_replace('/^.+\n/', '', $reply_message);
                    $sales_id       = str_replace('JA', '', strtok($reply_message, "\n"));
                    $datenow  = date('Y-m-d H:i:s');
                    $cekid    = mysqli_query($koneksi, "SELECT * FROM tb_sales WHERE sales_id = '$sales_id'");
                    while ($d = mysqli_fetch_array($cekid)) {
                        $status_id = $d['status_id'];
                        $progress_to = $d['progress_to'];
                    }
                    if (mysqli_num_rows($cekid) > 0) {
                        $cekTek    = mysqli_query($koneksi, "SELECT * FROM tb_teknisi WHERE t_telegram_id = '$progress_to'");
                        while ($e = mysqli_fetch_array($cekTek)) {
                            $crew = $e['crew'];
                        }
                        $cekCrew   = mysqli_query($koneksi, "SELECT * FROM tb_teknisi WHERE crew = '$crew'");
                        $crewNya = "";
                        foreach ($cekCrew as $row) {
                            $crewNya .= $row['t_telegram_id'] . ",";
                        }
                        $permitTek = explode(",", $crewNya);
                        if ($fromid == $progress_to || $fromid == $permitTek[0] || $fromid == $permitTek[1]) {
                            $cekreq    = mysqli_query($koneksi, "SELECT * FROM tb_sales WHERE sales_id = '$sales_id' AND (status = 'scbe' OR status = 'ordered' OR status = 'otw')");
                            if (mysqli_num_rows($cekreq) > 0) {
                                $query = mysqli_query($koneksi, "UPDATE tb_sales SET status = 'ogp', tgl_update = '$datenow' WHERE sales_id=$sales_id");
                                if ($query) {
                                    $text = "✅ JA$sales_id berhasil diupdate ke OGP pengerjaan pemasangan";
                                } else {
                                    $text = 'Error. Silahkan coba beberapa saat lagi.';
                                }
                            } else {
                                $text = "JA$sales_id tidak dalam status untuk di OGP. Statusnya : " . statusSales($status_id) . "";
                            }
                        } else {
                            $text = "❌ JA$sales_id bukan order anda. Silahkan koordinasi dengan TL";
                        }
                    } else {
                        $text = "JA$sales_id tidak ditemukan.";
                    }
                    $meid     = $message["message_id"];
                    sendApiMsg($chatid, $text, $meid);
                }

                break;

            default:

                //source default
                break;
        }
    }

    if ($masuk_switch_m) {
        if ($type_ms == "private") {
            switch (true) {
                case $pesan == '/id':
                    sendApiAction($chatid);
                    $text = 'ID Kamu adalah: ' . $fromid;
                    sendApiMsg($chatid, $text);
                    break;

                case $pesan == '/coba':
                    $inkeyboard = [
                        [
                            ['text' => 'SATU', 'callback_data' => "1"],
                        ],
                        [
                            ['text' => 'DUA', 'callback_data' => "2"],
                        ],
                        [
                            ['text' => 'TIGA', 'callback_data' => "3"],
                        ]
                    ];

                    sendApiKeyboard($chatid, 'Silahkan pilih jenis kendala nya :', $inkeyboard, true);
                    break;

                case $pesan == '/registerhd':
                    sendApiAction($chatid);
                    $query = mysqli_query($koneksi, "SELECT h_telegram_id FROM tb_helpdesk WHERE h_telegram_id = '$fromid'");
                    if (mysqli_num_rows($query) > 0) {
                        $text = '❗️ Kamu sudah terdaftar sebagai Help Desk pada aplikasi ini! ';
                        sendApiMsg($chatid, $text);
                    } else {
                        $text = 'Halo 👋🏻, perkenalkan saya adalah jarvis, robot yang akan membantu pekerjaan teman-teman.';
                        sendApiMsg($chatid, $text);
                        $text = 'Saat ini kamu mencoba untuk mendaftar sebagai Help Desk';
                        sendApiMsg($chatid, $text);
                        $text = 'Silahkan tulis siapa nama kamu?';
                        sendApiMsgReply($chatid, $text);
                    }

                    break;

                case $pesan == '/regteknisi':
                    sendApiAction($chatid);
                    $query = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid' AND nik IS NOT NULL AND nama_teknisi IS NOT NULL AND crew IS NOT NULL AND mitra IS NOT NULL");
                    $ceknonik   = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid' AND nik IS NULL");
                    $ceknoname  = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid' AND nama_teknisi IS NULL");
                    $ceknocrew  = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid' AND crew IS NULL");
                    $ceknomitra = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid' AND mitra IS NULL");
                    if (mysqli_num_rows($query) > 0) {
                        $text = '❗️ Kamu sudah terdaftar sebagai Teknisi pada aplikasi ini! ';
                        sendApiMsg($chatid, $text);
                    } else {
                        if (mysqli_num_rows($ceknonik) > 0) {
                            $text = 'Masukan NIK dengan 8 digit angka. Jika kamu belum memiliki nik, gunakan tanggal lahir kamu. misal : 20 Juli 1998, maka masukan nik dengan 20071998';
                            sendApiMsg($chatid, $text);
                            $text = 'Silahkan masukan nik kamu :';
                            sendApiMsgReply($chatid, $text);
                        } elseif (mysqli_num_rows($ceknoname) > 0) {
                            $text = '👤 Masukan nama kamu :';
                            sendApiMsgReply($chatid, $text);
                        } elseif (mysqli_num_rows($ceknocrew) > 0) {
                            $text = "Masukan KODE DATEL dengan, Contoh : PKL, SLW, BTG, TEG, BRB, PML";
                            sendApiMsg($chatid, $text);
                            $text = '👥 Masukan KODE DATEL :';
                            sendApiMsgReply($chatid, $text);
                        } elseif (mysqli_num_rows($ceknomitra) > 0) {
                            $text = 'Masukan Mitra :';
                            sendApiMsgReply($chatid, $text);
                            $text = "Isi Mitra dengan nama perusahaan. misal : HCP, TA, GLOBAL, KOPEGTEL, ZAG, KJS";
                            sendApiMsg($chatid, $text);
                        } else {
                            $text = 'Halo 👋🏻, perkenalkan saya adalah jarvis, robot yang akan membantu pekerjaan teman-teman.';
                            sendApiMsg($chatid, $text);
                            $text = 'Saat ini kamu mencoba untuk mendaftar sebagai Teknisi';
                            sendApiMsg($chatid, $text);
                            $text = 'Masukan NIK dengan 8 digit angka. Jika kamu belum memiliki nik, gunakan tanggal lahir kamu. misal : 20 Juli 1998, maka masukan nik dengan 20071998';
                            sendApiMsg($chatid, $text);
                            $text = 'Silahkan masukan nik kamu :';
                            sendApiMsgReply($chatid, $text);
                        }
                    }

                    break;

                case $pesan == '/regsales':
                    sendApiAction($chatid);
                    $query = mysqli_query($koneksi, "SELECT s_telegram_id FROM tb_salesman WHERE s_telegram_id = '$fromid' AND fullname IS NOT NULL AND no_hp IS NOT NULL AND no_mobi IS NOT NULL AND mitra IS NOT NULL");
                    $ceknoname  = mysqli_query($koneksi, "SELECT s_telegram_id FROM tb_salesman WHERE s_telegram_id = '$fromid' AND fullname IS NULL");
                    $ceknohp   = mysqli_query($koneksi, "SELECT s_telegram_id FROM tb_salesman WHERE s_telegram_id = '$fromid' AND no_hp IS NULL");
                    $ceknomobi  = mysqli_query($koneksi, "SELECT s_telegram_id FROM tb_salesman WHERE s_telegram_id = '$fromid' AND no_mobi IS NULL");
                    $ceknomitra = mysqli_query($koneksi, "SELECT s_telegram_id FROM tb_salesman WHERE s_telegram_id = '$fromid' AND mitra IS NULL");
                    if (mysqli_num_rows($query) > 0) {
                        $text = '❗️ Kamu sudah terdaftar sebagai sales pada aplikasi ini! ';
                        sendApiMsg($chatid, $text);
                    } else {
                        if (mysqli_num_rows($ceknoname) > 0) {
                            $text = 'Silahkan masukan nama kamu';
                            sendApiMsgReply($chatid, $text);
                        } elseif (mysqli_num_rows($ceknohp) > 0) {
                            $text = '📱 Masukan No HP';
                            sendApiMsgReply($chatid, $text);
                        } elseif (mysqli_num_rows($ceknomobi) > 0) {
                            $text = 'Masukan No Mobi';
                            sendApiMsgReply($chatid, $text);
                        } elseif (mysqli_num_rows($ceknomitra) > 0) {
                            $text = 'Masukan Nama Mitra';
                            sendApiMsgReply($chatid, $text);
                            $text = "Isi Mitra dengan nama perusahaan. misal : KOPEGTEL, INFOMEDIA";
                            sendApiMsg($chatid, $text);
                        } else {
                            $text = 'Halo 👋🏻, perkenalkan saya adalah jarvis, robot yang akan membantu pekerjaan teman-teman.';
                            sendApiMsg($chatid, $text);
                            $text = 'Saat ini kamu mencoba untuk mendaftar sebagai Sales';
                            sendApiMsg($chatid, $text);
                            $text = 'Silahkan masukan nama kamu';
                            sendApiMsgReply($chatid, $text);
                        }
                    }

                    break;

                case $pesan == '/help':
                    sendApiAction($chatid);
                    $text = "Halo. Kenalin saya Jarvis, asisten WITEL PKL. Saya ditugaskan untuk membantu pekerjaan teman-teman semua.\n\n";
                    $text .= "📖 Berikut yang bisa saya lakukan untuk teman-teman semua :\n\n";
                    $text .= "/registerhd untuk registrasi sebagai Help Desk.\n";
                    $text .= "/regteknisi untuk registrasi sebagai Teknisi.\n";
                    $text .= "/regsales untuk registrasi sebagai Sales.\n\n";
                    $text .= "== Teknisi ==\n";
                    $text .= "/otw untuk mengonfirmasi order bahwa teknisi sudah otw. (Mereply Pesan Order)\n";
                    $text .= "/ogp untuk mengonfirmasi order bahwa teknisi sedang dalam pengerjaan. (Mereply Pesan Order)\n";
                    $text .= "/cekonuid untuk request cek onu ke tim Helpdesk. (Mereply Pesan Order)\n";
                    $text .= "/status untuk melihat status order. (Mereply Pesan Order)\n";
                    $text .= "/reqsc [enter] ODP [enter ] PORT [enter] DC untuk request SC ke HD. (Mereply Pesan Order)\n";
                    $text .= "/lapor untuk lapor order yang terkendala. (Mereply Pesan Order)\n";
                    $text .= "/formatcreate untuk melihat format permintaan create aktivasi.\n\n";
                    $text .= "== Sales ==\n";
                    $text .= "/formatinput untuk melihat format input Sales.\n";
                    $text .= "!InputSC untuk menginput order pelanggan sales.\n\n";
                    $text .= "== Non Fungsional ==\n";
                    $text .= "/help untuk melihat informasi bantuan.\n";
                    $text .= "/id untuk melihat informasi ID User Telegram.\n\n";
                    $text .= "😎 Terima Kasih";
                    sendApiMsg($chatid, $text);
                    break;

                case $pesan == '/formatcreate':
                    sendApiAction($chatid);
                    $text = "!create data\n";
                    $text .= "ID VALINS : \*\n";
                    $text .= "ORDER : \*\n";
                    $text .= "SC : \*\n";
                    $text .= "A/N : \*\n";
                    $text .= "ALAMAT : \*\n";
                    $text .= "CP : \n";
                    $text .= "NO.TELP : \n";
                    $text .= "NO.INET : \n";
                    $text .= "ODP : \*\n";
                    $text .= "PORT : \n";
                    $text .= "SISA : \n";
                    $text .= "SN ONT : \*\n";
                    $text .= "STB ID : \n";
                    $text .= "IP CAM : \n";
                    $text .= "WIFI EXT : \n";
                    $text .= "ORBIT : \n";
                    $text .= "DC : \n";
                    $text .= "SOC : \n";
                    $text .= "PRECON : \n";
                    $text .= "MITRA : \n";
                    $text .= "TEKNISI : \n";
                    $text .= "CREW : \n";
                    $text .= "BC : \*\n";
                    $text .= "SCLAM : \n";
                    $text .= "BREKET : \n";
                    $text .= "T7 : \n";
                    $text .= "ASS TIANG : \n";
                    $text .= "UTP : \n";
                    $text .= "\n\n\* Wajib diisi";
                    sendApiMsg($chatid, $text, false, 'Markdown');
                    break;

                case $pesan == '/formatinput':
                    sendApiAction($chatid);
                    $text = "!inputSC data\n";
                    $text .= "1. Nama Pelanggan\* :\n";
                    $text .= "2. No KTP :\n";
                    $text .= "3. Alamat : \n";
                    $text .= "4. Cp\* :\n";
                    $text .= "5. Email :\n";
                    $text .= "6. Paket :\n";
                    $text .= "7. MYIR\* :\n";
                    $text .= "8. Kode Sales\* :\n";
                    $text .= "9. ODP\* :\n";
                    $text .= "10. Koordinat\* :\n";
                    $text .= "11. Jarak/Tanam Tiang :\n";
                    $text .= "12. Segment/Unit\* :\n";
                    sendApiMsg($chatid, $text, false, 'Markdown');
                    break;

                case preg_match("/\/laporold (.*)/", $pesan, $hasil):
                    sendApiAction($chatid);

                    $sales_id = intval(preg_replace('/[^0-9]+/', '', $hasil[1]), 10);

                    $inkeyboard = [
                        [
                            ['text' => 'A. RNA', 'callback_data' => "RNA-$sales_id"],
                        ],
                        [
                            ['text' => 'B. ALAMAT', 'callback_data' => "ALAMAT-$sales_id"],
                        ],
                        [
                            ['text' => 'C. BATAL', 'callback_data' => "BATAL-$sales_id"],
                        ],
                        [
                            ['text' => 'D. PENDING', 'callback_data' => "PENDING-$sales_id"],
                        ],
                        [
                            ['text' => 'E. ODP FULL', 'callback_data' => "ODP FULL-$sales_id"],
                        ],
                        [
                            ['text' => 'F. ODP LOSS', 'callback_data' => "ODP LOSS-$sales_id"],
                        ],
                        [
                            ['text' => 'G. ODP RETI', 'callback_data' => "ODP RETI-$sales_id"],
                        ],
                        [
                            ['text' => 'H. TIANG', 'callback_data' => "TIANG-$sales_id"],
                        ],
                        [
                            ['text' => 'I. PT2', 'callback_data' => "PT2-$sales_id"],
                        ],
                        [
                            ['text' => 'J. NO FO/ODP', 'callback_data' => "NO FO/ODP-$sales_id"],
                        ],
                        [
                            ['text' => 'K. RUTE INSTALASI', 'callback_data' => "RUTE INSTALASI-$sales_id"],
                        ],
                        [
                            ['text' => 'L. ODP BLM LIVE', 'callback_data' => "ODP BLM LIVE-$sales_id"],
                        ],
                        [
                            ['text' => 'M. IJIN TANAM TIANG', 'callback_data' => "IJIN TANAM TIANG-$sales_id"],
                        ],
                        [
                            ['text' => 'N. ONU > 32', 'callback_data' => "ONU > 32-$sales_id"],
                        ],
                        [
                            ['text' => 'O. PENDING INSTALASI', 'callback_data' => "PENDING INSTALASI-$sales_id"],
                        ],
                        [
                            ['text' => 'P. NJKI', 'callback_data' => "NJKI-$sales_id"],
                        ]
                    ];

                    $cekorder   = mysqli_query($koneksi, "SELECT sales_id FROM tb_kendala WHERE sales_id = '$sales_id'");
                    $datenow    = date('Y-m-d H:i:s');
                    $meid       = $message["message_id"];
                    if (mysqli_num_rows($cekorder) > 0) {
                        while ($d = mysqli_fetch_array($cekorder)) {
                            $text = '❗️ Order ID JA' . $sales_id . ' sudah dilaporkan sebagai kendala ' . $d['kendala'] . '';
                            sendApiMsg($chatid, $text, false, 'Markdown');
                        }
                    } else {
                        $cekja   = mysqli_query($koneksi, "SELECT sales_id FROM tb_sales WHERE sales_id = '$sales_id'");

                        if (mysqli_num_rows($cekja) > 0) {
                            sendApiKeyboard($chatid, 'Silahkan pilih jenis kendala nya :', $inkeyboard, true);
                        } else {
                            $text = "❌ JA$sales_id belum diorder oleh TL!";
                            sendApiMsg($chatid, $text, false, 'Markdown');
                        }
                    }

                    break;

                case preg_match("/\!inputSC (.*)/", $pesan, $hasil):
                case preg_match("/\!InputSC (.*)/", $pesan, $hasil):
                    sendApiAction($chatid);

                    $cekus = mysqli_query($koneksi, "SELECT s_telegram_id FROM tb_salesman WHERE s_telegram_id = '$fromid'");
                    $cekhs = mysqli_query($koneksi, "SELECT s_telegram_id,active FROM tb_salesman WHERE s_telegram_id = '$fromid' AND active = 1");
                    if (mysqli_num_rows($cekus) > 0) {
                        if (mysqli_num_rows($cekhs) > 0) {
                            /*bersih bersih pesan*/
                            $pesan = strtoupper($message['text']);
                            $arrpsn = explode("\n", $pesan);

                            if (stripos($arrpsn[1], 'PELANGGAN') == false or stripos($arrpsn[1], ':') == false) {
                                $text = '⚠️ Isi baris 1 dengan Nama Pelanggan :';
                            } else if (stripos($arrpsn[2], 'KTP') == false or stripos($arrpsn[2], ':') == false) {
                                $text = '⚠️ Isi baris 2 dengan No KTP:';
                            } else if (stripos($arrpsn[3], 'ALAMAT') == false or stripos($arrpsn[3], ':') == false) {
                                $text = '⚠️ Isi baris 3 dengan Alamat:';
                            } else if (stripos($arrpsn[4], 'CP') == false or stripos($arrpsn[4], ':') == false) {
                                $text = '⚠️ Isi baris 4 dengan CP:';
                            } else if (stripos($arrpsn[5], 'EMAIL') == false or stripos($arrpsn[5], ':') == false) {
                                $text = '⚠️ Isi baris 5 dengan EMAIL:';
                            } else if (stripos($arrpsn[6], 'PAKET') == false or stripos($arrpsn[6], ':') == false) {
                                $text = '⚠️ Isi baris 6 dengan PAKET:';
                            } else if (stripos($arrpsn[7], 'MYIR') == false or stripos($arrpsn[7], ':') == false) {
                                $text = '⚠️ Isi baris 7 dengan MYIR:';
                            } else if (stripos($arrpsn[8], 'SALES') == false or stripos($arrpsn[8], ':') == false) {
                                $text = '⚠️ Isi baris 8 dengan KODE SALES:';
                            } else if (stripos($arrpsn[9], 'ODP') == false or stripos($arrpsn[9], ':') == false) {
                                $text = '⚠️ Isi baris 9 dengan ODP:';
                            } else if (stripos($arrpsn[10], 'KOORDINAT') == false or stripos($arrpsn[10], ':') == false) {
                                $text = '⚠️ Isi baris 10 dengan KOORDINAT:';
                            } else if (stripos($arrpsn[11], 'JARAK') == false or stripos($arrpsn[11], ':') == false) {
                                $text = '⚠️ Isi baris 11 dengan JARAK/TANAM TIANG:';
                            } else if (stripos($arrpsn[12], 'SEGMENT') == false or stripos($arrpsn[12], ':') == false) {
                                $text = '⚠️ Isi baris 12 dengan SEGMENT/UNIT:';
                            } else {
                                preg_match_all('/(.*):(.*)/', $pesan, $output);
                                $plgn           = trim(strtoupper($output["2"]["0"]));
                                $no_ktp         = trim(strtoupper($output["2"]["1"]));
                                $alamat         = trim(strtoupper($output["2"]["2"]));
                                $cp             = trim(str_replace(' ', '', strtoupper($output["2"]["3"])));
                                $email          = trim(strtolower($output["2"]["4"]));
                                $paket          = trim(strtoupper($output["2"]["5"]));
                                //$myir           = intval(preg_replace('/[^0-9]+/', '', trim($output["2"]["6"])), 10);
                                $myir           = intval(preg_replace('/[^0-9]+/', '', trim($output["2"]["6"])), 10);
                                $kode           = trim(strtoupper(str_replace(' ', '', $output["2"]["7"])));
                                $odp            = trim(strtoupper(str_replace(' ', '', $output["2"]["8"])));
                                $lat_long       = trim(strtoupper($output["2"]["9"]));
                                $jarak_tiang    = trim(strtoupper($output["2"]["10"]));
                                $segment        = trim(strtoupper($output["2"]["11"]));
                                $pecahloc       = explode(',', $lat_long);
                                $lat            = str_replace(" ", "", $pecahloc[0]);
                                $lng            = str_replace(" ", "", $pecahloc[1]);
                                $ceklat         = str_split($lat);
                                $tgl            = date('Y-m-d H:i:s');
                                $post_id        = $message["message_id"];
                                $dbrb           = array('BRB', 'BKA', 'BMU', 'KTM', 'TTL');
                                $dbtg           = array('BTG', 'BDY', 'SBA');
                                $dpkl1          = array('PKL');
                                $dpkl2          = array('KJE', 'KDW');
                                $dpml           = array('PML', 'CMA', 'RDD');
                                $dslw           = array('SLW', 'ADW', 'BLU');
                                $dteg           = array('MGN', 'TEG', 'TGL');

                                if ($plgn == '') {
                                    $text = 'Nama Pelanggan tidak boleh kosong! Lengkapi data dan kirim ulang.';
                                } elseif ($cp == '') {
                                    $text = 'CP Pelanggan tidak boleh kosong! Lengkapi data dan kirim ulang.';
                                } elseif ($segment == '') {
                                    $text = 'Segment / Unit tidak boleh kosong! Lengkapi data dan kirim ulang.';
                                } elseif (cekSegment($segment) == false) {
                                    $text = "Segment / Unit tidak diketahui! Silahkan pilih salah satu DCS, DBS, DES, DGS, BGES.\n";
                                    $text .= "Silahkan perbaiki dan kirim ulang.";
                                } elseif ($myir == 0 && $jarak_tiang != 'UNSC') {
                                    $text = "MYIR tidak boleh kosong!. Isikan dengan nomor SC jika inputan manual.";
                                } elseif (strlen($kode) < 7) {
                                    $text = 'KODE SALES tidak valid. Silahkan perbaiki dan kirim ulang.';
                                } elseif ($odp == '') {
                                    $text = 'ODP tidak boleh kosong! Lengkapi data dan kirim ulang.';
                                } elseif (strlen($odp) < 14 || strlen($odp) > 15) {
                                    $text = 'ODP Tidak valid! Lengkapi penulisan ODP. misal : ODP-PKL-FAA/001';
                                } elseif (stripos($odp, '/') == false) {
                                    $text = '❌ Kurang tanda / nya. Seharusnya : ODP-PKL-FAA/001 ';
                                } elseif ($lat_long == '') {
                                    $text = 'KOORDINAT tidak boleh kosong! Lengkapi data dan kirim ulang.';
                                } elseif (stripos($lat_long, 'https') !== false) {
                                    $text = 'Isi koordinat dengan latitude,longitude. misal : -6.89041,109.620956';
                                    $text .= 'Silahkan perbaiki dan kirim ulang.';
                                } elseif (stripos($lat_long, ',') == false) {
                                    $text = 'Penulisan koordinat tidak sesuai. contoh yang sesuai : -6.89041,109.620956';
                                    $text .= 'Silahkan perbaiki dan kirim ulang.';
                                } elseif ($ceklat[2] != '.') {
                                    $text = 'Penulisan koordinat tidak sesuai. contoh yang sesuai : -6.89041,109.620956';
                                    $text .= 'Silahkan perbaiki dan kirim ulang.';
                                } else {
                                    $cekdata = mysqli_query($koneksi, "SELECT nama_pelanggan,cp,lat_long FROM tb_sales WHERE myir = '$myir'");
                                    if (mysqli_num_rows($cekdata) > 0 && strpos($jarak_tiang, 'UNSC') == false) {
                                        while ($d = mysqli_fetch_array($cekdata)) {
                                            $text = "Pelanggan $plgn sudah diinput, Mohon jangan input berulang!";
                                        }
                                    } else {
                                        if (strposa($odp, $dbrb) !== false) {
                                            $datel = 'BRB';
                                        } else if (strposa($odp, $dbtg) !== false) {
                                            $datel = 'BTG';
                                        } else if (strposa($odp, $dpkl1) !== false) {
                                            $datel = 'PKL1';
                                        } else if (strposa($odp, $dpkl2) !== false) {
                                            $datel = 'PKL2';
                                        } else if (strposa($odp, $dpml) !== false) {
                                            $datel = 'PML';
                                        } else if (strposa($odp, $dslw) !== false) {
                                            $datel = 'SLW';
                                        } else if (strposa($odp, $dteg) !== false) {
                                            $datel = 'TEG';
                                        } else {
                                            $datel = 'none';
                                        }

                                        if ($datel == 'none') {
                                            $text = "❗️ Kode STO dalam ODP Tidak Valid. Perbaiki penulisan ODP dan silahkan kirim ulang order nya! ";
                                        } else {
                                            if (stripos($jarak_tiang, 'UNSC') !== false) {
                                                $kategori   = '3';
                                                $status     = 'unsc';
                                                $status_id  = '40';
                                            } else {
                                                $kategori   = '1';
                                                $status     = 'wait_fcc';
                                                $status_id  = '41';
                                            }
                                            $query = mysqli_query($koneksi, "INSERT INTO tb_sales (sales_id, datel, unit, nama_pelanggan, no_ktp, alamat, lat_long, cp, email, odp, kode, paket, jarak_tiang, myir, kategori, isi_pesan, message_id, message_from, tgl_post, tgl_update, status, status_id) VALUES (NULL, '$datel', '$segment', '$plgn', '$no_ktp', '$alamat', '$lat_long', '$cp', '$email', '$odp', '$kode', '$paket', '$jarak_tiang', '$myir', '$kategori', '$pesan','$post_id', '$fromid', '$tgl','$tgl','$status','$status_id')");
                                            $idsales = mysqli_insert_id($koneksi);
                                            if ($query) {
                                                $ambil = mysqli_query($koneksi, "SELECT *,u.fullname as nama_sales FROM  tb_sales s JOIN tb_salesman u ON u.s_telegram_id = s.message_from WHERE sales_id = '$idsales'");
                                                if (mysqli_num_rows($ambil) > 0) {
                                                    while ($d = mysqli_fetch_array($ambil)) {
                                                        if ($d['kategori'] == 1) {
                                                            $kategori = 'DEAL, ODP READY';
                                                        } elseif ($d['kategori'] == 2) {
                                                            $kategori = 'DEAL, ODP NOT READY';
                                                        } elseif ($d['kategori'] == 3) {
                                                            $kategori = 'UNSC';
                                                        }
                                                        $pecahloc       = explode(',', $d['lat_long']);
                                                        $lat            = str_replace(" ", "", $pecahloc[0]);
                                                        $lng            = str_replace(" ", "", $pecahloc[1]);
                                                        $text = "ORDER\n";
                                                        $text  .= "JA$d[sales_id]\n";
                                                        $text  .= "NAMA PELANGGAN : $d[nama_pelanggan]\n";
                                                        $text  .= "CP : $d[cp]\n";
                                                        $text  .= "ODP : $d[odp]\n";
                                                        $text  .= "ALAMAT : $d[alamat]\n";
                                                        $text  .= "MYIR : $d[myir]\n";
                                                        $text  .= "PAKET : $d[paket]\n";
                                                        $text  .= "KATEGORI : $kategori\n";
                                                        $text  .= "SALES : $d[nama_sales]\n";
                                                        $text  .= "LOKASI : \nhttps://www.google.co.id/maps/search/$lat,+$lng";

                                                        $savelog = mysqli_query($koneksi, "INSERT INTO tb_log (sales_id, action_by, action_on, action_status) VALUES ($d[sales_id], '$d[nama_sales]', '$tgl', 1)");
                                                    }
                                                }
                                            } else {
                                                $text  = "Maaf data gagal disimpan, silahkan coba beberapa saat lagi..";
                                                $text .= "Error : " . mysqli_error($koneksi) . " ";
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $text = 'Akun kamu sedang dinonaktifkan. Kamu tidak dapat membuat order.';
                        }
                    } else {
                        $text = 'Kamu belum terdaftar sebagai sales. silahkan register dengan /regsales';
                    }

                    sendApiMsg($chatid, $text, false);
                    break;

                default:
                    $cekt       = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid'");
                    $ceknonik   = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid' AND nik IS NULL");
                    $ceknoname  = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid' AND nama_teknisi IS NULL");
                    $ceknocrew  = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid' AND crew IS NULL");
                    $ceknomitra = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid' AND mitra IS NULL");
                    if (mysqli_num_rows($cekt) <= 0) {
                        $text = 'Silahkan ketik /help untuk melihat menu bantuan :)';
                        sendApiMsg($chatid, $text);
                    } elseif (mysqli_num_rows($ceknonik) > 0) {
                        $text = 'Kamu belum menyelesaikan pendaftaran. Silahkan lanjutkan pendaftarannya';
                        sendApiMsg($chatid, $text);
                        $text = 'Masukan NIK dengan 8 digit angka. Jika kamu belum memiliki nik, gunakan tanggal lahir kamu. misal : 20 Juli 1998, maka masukan nik dengan 20071998';
                        sendApiMsg($chatid, $text);
                        $text = 'Silahkan masukan nik kamu :';
                        sendApiMsgReply($chatid, $text);
                    } elseif (mysqli_num_rows($ceknoname) > 0) {
                        $text = 'Kamu belum menyelesaikan pendaftaran. Silahkan lanjutkan pendaftarannya';
                        sendApiMsg($chatid, $text);
                        $text = '👤 Masukan nama kamu :';
                        sendApiMsgReply($chatid, $text);
                    } elseif (mysqli_num_rows($ceknocrew) > 0) {
                        $text = 'Kamu belum menyelesaikan pendaftaran. Silahkan lanjutkan pendaftarannya';
                        sendApiMsg($chatid, $text);
                        $text = "Masukan KODE DATEL dengan, Contoh : PKL, SLW, BTG, TEG, BRB, PML";
                        sendApiMsg($chatid, $text);
                        $text = '👥 Masukan KODE DATEL :';
                        sendApiMsgReply($chatid, $text);
                    } elseif (mysqli_num_rows($ceknomitra) > 0) {
                        $text = 'Kamu belum menyelesaikan pendaftaran. Silahkan lanjutkan pendaftarannya';
                        sendApiMsg($chatid, $text);
                        $text = 'Masukan Mitra :';
                        sendApiMsgReply($chatid, $text);
                        $text = "Isi Mitra dengan nama perusahaan. misal : HCP, TA, GLOBAL, KOPEGTEL, ZAG, KJS";
                        sendApiMsg($chatid, $text);
                    } else {
                        $text = 'Silahkan ketik /help untuk melihat menu bantuan :)';
                        sendApiMsg($chatid, $text);
                    }

                    break;
            }
        } else {
            switch (true) {
                case $pesan == '/id':
                    sendApiAction($chatid);
                    $text = 'ID Kamu adalah: ' . $fromid;
                    sendApiMsg($chatid, $text);
                    break;

                case $pesan == '/groupid':
                    sendApiAction($chatid);
                    $text = 'GROUP ID : ' . $chatid;
                    sendApiMsg($chatid, $text);
                    break;

                case $pesan == '/showns':
                case $pesan == '/showns@damanasisten_bot':
                    if ($chatid != '-1001303948672') {
                        sendApiAction($chatid);
                        $image_data = file_get_contents("https://api.apiflash.com/v1/urltoimage?access_key=4e8151c3b7104ea28346bfa76e9b4907&height=768&fresh=true&url=https%3A%2F%2Fprovi.jarvisid.com%2Fprovisioning%2Fshow%2Fnew_sales&width=1024");
                        file_put_contents('/home/jarvisid/newjarvis.jarvisid.com/tmp/last_ns.png', $image_data);
                        $text = 'New Sales';
                        $img  = 'last_ns';
                        sendApiImg($chatid, $text, $img);
                    }

                    break;

                case $pesan == '/showmig':
                case $pesan == '/showmig@damanasisten_bot':
                    if ($chatid != '-1001303948672') {
                        sendApiAction($chatid);
                        $image_data = file_get_contents("https://api.apiflash.com/v1/urltoimage?access_key=4e8151c3b7104ea28346bfa76e9b4907&height=768&fresh=true&url=https%3A%2F%2Fprovi.jarvisid.com%2Fprovisioning%2Fshow%2Fmigrasi&width=1024");
                        file_put_contents('/home/jarvisid/newjarvis.jarvisid.com/tmp/last_mig.png', $image_data);
                        $text = 'Migrasi';
                        $img  = 'last_mig';
                        sendApiImg($chatid, $text, $img);
                    }

                    break;

                case $pesan == '/showaddon':
                case $pesan == '/showaddon@damanasisten_bot':
                    if ($chatid != '-1001303948672') {
                        sendApiAction($chatid);
                        $image_data = file_get_contents("https://api.apiflash.com/v1/urltoimage?access_key=4e8151c3b7104ea28346bfa76e9b4907&height=768&fresh=true&url=https%3A%2F%2Fprovi.jarvisid.com%2Fprovisioning%2Fshow%2Fadd_on&width=1024");
                        file_put_contents('/home/jarvisid/newjarvis.jarvisid.com/tmp/last_ao.png', $image_data);
                        $text = 'Addon Modification';
                        $img  = 'last_ao';
                        sendApiImg($chatid, $text, $img);
                    }

                    break;

                case $pesan == '/help':
                case $pesan == '/help@damanasisten_bot':
                    sendApiAction($chatid);
                    $text = "Halo. Kenalin saya Jarvis, asisten WITEL PKL. Saya ditugaskan untuk membantu pekerjaan teman-teman semua.\n\n";
                    $text .= "📖 Berikut yang bisa saya lakukan untuk teman-teman semua :\n\n";
                    $text .= "/showns untuk melihat perolehan order new sales hari ini.\n";
                    $text .= "/showmig untuk melihat perolehan order migrasi hari ini.\n";
                    $text .= "/showaddon untuk melihat perolehan order addon hari ini.\n";
                    $text .= "/order untuk melihat order yang belum dikirim ke teknisi.\n\n";
                    $text .= "== Teknisi ==\n";
                    $text .= "/formatcreate untuk melihat format permintaan create aktivasi.\n";
                    $text .= "/status untuk melihat status order. (Mereply Pesan Order)\n";
                    $text .= "!create untuk mengirim order provisioning siap create.\n";
                    $text .= "!doneLME untuk mengirim order provisioning WMS siap create.\n";
                    $text .= "/reqsc [enter] ODP [enter ] PORT [enter] DC untuk request SC ke HD. (Mereply Pesan Order)\n\n";
                    $text .= "== Helpdesk ==\n";
                    $text .= "reply order provisioning dengan jawaban 'wait' untuk mengambil order tersebut.\n\n";
                    $text .= "== Sales ==\n";
                    $text .= "/inputWMS untuk menginput order pelanggan sales WMS.\n\n";
                    $text .= "== Non Fungsional ==\n";
                    $text .= "/help untuk melihat informasi bantuan.\n";
                    $text .= "/id untuk melihat informasi ID User Telegram.\n";
                    $text .= "/groupid untuk melihat informasi ID Grup Telegram.\n\n";
                    $text .= "😎 Terima Kasih";
                    sendApiMsg($chatid, $text);
                    break;

                case $pesan == '/formatcreate':
                case $pesan == '/formatcreate@damanasisten_bot':
                    sendApiAction($chatid);
                    $text = "!create data\n";
                    $text .= "ID VALINS : \*\n";
                    $text .= "ORDER : \*\n";
                    $text .= "SC : \*\n";
                    $text .= "A/N : \*\n";
                    $text .= "ALAMAT : \*\n";
                    $text .= "CP : \n";
                    $text .= "NO.TELP : \n";
                    $text .= "NO.INET : \n";
                    $text .= "ODP : \*\n";
                    $text .= "PORT : \n";
                    $text .= "SISA : \n";
                    $text .= "SN ONT : \*\n";
                    $text .= "STB ID : \n";
                    $text .= "IP CAM : \n";
                    $text .= "WIFI EXT : \n";
                    $text .= "ORBIT : \n";
                    $text .= "DC : \n";
                    $text .= "SOC : \n";
                    $text .= "PRECON : \n";
                    $text .= "MITRA : \n";
                    $text .= "TEKNISI : \n";
                    $text .= "CREW : \n";
                    $text .= "BC : \*\n";
                    $text .= "SCLAM : \n";
                    $text .= "BREKET : \n";
                    $text .= "T7 : \n";
                    $text .= "ASS TIANG : \n";
                    $text .= "UTP : \n";
                    $text .= "\n\n\* Wajib diisi";
                    sendApiMsg($chatid, $text, false, 'Markdown');
                    break;

                case $pesan == '/order':
                case $pesan == '/order@damanasisten_bot':
                    sendApiAction($chatid);

                    switch ($chatid) {
                            // DCS
                        case -263944966:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'PKL1' AND unit = 'DCS' AND kategori = 1");
                            break;
                        case -714329985:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'PKL2' AND unit = 'DCS' AND kategori = 1");
                            break;
                        case -341677349:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'BRB' AND unit = 'DCS' AND kategori = 1");
                            break;
                        case -280898518:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'BTG' AND unit = 'DCS' AND kategori = 1");
                            break;
                        case -338613303:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'TEG' AND unit = 'DCS' AND kategori = 1");
                            break;
                        case -336766109:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'SLW' AND unit = 'DCS' AND kategori = 1");
                            break;
                        case -371367732:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'PML' AND unit = 'DCS' AND kategori = 1");
                            break;

                            // NON DCS
                        case -770138148:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'PKL1' AND unit != 'DCS' AND kategori = 1");
                            break;
                        case -644062746:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'PKL2' AND unit != 'DCS' AND kategori = 1");
                            break;
                        case -731237375:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'BRB' AND unit != 'DCS' AND kategori = 1");
                            break;
                        case -669337165:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'BTG' AND unit != 'DCS' AND kategori = 1");
                            break;
                        case -642556631:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'TEG' AND unit != 'DCS' AND kategori = 1");
                            break;
                        case -696468812:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'SLW' AND unit != 'DCS' AND kategori = 1");
                            break;
                        case -709203943:
                            $ambilwait = mysqli_query($koneksi, "SELECT * FROM  tb_sales WHERE status_id = 1 AND datel = 'PML' AND unit != 'DCS' AND kategori = 1");
                            break;

                        default:
                            $text = '❗️ Tidak untuk digunakan digrup ini!';
                            sendApiMsg($chatid, $text);
                            break;
                    }


                    if (mysqli_num_rows($ambilwait) > 0) {
                        while ($d = mysqli_fetch_array($ambilwait)) {
                            foreach ($ambilwait as $row) {
                                if ($row['kategori'] == 1) {
                                    $kategori = 'DEAL, ODP READY';
                                } elseif ($row['kategori'] == 2) {
                                    $kategori = 'DEAL, ODP NOT READY';
                                } elseif ($row['kategori'] == 3) {
                                    $kategori = 'UNSC';
                                }
                                $text = "ORDER\n";
                                $text .= "JA$row[sales_id] \n";
                                $text .= "NAMA PELANGGAN : $row[nama_pelanggan]\n";
                                $text .= "CP : $row[cp]\n";
                                $text .= "ODP : $row[odp]\n";
                                $text .= "ALAMAT : $row[alamat]\n";
                                $text .= "PAKET : $row[paket]\n";
                                $text .= "JARAK TIANG : $row[jarak_tiang]\n";
                                $text .= "KATEGORI : $kategori\n";
                                $text .= "LOKASI : \nhttps://www.google.co.id/maps/search/$lat,+$lng";
                                sendApiMsg($chatid, $text);
                            }
                        }
                    } else {
                        $text = '✅ Semua order sudah disend ke teknisi.';
                        sendApiMsg($chatid, $text);
                    }
                    break;

                case preg_match("/\!create (.*)/", $pesan, $hasil):
                    sendApiAction($chatid);

                    preg_match('/SC\s{0,1}:(.+)/i', $pesan, $sc);
                    preg_match('/ORDER\s{0,1}:(.+)/i', $pesan, $order);
                    preg_match('/ODP\s{0,1}:(.+)/i', $pesan, $odp);
                    preg_match('/BC\s{0,1}:(.+)/i', $pesan, $bc);

                    $sc             = intval(preg_replace('/[^0-9]+/', '', trim($sc[1])), 10);
                    $order          = (trim($order[1]) != '' ? trim($order[1]) : '-');
                    $odp            = (trim($odp[1]) != '' ? trim($odp[1]) : '-');
                    $bc             = (trim($bc[1]) != '' ? trim($bc[1]) : '-');
                    $cekKarakter    = substr($hasil[0], 12);

                    // $cekmitra       = mysqli_query($koneksi, "SELECT nama_mitra FROM tb_mitra WHERE singkat = '$mitra' OR nama_mitra LIKE '%$mitra%'");
                    $cekbc          = mysqli_query($koneksi, "SELECT qrcode FROM tb_qrcode WHERE qrcode='" . $bc . "'");
                    $cekbcpakai     = mysqli_query($koneksi, "SELECT barcode FROM tb_provisioning WHERE barcode = '$bc'");
                    $cekut          = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid'");
                    $cekScSales     = mysqli_query($koneksi, "SELECT new_sc,progress_to FROM tb_sales WHERE new_sc = '$sc'");
                    $ceksckendala   = mysqli_query($koneksi, "SELECT new_sc,status_id FROM tb_sales WHERE (new_sc = '$sc') AND (status_id = 6 OR status_id = 12 OR status_id = 13) AND (progress_to IS NULL)");
                    $cekScProvi     = mysqli_query($koneksi, "SELECT sc_baru,status_id FROM tb_provisioning WHERE sc_baru = '$sc'");

                    if (mysqli_num_rows($cekut) > 0) {

                        if (mysqli_num_rows($cekScSales) > 0 || ($order == 'MIGRASI' || $order == 'MIG')) {

                            if (mysqli_num_rows($ceksckendala) <= 0 || ($order == 'MIGRASI' || $order == 'MIG')) {

                                if (mysqli_num_rows($cekScProvi) > 0) {
                                    while ($d = mysqli_fetch_array($ceksc)) {
                                        $text = "SC $sc sudah ada, statusnya : " . statusSC($d['status_id']) . "";
                                    }
                                } else {
                                    /* else if (mysqli_num_rows($cekmitra) <= 0) {
                                        $text = "⚠️ Mitra tidak ditemukan, silahkan perbaiki nama mitra!";
                                    } */
                                    if ($cekKarakter != '') {
                                        $text = "⚠️ Pastikan tidak ada karakter lain setelah pesan \"!create data\", silahkan cek ulang!";
                                    } else if ($order != 'PSB' && $order != 'MIGRASI' && $order != 'MIG' && $order != 'MO') {
                                        $text = "⚠️ Hanya gunakan PSB, MIG/MIGRASI, dan MO untuk isian ORDER!";
                                    } elseif (strlen($odp) < 14 || strlen($odp) > 15) {
                                        $text = "⚠️ ODP tidak valid! tulis ODP dengan ODP : ODP-PKL-FAA/001";
                                    } else if (strpos($odp, '/') == false) {
                                        $text = "tanda / nya mana? misal : ODP-PKL-FAA/001";
                                    } else if (cekStoOdp(substr($odp, 4, 3)) == false) {
                                        $text = "⚠️ Pastikan format ODP, STO nya sudah benar!";
                                    } else if ((mysqli_num_rows($cekbc) <= 0) && (strpos($order, 'MIG') !== false || strpos($order, 'PSB') !== false || strpos($order, 'MIGRASI') !== false)) {
                                        $text  = "⚠️ Barcode $bc tidak valid, silahkan perbaiki/ganti barcode nya! *lampirkan foto barcode jika barcode sudah sesuai.";
                                    } else if ((mysqli_num_rows($cekbcpakai) > 0) && (strpos($order, 'MIG') !== false || strpos($order, 'PSB') !== false || strpos($order, 'MIGRASI') !== false)) {
                                        $text = "⚠️ Barcode $bc sudah digunakan, silahkan ganti yang lain ya..";
                                    } else {
                                        $text = "Oke, silahkan lanjut kirim lokasi pelanggan dengan mereply pesan request aktivasi sebelumnya.";
                                    }
                                }
                            } else {
                                while ($k = mysqli_fetch_array($ceksckendala)) {
                                    $sc     = $k['new_sc'];
                                    $status = statusSales($k['status_id']);
                                    $text   = "❌ SC$sc ditolak! Status SC $status, atau silahkan koordinasi dengan TL.";
                                }
                            }
                        } else {
                            $text = '❌ SC tidak valid! Mohon Cek kembali nomor SC nya, atau silahkan koordinasi dengan inputers.';
                        }
                    } else {
                        $text = '❗️ Kamu belum mendaftar sebagai teknisi pada @damanasisten_bot. Silahkan lakukan pendaftaran dengan klik @damanasisten_bot kemudian ketik /regteknisi atau /help untuk infromasi bantuan.';
                    }

                    sendApiMsg($chatid, $text, $meid);

                    /*
                    $pesan = strtoupper($message['text']);
                    $pesan = str_replace('A/N', 'AN', $pesan);
                    $pesan = str_replace('A/N ', 'AN', $pesan);
                    $pesan = str_replace('NAMA', 'AN', $pesan);
                    $pesan = str_replace('STB.ID', 'STB ID', $pesan);
                    $pesan = str_replace('SPL 1/2', 'SPL 1:2', $pesan);
                    $pesan = str_replace('SPLITER 1/2', 'SPL 1:2', $pesan);
                    $pesan = str_replace('SPLITTER 1/2', 'SPL 1:2', $pesan);
                    $pesan = str_replace('SPLITTER 1:2', 'SPL 1:2', $pesan);
                    $pesan = str_replace('SPL 1/4', 'SPL 1:4', $pesan);
                    $pesan = str_replace('SPLITER 1/4', 'SPL 1:4', $pesan);
                    $pesan = str_replace('SPLITTER 1/4', 'SPL 1:4', $pesan);
                    $pesan = str_replace('SPLITTER 1:4', 'SPL 1:4', $pesan);
                    $pesan = str_replace('SPL 1/8', 'SPL 1:8', $pesan);
                    $pesan = str_replace('SPLITER 1/8', 'SPL 1:8', $pesan);
                    $pesan = str_replace('SPLITTER 1/8', 'SPL 1:8', $pesan);
                    $pesan = str_replace('SPLITTER 1:8', 'SPL 1:8', $pesan);
                    $pesan = str_replace('CASET', 'CASSETE', $pesan);
                    $pesan = str_replace('KASET', 'CASSETE', $pesan);
                    $pesan = str_replace('CASETTE', 'CASSETE', $pesan);
                    $pesan = str_replace('BRIKET', 'BREKET', $pesan);
                    $pesan = str_replace('BRACET', 'BREKET', $pesan);
                    $pesan = str_replace('S CLAM', 'SCLAM', $pesan);

                    preg_match('/ORDER\s{0,1}:(.+)/i', $pesan, $order);
                    preg_match('/AN\s{0,1}:(.+)/i', $pesan, $an);
                    preg_match('/ALAMAT\s{0,1}:(.+)/i', $pesan, $alamat);
                    preg_match('/TLP\s{0,1}:(.+)/i', $pesan, $telp);
                    preg_match('/INET\s{0,1}:(.+)/i', $pesan, $inet);
                    preg_match('/ODP\s{0,1}:(.+)/i', $pesan, $odp);
                    preg_match('/PORT\s{0,1}:(.+)/i', $pesan, $port);
                    preg_match('/SISA\s{0,1}:(.+)/i', $pesan, $sisa);
                    preg_match('/SN\s{0,1}:(.+)/i', $pesan, $sn);
                    preg_match('/SC\s{0,1}:(.+)/i', $pesan, $sc);
                    preg_match('/DC\s{0,1}:(.+)/i', $pesan, $dc);
                    preg_match('/MITRA\s{0,1}:(.+)/i', $pesan, $mitra);
                    preg_match('/TEKNISI\s{0,1}:(.+)/i', $pesan, $teknisi);
                    preg_match('/CREW\s{0,1}:(.+)/i', $pesan, $crew);
                    preg_match('/BC\s{0,1}:(.+)/i', $pesan, $bc);
                    preg_match('/SCLAM\s{0,1}:(.+)/i', $pesan, $sclam);
                    preg_match('/BREKET\s{0,1}:(.+)/i', $pesan, $breket);
                    preg_match('/ROS\s{0,1}:(.+)/i', $pesan, $ros);
                    preg_match('/T7\s{0,1}:(.+)/i', $pesan, $t7);
                    preg_match('/CP\s{0,1}:(.+)/i', $pesan, $cp);
                    preg_match('/STB ID\s{0,1}:(.+)/i', $pesan, $stb_id);
                    preg_match('/SPL 1:2\s{0,1}:(.+)/i', $pesan, $spl12);
                    preg_match('/SPL 1:4\s{0,1}:(.+)/i', $pesan, $spl14);
                    preg_match('/SPL 1:8\s{0,1}:(.+)/i', $pesan, $spl18);
                    preg_match('/CASSETE\s{0,1}:(.+)/i', $pesan, $cassete);
                    preg_match('/ADAPTER\s{0,1}:(.+)/i', $pesan, $adapter);
                    preg_match('/UTP\s{0,1}:(.+)/i', $pesan, $utp);
                    preg_match('/RJ45\s{0,1}:(.+)/i', $pesan, $rj45);
                    preg_match('/REDAMAN\s{0,1}:(.+)/i', $pesan, $redaman);
                    preg_match('/PREKSO\s{0,1}:(.+)/i', $pesan, $prekso);
                    preg_match('/OTP\s{0,1}:(.+)/i', $pesan, $otp);

                    $order      = (trim($order[1]) != '' ? trim($order[1]) : '-');
                    $an         = (trim($an[1]) != '' ? trim($an[1]) : '-');
                    $alamat     = (trim($alamat[1]) != '' ? trim($alamat[1]) : '-');
                    $telp       = (trim($telp[1]) != '' ? trim($telp[1]) : '-');
                    $inet       = (trim($inet[1]) != '' ? trim($inet[1]) : '-');
                    $odp        = (trim($odp[1]) != '' ? trim($odp[1]) : '-');
                    $port       = (trim($port[1]) != '' ? trim($port[1]) : '-');
                    $sisa       = (trim($sisa[1]) != '' ? trim($sisa[1]) : '-');
                    $sn         = (trim($sn[1]) != '' ? trim($sn[1]) : '-');
                    $sc         = intval(preg_replace('/[^0-9]+/', '', trim($sc[1])), 10);
                    $dc         = (trim($dc[1]) != '' ? trim($dc[1]) : '-');
                    $mitra      = (trim($mitra[1]) != '' ? trim($mitra[1]) : '-');
                    $teknisi    = (trim($teknisi[1]) != '' ? trim($teknisi[1]) : '-');
                    $crew       = (trim($crew[1]) != '' ? trim($crew[1]) : '-');
                    $bc         = (trim($bc[1]) != '' ? trim($bc[1]) : '-');
                    $sclam      = !empty($sclam) ? (trim($sclam[1]) != '' ? trim($sclam[1]) : '-') : '-';
                    $breket     = !empty($breket) ? (trim($breket[1]) != '' ? trim($breket[1]) : '-') : '-';
                    $ros        = !empty($ros) ? (trim($ros[1]) != '' ? trim($ros[1]) : '-') : '-';
                    $t7         = !empty($t7) ? (trim($t7[1]) != '' ? trim($t7[1]) : '-') : '-';
                    $cp         = (trim($cp[1]) != '' ? trim($cp[1]) : '-');
                    $stbid      = !empty($stb_id) ? (trim($stb_id[1]) != '' ? trim($stb_id[1]) : '-') : '-';
                    $spl12      = !empty($spl12) ? (trim($spl12[1]) != '' ? trim($spl12[1]) : '-') : '-';
                    $spl14      = !empty($spl14) ? (trim($spl14[1]) != '' ? trim($spl14[1]) : '-') : '-';
                    $spl18      = !empty($spl18) ? (trim($spl18[1]) != '' ? trim($spl18[1]) : '-') : '-';
                    $cassete    = !empty($cassete) ? (trim($cassete[1]) != '' ? trim($cassete[1]) : '-') : '-';
                    $adapter    = !empty($adapter) ? (trim($adapter[1]) != '' ? trim($adapter[1]) : '-') : '-';
                    $utp        = !empty($utp) ? (trim($utp[1]) != '' ? trim($utp[1]) : '-') : '-';
                    $rj45       = !empty($rj45) ? (trim($rj45[1]) != '' ? trim($rj45[1]) : '-') : '-';
                    $redaman    = !empty($redaman) ? (trim($redaman[1]) != '' ? trim($redaman[1]) : '-') : '-';
                    $prekso     = !empty($prekso) ? (trim($prekso[1]) != '' ? trim($prekso[1]) : '-') : '-';
                    $otp        = !empty($otp) ? (trim($otp[1]) != '' ? trim($otp[1]) : '-') : '-';
                    // variable pelengkap
                    $odp        = str_replace(' ', '', $odp);
                    $tgl        = date('Y-m-d H:i:s');
                    $post_name  = $message["from"]["username"];
                    $m_id       = $message["message_id"];
                    $g_id       = $chatid;

                    $datel = getDatel($odp);

                    $cekut      = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid'");
                    $ceksc      = mysqli_query($koneksi, "SELECT sc_baru,status_id FROM tb_provisioning WHERE sc_baru = '$sc'");
                    $cekscsales = mysqli_query($koneksi, "SELECT sales_id,new_sc,message_from,message_id FROM tb_sales WHERE new_sc = '$sc'");
                    $cekbc      = mysqli_query($koneksi, "SELECT qrcode FROM tb_qrcode WHERE qrcode='" . $bc . "'");
                    $cekbcpakai = mysqli_query($koneksi, "SELECT barcode FROM tb_provisioning WHERE barcode = '$bc'");
                    $cekmitra   = mysqli_query($koneksi, "SELECT nama_mitra FROM tb_mitra WHERE singkat = '$mitra' OR nama_mitra LIKE '%$mitra%'");
                    $jadwal_sholat  = file_get_contents("https://api.banghasan.com/sholat/format/json/jadwal/kota/723/tanggal/" . date('Y-m-d') . "");
                    $json           = json_decode($jadwal_sholat, TRUE);
                    $tanggal        =  $json['jadwal']['data']['tanggal'];
                    $status_api     =  $json['jadwal']['status'];
                    $ashar    =  str_replace(':', '', $json['jadwal']['data']['ashar']);
                    $dzuhur   =  str_replace(':', '', $json['jadwal']['data']['dzuhur']);
                    $isya     =  str_replace(':', '', $json['jadwal']['data']['isya']);
                    $maghrib  =  str_replace(':', '', $json['jadwal']['data']['maghrib']);
                    $subuh    =  str_replace(':', '', $json['jadwal']['data']['subuh']);

                    if (mysqli_num_rows($ceksc) > 0) {
                        while ($d = mysqli_fetch_array($ceksc)) {
                            $text = "SC $sc sudah ada, statusnya : " . statusSC($d['status_id']) . "";
                        }
                    } else {
                        if ($order != 'PSB' && $order != 'MIGRASI' && $order != 'MIG' && $order != 'MO') {
                            $text = "⚠️ Hanya gunakan PSB, MIG/MIGRASI, dan MO untuk isian ORDER!";
                        } else if ((mysqli_num_rows($cekscsales) <= 0) && (strpos($order, 'PSB') !== false)) {
                            $text = '❌ SC tidak valid! Mohon Cek kembali nomor SC nya, atau silahkan koordinasi dengan inputers.';
                        } elseif (strlen($odp) < 14 || strlen($odp) > 15) {
                            $text = "⚠️ ODP tidak valid! tulis ODP dengan ODP : ODP-PKL-FAA/001";
                        } else if (strpos($odp, '/') == false) {
                            $text = "tanda / nya mana? misal : ODP-PKL-FAA/001";
                        } else if (cekStoOdp(substr($odp, 4, 3)) == false) {
                            $text = "⚠️ Pastikan format ODP, STO nya sudah benar!";
                        } else if (mysqli_num_rows($cekmitra) <= 0) {
                            $text = "⚠️ Mitra tidak ditemukan, silahkan perbaiki nama mitra!";
                        } else if ((mysqli_num_rows($cekbc) <= 0) && (strpos($order, 'MIG') !== false || strpos($order, 'PSB') !== false || strpos($order, 'MIGRASI') !== false)) {
                            $text  = "⚠️ Barcode $bc tidak valid, silahkan perbaiki/ganti barcode nya! *lampirkan foto barcode jika barcode sudah sesuai.";
                        } else if ((mysqli_num_rows($cekbcpakai) > 0) && (strpos($order, 'MIG') !== false || strpos($order, 'PSB') !== false || strpos($order, 'MIGRASI') !== false)) {
                            $text = "⚠️ Barcode $bc sudah digunakan, silahkan ganti yang lain ya..";
                        } else {
                            if (mysqli_num_rows($cekut) > 0) {
                                $an = str_replace("Â", "", $an);
                                $query = mysqli_query($koneksi, "INSERT INTO tb_provisioning (datel, order_type, atas_nama, alamat, cp, voice, internet, odp, port, sisa, sn, sc, sc_baru, mitra, teknisi, crew, barcode, stb_id, redaman, post_by, group_id, message_id, tgl_masuk) VALUES ('$datel', '$order', '$an', '$alamat', '$cp', '$telp', '$inet', '$odp', '$port', '$sisa', '$sn', '$sc', '$sc', '$mitra', '$teknisi', '$crew', '$bc', '$stbid', '$redaman', '$fromid', '$g_id','$m_id', '$tgl')");
                                $idcreate = mysqli_insert_id($koneksi);
                                if ($query) {
                                    $insmtr = mysqli_query($koneksi, "INSERT INTO tb_material (create_id, dropcore, sclam, breket, ros, t_tujuh, spl1_2, spl1_4, spl1_8, cassete, adapter, utp, rj45, prekso, otp) VALUES ('$idcreate', '$dc', '$sclam', '$breket', '$ros', '$t7', '$spl12', '$spl14', '$spl18', '$cassete', '$adapter', '$utp', '$rj45', '$prekso', '$otp')");
                                    if ($insmtr) {
                                        if ($status_api != 'error') {
                                            if (date("Hi") > "$dzuhur" && date("Hi") < "1235" && date("N") == 5) {
                                                $text = "📢 ayoo sholatt jum'at dulu 🕌";
                                            } elseif (date("Hi") > "$dzuhur" && date("Hi") < "1220") {
                                                $text = "📢 sudah masuk waktu dhuhur.. yuk sholat dulu ya 🕌 \n";
                                            } else if (date("Hi") > "$ashar" && date("Hi") < "1530") {
                                                $text = "📢 sudah masuk waktu ashar.. yuk sholat dulu ya 🕌";
                                            } else if (date("Hi") > "1800") {
                                                $text = "lanjut besok lagi ya.. langsung pulang kerumah, hindari kerumunan, jangan lupa mandi sebelum berinteraksi dengan keluarga anda dirumah :)";
                                            } else if (date("Hi") > "$maghrib" && date("Hi") < "1820") {
                                                $text = "📢 sudah masuk waktu maghrib.. yuk sholat dulu ya 🕌";
                                            } else if (date("Hi") > "$isya" && date("Hi") < "2000") {
                                                $text = "📢 sudah masuk waktu isya.. yuk sholat dulu ya 🕌";
                                            } else {
                                                $text  = "yuk lanjut rekan HD 🤙";
                                            }
                                        } else {
                                            $text  = "yuk lanjut rekan HD 🤙";
                                        }
                                        while ($d = mysqli_fetch_array($cekscsales)) {
                                            $sales_id = $d['sales_id'];
                                            $s_telegram_id = $d['message_from'];
                                            $s_m_id = $d['message_id'];
                                            $ambilt = mysqli_query($koneksi, "SELECT nama_teknisi FROM tb_teknisi WHERE t_telegram_id = '$fromid'");
                                            while ($t = mysqli_fetch_array($ambilt)) {
                                                $savelog = mysqli_query($koneksi, "INSERT INTO tb_log (sales_id, action_by, action_on, action_status) VALUES ('$sales_id','$t[nama_teknisi]','$tgl',7)");
                                                $udatasales = mysqli_query($koneksi, "UPDATE tb_sales SET status = 'wait_act', status_id = '7', tgl_update = '$tgl'  WHERE sales_id='$sales_id'");
                                                $pesantosales = "⚠️ Order dimintakan create oleh teknisi $t[nama_teknisi]";
                                                sendApiMsg($s_telegram_id, $pesantosales, $s_m_id, 'Markdown');
                                            }
                                        }
                                    } else {
                                        $text  = "Error! Data Material Gagal Disimpan!";
                                        $text .= "Error : " . mysqli_error($koneksi);
                                    }
                                } else {
                                    $text  = "Error! Data Gagal Disimpan! \n";
                                    $text .= "Error : " . mysqli_error($koneksi);
                                }
                            } else {
                                $text = '❗️ Kamu belum mendaftar sebagai teknisi pada @damanasisten_bot. silahkan lakukan pendaftaran dengan klik @damanasisten_bot kemudian ketik /regteknisi atau /help untuk infromasi bantuan.';
                            }
                        }
                    }

                    sendApiMsg($chatid, $text, $meid);
                    */
                    break;

                case preg_match("/\!inputWMS (.*)/", $pesan, $hasil):
                    if ($chatid == '-1001189351404' || $chatid == '-423730453') { // -423730453
                        sendApiAction($chatid);
                        $cekus = mysqli_query($koneksi, "SELECT s_telegram_id FROM tb_salesman WHERE s_telegram_id = '$fromid'");
                        $cekhs = mysqli_query($koneksi, "SELECT s_telegram_id,active FROM tb_salesman WHERE s_telegram_id = '$fromid' AND active = 1");
                        /*bersih bersih pesan*/
                        $pesan = strtoupper($message['text']);
                        $pesan = str_replace('NAMA PLGN', 'NAMA', $pesan);
                        $pesan = str_replace('ALAMAT PEMASANGAN', 'ALAMAT', $pesan);


                        preg_match('/NAMA\s{0,1}:(.+)/i', $pesan, $plgn);
                        preg_match('/KTP\s{0,1}:(.+)/i', $pesan, $no_ktp);
                        preg_match('/ALAMAT\s{0,1}:(.+)/i', $pesan, $alamat);
                        preg_match('/CP\s{0,1}:(.+)/i', $pesan, $cp);
                        preg_match('/EMAIL\s{0,1}:(.+)/i', $pesan, $email);
                        preg_match('/PAKET\s{0,1}:(.+)/i', $pesan, $paket);
                        preg_match('/SALES\s{0,1}:(.+)/i', $pesan, $kode_sales);
                        preg_match('/ODP\s{0,1}:(.+)/i', $pesan, $odp);
                        preg_match('/KOORDINAT\s{0,1}:(.+)/i', $pesan, $lat_long);
                        preg_match('/JARAK TIANG \s{0,1}:(.+)/i', $pesan, $jarak_tiang);

                        $plgn        = (trim($plgn[1]) != '' ? trim($plgn[1]) : '-');
                        $no_ktp      = (trim($no_ktp[1]) != '' ? trim($no_ktp[1]) : '-');
                        $alamat      = (trim($alamat[1]) != '' ? trim($alamat[1]) : '-');
                        $cp          = (trim($cp[1]) != '' ? trim($cp[1]) : '-');
                        $email       = (trim($email[1]) != '' ? trim($email[1]) : '-');
                        $paket       = (trim($paket[1]) != '' ? trim($paket[1]) : '-');
                        $kode_sales  = (trim($kode_sales[1]) != '' ? trim($kode_sales[1]) : '-');
                        $odp         = (trim($odp[1]) != '' ? trim($odp[1]) : '-');
                        $lat_long    = (trim($lat_long[1]) != '' ? trim($lat_long[1]) : '-');
                        $jarak_tiang = (trim($jarak_tiang[1]) != '' ? trim($jarak_tiang[1]) : '-');

                        // variable sales 
                        $pecahloc       = explode(',', $lat_long);
                        $lat            = str_replace(" ", "", $pecahloc[0]);
                        $lng            = str_replace(" ", "", $pecahloc[1]);
                        $ceklat         = str_split($lat);
                        $tgl            = date('Y-m-d H:i:s');
                        $post_id        = $message["message_id"];
                        $dbrb           = array('BRB', 'BKA', 'BMU', 'KTM', 'TTL');
                        $dbtg           = array('BTG', 'BDY', 'SBA');
                        $dpkl1          = array('PKL');
                        $dpkl2          = array('KJE', 'KDW');
                        $dpml           = array('PML', 'CMA', 'RDD');
                        $dslw           = array('SLW', 'ADW', 'BLU');
                        $dteg           = array('MGN', 'TEG', 'TGL');

                        if ($plgn == '') {
                            $text = 'Nama Pelanggan tidak boleh kosong! Lengkapi data dan kirim ulang.';
                        } elseif ($cp == '') {
                            $text = 'CP Pelanggan tidak boleh kosong! Lengkapi data dan kirim ulang.';
                        } elseif (strlen($kode_sales) < 7) {
                            $text = 'KODE SALES tidak valid. Silahkan perbaiki dan kirim ulang.';
                        } elseif ($odp == '') {
                            $text = 'ODP tidak boleh kosong! Lengkapi data dan kirim ulang.';
                        } elseif (strlen($odp) < 14 || strlen($odp) > 15) {
                            $text = 'ODP Tidak valid! Lengkapi penulisan ODP. misal : ODP-PKL-FAA/001';
                        } elseif (stripos($odp, '/') == false) {
                            $text = '❌ Kurang tanda / nya. Seharusnya : ODP-PKL-FAA/001 ';
                        } elseif ($lat_long == '') {
                            $text = 'KOORDINAT tidak boleh kosong! Lengkapi data dan kirim ulang.';
                        } elseif (stripos($lat_long, 'https') !== false) {
                            $text = 'Isi koordinat dengan latitude,longitude. misal : -6.89041,109.620956';
                            $text .= 'Silahkan perbaiki dan kirim ulang.';
                        } elseif (stripos($lat_long, ',') == false) {
                            $text = 'Penulisan koordinat tidak sesuai. contoh yang sesuai : -6.89041,109.620956';
                            $text .= 'Silahkan perbaiki dan kirim ulang.';
                        } elseif ($ceklat[2] != '.') {
                            $text = 'Penulisan koordinat tidak sesuai. contoh yang sesuai : -6.89041,109.620956';
                            $text .= 'Silahkan perbaiki dan kirim ulang.';
                        } else {
                            $cekdata = mysqli_query($koneksi, "SELECT nama_pelanggan,cp,lat_long FROM tb_sales WHERE nama_pelanggan = '$plgn' AND cp = '$cp'");
                            if (mysqli_num_rows($cekdata) > 0) {
                                while ($d = mysqli_fetch_array($cekdata)) {
                                    $text = "Pelanggan $plgn sudah diinput, Mohon jangan input berulang!";
                                }
                            } else {
                                if (strposa($odp, $dbrb) !== false) {
                                    $datel = 'BRB';
                                } else if (strposa($odp, $dbtg) !== false) {
                                    $datel = 'BTG';
                                } else if (strposa($odp, $dpkl1) !== false) {
                                    $datel = 'PKL1';
                                } else if (strposa($odp, $dpkl2) !== false) {
                                    $datel = 'PKL2';
                                } else if (strposa($odp, $dpml) !== false) {
                                    $datel = 'PML';
                                } else if (strposa($odp, $dslw) !== false) {
                                    $datel = 'SLW';
                                } else if (strposa($odp, $dteg) !== false) {
                                    $datel = 'TEG';
                                } else {
                                    $datel = 'none';
                                }

                                if ($datel == 'none') {
                                    $text = "❗️ Kode STO dalam ODP Tidak Valid. Perbaiki penulisan ODP dan silahkan kirim ulang order nya! ";
                                } else {
                                    if (stripos($jarak_tiang, 'UNSC') !== false) {
                                        $kategori   = '3';
                                        $status     = 'unsc';
                                    } else {
                                        $kategori   = '1';
                                        $status     = 'waiting';
                                    }
                                    $query = mysqli_query($koneksi, "INSERT INTO tb_sales (sales_id, datel, nama_pelanggan, no_ktp, alamat, lat_long, cp, email, odp, kode, paket, jarak_tiang, myir, segment, kategori, isi_pesan, message_id, message_from, tgl_post, tgl_update, status) VALUES (NULL, '$datel', '$plgn', '$no_ktp', '$alamat', '$lat_long', '$cp', '$email', '$odp', '$kode_sales', '$paket', '$jarak_tiang', '0', '1', '$kategori', '$pesan','$post_id', '$fromid', '$tgl', '$tgl','$status')");
                                    $idsales = mysqli_insert_id($koneksi);
                                    if ($query) {
                                        $ambil = mysqli_query($koneksi, "SELECT s.*,u.fullname AS nama_sales FROM  tb_sales s JOIN tb_salesman u ON u.s_telegram_id = s.message_from WHERE sales_id = '$idsales'");
                                        if (mysqli_num_rows($ambil) > 0) {
                                            while ($d = mysqli_fetch_array($ambil)) {
                                                if ($d['kategori'] == 1) {
                                                    $kategori = 'DEAL, ODP READY';
                                                } elseif ($d['kategori'] == 2) {
                                                    $kategori = 'DEAL, ODP NOT READY';
                                                } elseif ($d['kategori'] == 3) {
                                                    $kategori = 'UNSC';
                                                }
                                                $pecahloc       = explode(',', $d['lat_long']);
                                                $lat            = str_replace(" ", "", $pecahloc[0]);
                                                $lng            = str_replace(" ", "", $pecahloc[1]);

                                                $savelog = mysqli_query($koneksi, "INSERT INTO tb_log (sales_id, action_by, action_on, action_status) VALUES ($d[sales_id], '$d[nama_sales]', '$tgl', 1)");
                                                if ($d['kategori'] == 1) {
                                                    $dtl = $d['datel'];

                                                    $text = "ORDER\n";
                                                    $text .= "JA$d[sales_id] \n";
                                                    $text .= "SEGMENT : WMS\n";
                                                    $text .= "PELANGGAN : $d[nama_pelanggan]\n";
                                                    $text .= "CP : $d[cp]\n";
                                                    $text .= "ODP : $d[odp]\n";
                                                    $text .= "ALAMAT : $d[alamat]\n";
                                                    $text .= "JARAK TIANG : $d[jarak_tiang]\n";
                                                    $text .= "KATEGORI : $kategori\n";
                                                    $text .= "PAKET : $d[paket]\n";
                                                    $text .= "SALES : $d[nama_sales]\n";
                                                    $text .= "LOKASI : \nhttps://www.google.co.id/maps/search/$lat,+$lng";
                                                }
                                            }
                                        } else {
                                            $text = 'Akun kamu sedang dinonaktifkan. Kamu tidak dapat membuat order.';
                                        }
                                    } else {
                                        $text  = "Maaf data gagal disimpan, silahkan coba beberapa saat lagi..";
                                    }
                                }
                            }
                        }
                        // if (mysqli_num_rows($cekus)>0) {
                        //     if (mysqli_num_rows($cekhs)>0) {

                        //     }
                        //     else{
                        //         $text = 'Kamu sudah mendaftar sebagai sales, namun akun ini belum di approve admin. silahkan hubungi admin..';
                        //     }
                        // }
                        // else{
                        //     $text = 'Kamu belum terdaftar sebagai sales. silahkan register dengan /regsales';
                        // }
                    } else {
                        $text = 'Maaf fitur dikunci, hanya untuk grup terdaftar!';
                    }
                    sendApiMsg($chatid, $text, $meid);
                    break;

                case preg_match("/\!doneLME (.*)/", $pesan, $hasil):
                    if ($chatid == '-1001189351404' || $chatid == '-423730453') { // -423730453
                        sendApiAction($chatid);
                        /*bersih bersih pesan*/
                        $pesan = strtoupper($message['text']);
                        $pesan = str_replace('A/N', 'AN', $pesan);
                        $pesan = str_replace('NAMA', 'AN', $pesan);

                        preg_match('/ORDER\s{0,1}:(.+)/i', $pesan, $ja);
                        preg_match('/AN\s{0,1}:(.+)/i', $pesan, $an);
                        preg_match('/ALAMAT\s{0,1}:(.+)/i', $pesan, $alamat);
                        preg_match('/CP\s{0,1}:(.+)/i', $pesan, $cp);
                        preg_match('/INET\s{0,1}:(.+)/i', $pesan, $inet);
                        preg_match('/ODP\s{0,1}:(.+)/i', $pesan, $odp);
                        preg_match('/PORT\s{0,1}:(.+)/i', $pesan, $port);
                        preg_match('/SN\s{0,1}:(.+)/i', $pesan, $sn);
                        preg_match('/SC\s{0,1}:(.+)/i', $pesan, $sc);
                        preg_match('/DC\s{0,1}:(.+)/i', $pesan, $dc);
                        preg_match('/OLT\s{0,1}:(.+)/i', $pesan, $olt);
                        preg_match('/IP OLT\s{0,1}:(.+)/i', $pesan, $ip_olt);
                        preg_match('/METRO\s{0,1}:(.+)/i', $pesan, $metro);
                        preg_match('/PORT UPLINK\s{0,1}:(.+)/i', $pesan, $uplink);
                        preg_match('/INTERFACE OLT\s{0,1}:(.+)/i', $pesan, $int_olt);
                        preg_match('/VLAN\s{0,1}:(.+)/i', $pesan, $vlan);
                        preg_match('/KET\s{0,1}:(.+)/i', $pesan, $ket);

                        $ja         = (trim($ja[1]) != '' ? trim($ja[1]) : '-');
                        $ja         = str_replace("JA", "", $ja);
                        $an         = (trim($an[1]) != '' ? trim($an[1]) : '-');
                        $alamat     = (trim($alamat[1]) != '' ? trim($alamat[1]) : '-');
                        $cp         = (trim($cp[1]) != '' ? trim($cp[1]) : '-');
                        $inet       = (trim($inet[1]) != '' ? trim($inet[1]) : '-');
                        $odp        = (trim($odp[1]) != '' ? trim($odp[1]) : '-');
                        $port       = (trim($port[1]) != '' ? trim($port[1]) : '-');
                        $sn         = (trim($sn[1]) != '' ? trim($sn[1]) : '-');
                        $sc         = intval(preg_replace('/[^0-9]+/', '', trim($sc[1])), 10);
                        $dc         = (trim($dc[1]) != '' ? trim($dc[1]) : '-');
                        $olt        = (trim($olt[1]) != '' ? trim($olt[1]) : '-');
                        $ip_olt     = (trim($ip_olt[1]) != '' ? trim($ip_olt[1]) : '-');
                        $metro      = (trim($metro[1]) != '' ? trim($metro[1]) : '-');
                        $uplink     = (trim($uplink[1]) != '' ? trim($uplink[1]) : '-');
                        $int_olt    = (trim($int_olt[1]) != '' ? trim($int_olt[1]) : '-');
                        $vlan       = (trim($vlan[1]) != '' ? trim($vlan[1]) : '-');
                        $ket        = (trim($ket[1]) != '' ? trim($ket[1]) : '-');
                        // variable pelengkap
                        $odp        = str_replace(' ', '', $odp);
                        $tgl        = date('Y-m-d H:i:s');
                        $post_name  = $message["from"]["username"];
                        $m_id       = $message["message_id"];
                        $g_id       = $chatid;

                        $datel = getDatel($odp);

                        $cekut      = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE t_telegram_id = '$fromid'");
                        $cekdata    = mysqli_query($koneksi, "SELECT sales_id,status_id FROM tb_wms_provisioning WHERE sales_id = '$ja'");
                        $cekscsales = mysqli_query($koneksi, "SELECT sales_id,new_sc,message_from,message_id FROM tb_sales WHERE new_sc = '$sc'");
                        $cekja      = mysqli_query($koneksi, "SELECT sales_id,new_sc,message_from,message_id FROM tb_sales WHERE sales_id = '$ja'");
                        $jadwal_sholat  = file_get_contents("https://api.banghasan.com/sholat/format/json/jadwal/kota/723/tanggal/" . date('Y-m-d') . "");
                        $json           = json_decode($jadwal_sholat, TRUE);
                        $tanggal  =  $json['jadwal']['data']['tanggal'];
                        $ashar    =  str_replace(':', '', $json['jadwal']['data']['ashar']);
                        $dzuhur   =  str_replace(':', '', $json['jadwal']['data']['dzuhur']);
                        $isya     =  str_replace(':', '', $json['jadwal']['data']['isya']);
                        $maghrib  =  str_replace(':', '', $json['jadwal']['data']['maghrib']);
                        $subuh    =  str_replace(':', '', $json['jadwal']['data']['subuh']);

                        if (mysqli_num_rows($cekdata) > 0) {
                            while ($d = mysqli_fetch_array($cekdata)) {
                                $text = "JA $ja sudah ada, statusnya : " . statusSC($d['status_id']) . "";
                            }
                        } else {
                            if ($ja == '-') {
                                $text = "⚠️ Nomor JA kosong! Isi dengan *ORDER : NO_JA (misal 123456)*";
                            } elseif (strlen($odp) < 14 || strlen($odp) > 15) {
                                $text = "⚠️ ODP tidak valid! tulis ODP dengan ODP : ODP-PKL-FAA/001";
                            } elseif (strpos($odp, '/') == false) {
                                $text = "tanda / nya mana? misal : ODP-PKL-FAA/001";
                            } else {
                                $query = mysqli_query($koneksi, "INSERT INTO tb_wms_provisioning (datel, atas_nama, alamat, cp, internet, odp, port, sn, dc, olt, ip_olt, metro, port_uplink, int_olt, vlan, ket_order, post_by, group_id, message_id, tgl_masuk, sales_id) VALUES ('$datel', '$an', '$alamat', '$cp', '$inet', '$odp', '$port', '$sn', '$dc', '$olt', '$ip_olt', '$metro', '$uplink', '$int_olt', '$vlan', '$ket', '$fromid', '$g_id','$m_id', '$tgl', '$ja')");
                                $idcreate = mysqli_insert_id($koneksi);
                                if ($query) {
                                    if (date("Hi") > "$dzuhur" && date("Hi") < "1235" && date("N") == 5) {
                                        $text = "📢 ayoo sholatt jum'at dulu 🕌";
                                    } elseif (date("Hi") > "$dzuhur" && date("Hi") < "1220") {
                                        $text = "📢 sudah masuk waktu dhuhur.. yuk sholat dulu ya 🕌 \n";
                                    } else if (date("Hi") > "$ashar" && date("Hi") < "1530") {
                                        $text = "📢 sudah masuk waktu ashar.. yuk sholat dulu ya 🕌";
                                    } else if (date("Hi") > "1800") {
                                        $text = "lanjut besok lagi ya.. langsung pulang kerumah, hindari kerumunan, jangan lupa mandi sebelum berinteraksi dengan keluarga anda dirumah :)";
                                    } else if (date("Hi") > "$maghrib" && date("Hi") < "1820") {
                                        $text = "📢 sudah masuk waktu maghrib.. yuk sholat dulu ya 🕌";
                                    } else if (date("Hi") > "$isya" && date("Hi") < "2000") {
                                        $text = "📢 sudah masuk waktu isya.. yuk sholat dulu ya 🕌";
                                    } else {
                                        $text  = "yuk lanjut rekan HD 🤙";
                                    }

                                    while ($d = mysqli_fetch_array($cekja)) {
                                        $sales_id = $d['sales_id'];
                                        $telegram_id = -273243235;
                                        $s_m_id = $d['message_id'];
                                        $ambilt = mysqli_query($koneksi, "SELECT nama_teknisi FROM tb_teknisi WHERE t_telegram_id = '$fromid'");
                                        while ($t = mysqli_fetch_array($ambilt)) {
                                            $savelog = mysqli_query($koneksi, "INSERT INTO tb_log (sales_id, action_by, action_on, action_status) VALUES ('$sales_id','$t[nama_teknisi]','$tgl',7)");
                                            $udatasales = mysqli_query($koneksi, "UPDATE tb_sales SET `status` = 'donelme', status_id = '7'  WHERE sales_id='$sales_id'");
                                            $pesantosales = "⚠️ Order telah selesai LME oleh $t[nama_teknisi], Silahkah rekan inputer untuk di input SC nya";
                                            sendApiMsg($telegram_id, $pesantosales, $s_m_id, 'Markdown');
                                        }
                                    }
                                    // if (mysqli_num_rows($cekut)>0){

                                    // }
                                    // else{
                                    //     $text = '❗️ Kamu belum mendaftar sebagai teknisi pada @damanasisten_bot. silahkan lakukan pendaftaran dengan klik @damanasisten_bot kemudian ketik /regteknisi atau /help untuk infromasi bantuan.';
                                    // }
                                } else {
                                    $text  = "Error! Data Gagal Disimpan!\n";
                                    $text .= "Datel : $datel\n";
                                    $text .= "Error : " . mysqli_error($koneksi);
                                }
                            }
                        }
                    } else {
                        $text = 'Maaf fitur dikunci, hanya untuk grup terdaftar!';
                    }
                    sendApiMsg($chatid, $text, $meid);

                    break;

                default:

                    break;
            }
        }
    }
}
