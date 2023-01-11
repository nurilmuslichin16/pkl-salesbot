<?php

define('HS', true);

/*
 *  Programmer  : Abdul Hakim Hassan
 *  Email       : abdulhakimhsn@gmail.com
 *  Telegram    : @abdulhakimhs
 *
 *  Pembuatan   : November 2019
 *
 *  ____________________________________________________________
*/


require_once 'bot-api-config.php';
require_once 'bot-api-fungsi.php';

require_once 'bot-api-proses.php';



$entityBody = file_get_contents('php://input');
$message = json_decode($entityBody, true);
prosesApiMessage($message);


// Telegram by: banghasan @hasanudinhs @myqers;
