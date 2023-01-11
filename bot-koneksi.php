<?php

$koneksi = mysqli_connect("127.0.0.1:3306", "jarvisakses", "Jarvis135!", "db_pkl_sales");

// Check connection
if (mysqli_connect_errno()) {
	echo "Koneksi database gagal : " . mysqli_connect_error();
}
