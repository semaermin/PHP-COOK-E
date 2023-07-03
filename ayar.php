<?php

error_reporting(1);

try {
  $baglan = new PDO("mysql:host=localhost;dbname=ismek;charset=utf8", "root", "");
  $baglan->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $baglan->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $baglan->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
  $baglan->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (Exception $e) {
  echo $e->getMessage();
  die();
}

?>