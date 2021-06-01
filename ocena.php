<?php

if(isset($_GET["lubienie"]))
{
  require_once 'dbconnect.php';
  $polaczenie = new mysqli($host, $user, $password);

  if ($polaczenie->connect_errno != 0)
  {
    echo "Error: ".$polaczenie->connect_errno." Opis: ".$polaczenie->connect_error;
  }
  else
  {
  mysqli_select_db($polaczenie, $database);

  $polubienia = json_decode($_GET['ilosc'], true);
  $id = json_decode($_GET['id'], true);
  $polubienia = $polubienia + 1;
  $polaczenie->query("UPDATE filmiki SET polubienia = $polubienia WHERE id='$id'");
  echo "wszystko git";

  $polaczenie->close();
  }
}

if(isset($_GET["nielubienie"]))
{
  require_once 'dbconnect.php';
  $polaczenie = new mysqli($host, $user, $password);

  if ($polaczenie->connect_errno != 0)
  {
    echo "Error: ".$polaczenie->connect_errno." Opis: ".$polaczenie->connect_error;
  }
  else
  {
  mysqli_select_db($polaczenie, $database);

  $nielubienia = json_decode($_GET['ilosc'], true);
  $id = json_decode($_GET['id'], true);
  $nielubienia = $nielubienia + 1;
  $polaczenie->query("UPDATE filmiki SET nielubienia = $nielubienia WHERE id='$id'");
  echo "wszystko git";

  $polaczenie->close();
  }
}
?>