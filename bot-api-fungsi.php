<?php


if (!defined('HS')) {
    die('Tidak boleh diakses langsung.');
}

function myPre($value)
{
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}

function apiRequest($method, $data)
{
    if (!is_string($method)) {
        error_log("Nama method harus bertipe string!\n");

        return false;
    }

    if (!$data) {
        $data = [];
    } elseif (!is_array($data)) {
        error_log("Data harus bertipe array\n");

        return false;
    }


    $url = 'https://api.telegram.org/bot' . $GLOBALS['token'] . '/' . $method;

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context = stream_context_create($options);

    $result = file_get_contents($url, false, $context);

    return $result;
}

function getApiUpdate($offset)
{
    $method = 'getUpdates';
    $data['offset'] = $offset;

    $result = apiRequest($method, $data);

    $result = json_decode($result, true);
    if ($result['ok'] == 1) {
        return $result['result'];
    }

    return [];
}

function sendApiMsg($chatid, $text, $msg_reply_id = false, $parse_mode = false, $disablepreview = false)
{
    $method = 'sendMessage';
    $data = ['chat_id' => $chatid, 'text'  => $text];
    if ($msg_reply_id) {
        $data['reply_to_message_id'] = $msg_reply_id;
    }
    if ($parse_mode) {
        $data['parse_mode'] = $parse_mode;
    }
    if ($disablepreview) {
        $data['disable_web_page_preview'] = $disablepreview;
    }

    $result = apiRequest($method, $data);
}

function sendApiImg($chat_id, $text, $img)
{
    $bot_url    = "https://api.telegram.org/bot889705435:AAHn3CpJQLhGktaxJUGwZol1kbTVTMQa6qs/";
    $url        = $bot_url . "sendPhoto?chat_id=" . $chat_id;
    $post_fields = array(
        'chat_id' => $chat_id,
        'caption' => 'Last Update ' . $text . ' on ' . date('Y-m-d H:i:s') . '',
        'photo'   => new CURLFile(realpath("/home/jarvisid/newjarvis.jarvisid.com/tmp/" . $img . ".png"))
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type:multipart/form-data"
    ));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    $output = curl_exec($ch);
}

function sendApiMsgReply($chatid, $text, $msg_reply_id = false, $parse_mode = false, $disablepreview = false, $replym = true)
{
    $method = 'sendMessage';
    $data = ['chat_id' => $chatid, 'text'  => $text];
    $replyMarkup = ['force_reply' => true];

    if ($msg_reply_id) {
        $data['reply_to_message_id'] = $msg_reply_id;
    }
    if ($parse_mode) {
        $data['parse_mode'] = $parse_mode;
    }
    if ($disablepreview) {
        $data['disable_web_page_preview'] = $disablepreview;
    }
    if ($replym) {
        $data['reply_markup'] = json_encode($replyMarkup);
    }

    $result = apiRequest($method, $data);
}


function sendApiAction($chatid, $action = 'typing')
{
    $method = 'sendChatAction';
    $data = [
        'chat_id' => $chatid,
        'action'  => $action,

    ];
    $result = apiRequest($method, $data);
}

function sendApiKeyboard($chatid, $text, $keyboard = [], $inline = false)
{
    $method = 'sendMessage';
    $replyMarkup = [
        'keyboard'        => $keyboard,
        'resize_keyboard' => true,
    ];

    $data = [
        'chat_id'    => $chatid,
        'text'       => $text,
        'parse_mode' => 'Markdown',

    ];

    $inline
        ? $data['reply_markup'] = json_encode(['inline_keyboard' => $keyboard])
        : $data['reply_markup'] = json_encode($replyMarkup);

    $result = apiRequest($method, $data);
}

function sendApiKeyboardMitra($chatid, $text, $keyboard = [], $inline = false)
{
    $method = 'sendMessage';
    $replyMarkup = [
        'keyboard'        => $keyboard,
        'force_reply'     => true,
        'resize_keyboard' => true,
    ];

    $data = [
        'chat_id'    => $chatid,
        'text'       => $text,
        'parse_mode' => 'Markdown',

    ];

    $inline
        ? $data['reply_markup'] = json_encode(['inline_keyboard' => $keyboard])
        : $data['reply_markup'] = json_encode($replyMarkup);

    $result = apiRequest($method, $data);
}


