<!DOCTYPE html>
<html lang="pl">
<head>
<title>Śmieszne Koty</title>
</head>
<body>

<style>
body{
  background-color: navy;
  color: white;
}

#container{
  display: flex;
}

#menu{
  float:left;
  background-color:blue;
  width: 20%;
}

#logo{
  margin-left:3px;
  text-decoration: none;
  color: white;
}

#list{
  margin-left:3px;
}

#contents{
  float:left;
}

.content{
  margin: 10px;
  width:230px;
  height: 180px;
  float:left;
  margin-bottom:45px;
}

.opis{
  background-color:blue;
}
</style>

<?php 
ini_set("display_errors", 0);
require_once 'dbconnect.php';
$polaczenie = mysqli_connect($host, $user, $password);
mysqli_query($polaczenie, "SET CHARSET utf8");
mysqli_query($polaczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
mysqli_select_db($polaczenie, $database);

$zapytanietxt = "SELECT * FROM filmiki";

$rezultat = mysqli_query($polaczenie, $zapytanietxt);
$ile = mysqli_num_rows($rezultat);

if ($ile>=1) 
{
echo<<<END
<div id=container>

<div id=menu>
<div><a href="/"><h2 id="logo">Śmieszne Koty</h2></a></div>
  <br>
  <div id="list">
    <h4>Obejrzane</h4>
	<h4>Najpopularniejsze</h4>
	<h4>Losowo</h4>
  </div>
</div>
<div id="contents">
END;
}
	for ($i = 1; $i <= $ile; $i++) 
	{
		
		$row = mysqli_fetch_assoc($rezultat);
		$id = $row['id'];
		$nazwa = $row['nazwa'];
		$wyswietlenia = $row['wyswietlenia'];
		
echo<<<END
<div class="content">
<a href="/watch.php?id=$id">
  <img class="obrazek" 
  src='zdjecia/$id.jpg'
  onmouseout="this.src='zdjecia/$id.jpg'" 
  onmouseover="this.src='gify/$id.gif'"  height="100%" width="100%"> 
</a>
 <div class="opis">$nazwa<br>Wyświetlenia:$wyswietlenia</div>
</div>
END;		
	}
	
?>






</body>
</html>