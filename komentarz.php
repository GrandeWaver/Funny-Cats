<?php

if(isset($_GET['id']))
{
    $id = $_GET['id'];
    $nick = $_GET['nick'];
    $tresc = $_GET['tresc'];
    echo $nick;
    echo '<br>';
    echo $tresc;
    echo '<br>';
    echo $id;

    require_once 'dbconnect2.php';
    $polaczenie = new mysqli($host, $user, $password);
    if ($polaczenie->connect_errno != 0)
    {
    echo "Error: ".$polaczenie->connect_errno." Opis: ".$polaczenie->connect_error;
    }
    else
    {
    mysqli_select_db($polaczenie, $database);

    $polaczenie->query("INSERT INTO komentarze (id_filmu, tresc, tworca) VALUES ('$id', '$tresc', '$nick')");
    echo "<br>wszystko git";

    $polaczenie->close();
    }
}
?>