function editMessageText($chatid, $message_id, $text, $keyboard = [], $inline = false)
{
    $method = 'editMessageText';
    $replyMarkup = [
        'keyboard'        => $keyboard,
        'resize_keyboard' => true,
    ];

    $data = [
        'chat_id'    => $chatid,
        'message_id' => $message_id,
        'text'       => $text,
        'parse_mode' => 'Markdown',

    ];

    $inline
        ? $data['reply_markup'] = json_encode(['inline_keyboard' => $keyboard])
        : $data['reply_markup'] = json_encode($replyMarkup);

    $result = apiRequest($method, $data);
}

function sendApiHideKeyboard($chatid, $text)
{
    $method = 'sendMessage';
    $data = [
        'chat_id'       => $chatid,
        'text'          => $text,
        'parse_mode'    => 'Markdown',
        'reply_markup'  => json_encode(['hide_keyboard' => true]),

    ];

    $result = apiRequest($method, $data);
}

function sendApiSticker($chatid, $sticker, $msg_reply_id = false)
{
    $method = 'sendSticker';
    $data = [
        'chat_id'  => $chatid,
        'sticker'  => $sticker,
    ];

    if ($msg_reply_id) {
        $data['reply_to_message_id'] = $msg_reply_id;
    }

    $result = apiRequest($method, $data);
}

function strposa($haystack, $needle, $offset = 0)
{

    if (!is_array($needle)) $needle = array($needle);

    foreach ($needle as $query) {

        if (strpos($haystack, $query, $offset) !== false) return true;
    }

    return false;
}

function statusSC($int)
{
    if ($int == 1) {
        return 'waiting';
    } elseif ($int == 2) {
        return "OGP";
    } elseif ($int == 3) {
        return "FACT";
    } elseif ($int == 4) {
        return "ACT COMP";
    } elseif ($int == 5) {
        return "FDATA";
    } elseif ($int == 6) {
        return "LIVE";
    } elseif ($int == 7) {
        return "PS";
    }
}

function statusSales($int)
{
    if ($int == 1) {
        return 'SCBE';
    } elseif ($int == 2) {
        return "ORDERED";
    } elseif ($int == 3) {
        return "WAITSC";
    } elseif ($int == 4) {
        return "PROGFCC";
    } elseif ($int == 5) {
        return "DONESC";
    } elseif ($int == 6) {
        return "KENDALA";
    } elseif ($int == 7) {
        return "WAIT ACT";
    } elseif ($int == 8) {
        return "FALLOUT";
    } elseif ($int == 9) {
        return "COMP";
    } elseif ($int == 10) {
        return "PS";
    } elseif ($int == 11) {
        return "BLM DEPO";
    } elseif ($int == 12) {
        return "KENDALA SC";
    } elseif ($int == 13) {
        return "UNDER CONSTRUCTION";
    } elseif ($int == 14) {
        return "DONE INST AP";
    } elseif ($int == 15) {
        return "KENDALA CREATE";
    } elseif ($int == 41) {
        return "WAIT FCC";
    } elseif ($int == 42) {
        return "KENALA FCC";
    } elseif ($int == 43) {
        return "PROGRESS FCC";
    } elseif ($int == 71) {
        return "PROGRESS ACT";
    } elseif ($int == 31) {
        return "OGP SC";
    } elseif ($int == 32) {
        return "MENGOSONGKAN ODP";
    } elseif ($int == 33) {
        return "WAIT RISMA";
    } else {
        return $int;
    }
}

function getDatel($odp)
{
    if ((strpos($odp, 'BRB')) || (strpos($odp, 'BKA')) || (strpos($odp, 'BMU')) || (strpos($odp, 'KTM')) || (strpos($odp, 'TTL'))) {
        return 'BRB';
    } else if ((strpos($odp, 'BTG')) || (strpos($odp, 'BDY')) || (strpos($odp, 'SBA'))) {
        return 'BTG';
    } else if ((strpos($odp, 'PKL'))) {
        return 'PKL1';
    } else if ((strpos($odp, 'KJE')) || (strpos($odp, 'KDW'))) {
        return 'PKL2';
    } else if ((strpos($odp, 'PML')) || (strpos($odp, 'CMA')) || (strpos($odp, 'RDD'))) {
        return 'PML';
    } else if ((strpos($odp, 'SLW')) || (strpos($odp, 'ADW')) || (strpos($odp, 'BLU'))) {
        return 'SLW';
    } else if ((strpos($odp, 'MGN')) || (strpos($odp, 'TEG')) || (strpos($odp, 'TGL'))) {
        return 'TEG';
    } else {
        return 'none';
    }
}


function cekStoOdp($sto)
{
    if ($sto == 'BTG' || $sto == 'KJE' || $sto == 'CMA' || $sto == 'PML' || $sto == 'BKA' || $sto == 'SLW' || $sto == 'BDY' || $sto == 'ADW' || $sto == 'TEG' || $sto == 'MGN' || $sto == 'RDD' || $sto == 'PKL' || $sto == 'BRB' || $sto == 'SBA' || $sto == 'KDW' || $sto == 'KTM' || $sto == 'BLU' || $sto == 'BMU' || $sto == 'TTL') {
        return true;
    } else {
        return false;
    }
}

function cekJenisKendala($kendala)
{
    if ($kendala == 'RNA' || $kendala == 'ALAMAT' || $kendala == 'PENDING' || $kendala == 'BATAL' || $kendala == 'IJIN TANAM TIANG' || $kendala == 'NJKI') {
        return 'KENDALA PELANGGAN';
    } else {
        return 'KENDALA JARINGAN';
    }
}

function cekSegment($segment)
{
    if ($segment == 'DCS' || $segment == 'DBS' || $segment == 'DES' || $segment == 'DGS' || $segment == 'BGES') {
        return true;
    } else {
        return false;
    }
}

function send_broadcast_tl($datel, $unit)
{
    $chatid = '';
    if ($unit == 'DCS') {
        switch ($datel) {
            case 'BYL':
                $chatid = 81208861;
                break;
            case 'KLX':
                $chatid = 20960742;
                break;
            case 'PEN':
                $chatid = 20960742;
                break;
            case 'SOP':
                $chatid = 228089374;
                break;
            case 'GLD':
                $chatid = 103961214;
                break;
            case 'KRT':
                $chatid = 93677881;
                break;
            case 'KTO':
                $chatid = 110820236;
                break;
            case 'MSO':
                $chatid = 228089374;
                break;
            case 'SLO':
                $chatid = 59150217;
                break;
            case 'KAR':
                $chatid = 150320800;
                break;
            case 'SRG':
                $chatid = 150320800;
                break;
            case 'SKH':
                $chatid = 59150217;
                break;
            case 'WNG':
                $chatid = 1497805592;
                break;

            default:
                $chatid = -889675603;
                break;
        }
    } else {
        switch ($datel) {
            case 'BYL':
                $chatid = 81208861;
                break;
            case 'KLX':
                $chatid = 20960742;
                break;
            case 'PEN':
                $chatid = 20960742;
                break;
            case 'SOP':
                $chatid = 228089374;
                break;
            case 'GLD':
                $chatid = 103961214;
                break;
            case 'KRT':
                $chatid = 93677881;
                break;
            case 'KTO':
                $chatid = 110820236;
                break;
            case 'MSO':
                $chatid = 228089374;
                break;
            case 'SLO':
                $chatid = 59150217;
                break;
            case 'KAR':
                $chatid = 150320800;
                break;
            case 'SRG':
                $chatid = 150320800;
                break;
            case 'SKH':
                $chatid = 59150217;
                break;
            case 'WNG':
                $chatid = 1497805592;
                break;

            default:
                $chatid = -889675603;
                break;
        }
    }
    return $chatid;
}

function crewTeknisi($telegram_id_teknisi)
{
    include 'bot-koneksi.php';

    $id_teknisi = [];

    $cekTeknisi = mysqli_query($koneksi, "SELECT crew FROM tb_teknisi WHERE t_telegram_id = '$telegram_id_teknisi'");
    while ($t = mysqli_fetch_array($cekTeknisi)) {
        $crew = $t['crew'];
        $cekCrewTeknisi = mysqli_query($koneksi, "SELECT t_telegram_id FROM tb_teknisi WHERE crew = '$crew'");
        while ($c = mysqli_fetch_array($cekCrewTeknisi)) {
            array_push($id_teknisi, $c['t_telegram_id']);
        }
    }

    return $id_teknisi;
}
