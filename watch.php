<!DOCTYPE html>
<html lang="pl">
<head>
<title>Śmieszne Koty</title>
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
  width: 15%;
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

.komentarz{
  margin-top: 25px;
  background-color:blue;
  margin-left: 5px;
}

#polecane{
  float: right;
  height: 1070px;
  width: 280px;
}

.content{
  width:260px;
  height: 150px;
  float:left;
  margin-bottom:65px;
  margin-left:25px;
  float: right;
}

.opis{
  background-color:blue;
}

form {
  padding: 15px;
  border: 1px solid #666;
  background: blue;
  display: none;
}
</style>

<?php 
require_once 'dbconnect.php';
$polaczenie = new mysqli($host, $user, $password);
if ($polaczenie->connect_errno != 0)
{
  echo "Error: ".$polaczenie->connect_errno." Opis: ".$polaczenie->connect_error;
}
else
{
  mysqli_query($polaczenie, "SET CHARSET utf8");
  mysqli_query($polaczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");

  mysqli_select_db($polaczenie, $database);

  $aktualny_id = $_GET['id'];

  $sql = "SELECT * FROM filmiki WHERE id='$aktualny_id'";
  $rezultat = $polaczenie->query($sql);
  $aktualny_filmik = $rezultat->fetch_assoc();

  $aktualna_nazwa = $aktualny_filmik['nazwa'];
  $aktualne_wyswietlenia = $aktualny_filmik['wyswietlenia'];

  // Dodawanie wyświetleń
  $aktualne_wyswietlenia = $aktualne_wyswietlenia + 1;
  $polaczenie->query("UPDATE filmiki SET wyswietlenia = '$aktualne_wyswietlenia' WHERE id='$aktualny_id'");

  $polubienia = $aktualny_filmik['polubienia'];
  $nielubienia = $aktualny_filmik['nielubienia'];

  echo<<<END
  <div id=container>

  <div id=menu>
  <div><a href="/"><h2 id="logo">Śmieszne Koty</h2></a></div>
    <br>
    <div id="list">
      <h4>*Strona poświęcona nauce języka PHP*</h4>
      <h4>*Przy tworzeniu strony największą uwagę zwracałem na funkcje, a nie na wygląd.*</h4>
    </div>
  </div>
  <div id="contents">

  <div style="float: left; margin-top:-62px;">
    <video width="1300" height="800" controls>
    <source src="filmiki/$aktualny_id.mp4" type="video/mp4">
    </video>
    <div><h3>$aktualna_nazwa<h3></div>
    <div><h3>Wyświetlenia: $aktualne_wyswietlenia<h3></div>
    <div onclick="polubienie($polubienia, $aktualny_id, $nielubienia)">
      Polubienia:
      <div id="ilosc_polubien">$polubienia</div>
    </div>
    <div onclick="nielubienie($nielubienia, $aktualny_id, $polubienia)">
      Nielubienia:
      <div id="ilosc_nielubien">$nielubienia</div>
    </div>
  
  <h3>Komentarze:</h3>
  <button type="button" id="nowy_komentarz">Napisz komentarz</button>
  <form name="formularz">
  <b>Twój nick:</b><br><input type="text" name="nick"><br><br>
  <textarea name="tresc">Twój komentarz:</textarea><br>
  <button type="button" id="submit" onclick="komentarz($aktualny_id)">Zatwierdź</button>
  </form>

  <div id="nowy"></div>
  END;
  
  $sql = "SELECT * FROM komentarze WHERE id_filmu='$aktualny_id'";
  
  $rezultat = mysqli_query($polaczenie, $sql);
  $ile = mysqli_num_rows($rezultat);
  
  for ($i = 1; $i <= $ile; $i++) 
  {
    
    $row = mysqli_fetch_assoc($rezultat);
    $id = $row['id'];
    $tresc = $row['tresc'];
    $tworca = $row['tworca'];
    
  echo<<<END
  <div class="komentarz">
  $tworca
  <br>
  $tresc
  </div>
  END;
  }

echo '<div style="clear:both;"></div></div><div id="polecane">';

  $sql = "SELECT * FROM filmiki";
  
  $rezultat = mysqli_query($polaczenie, $sql);

  for ($i = 1; $i <= 5; $i++) 
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

  echo '</div>';
$polaczenie->close();
}

?>
<script>
$(document).ready(function() {
  $("#nowy_komentarz").click(function() {
    $("form[name='formularz']").toggle();
  });
});

function wyswietl_komentarz(nick, tresc){
  $('#nowy').append("<div class='komentarz'>"+nick+"<br>"+tresc+"</div>")
}

function komentarz(id){
  var nick =  $("input[name='nick']").val();
  var tresc =  $("textarea[name='tresc']").val();
  $.ajax({
      type: "GET",
      url: "/komentarz.php",
      data: "id="+id+"&nick="+nick+"&tresc="+tresc,
      processData: false,
      contentType: false,
      error: function(jqXHR, textStatus, errorMessage) {
          console.log(errorMessage);
      },
      success: function(data) {console.log(data)} 
  });
  $("form[name='formularz']").toggle();
  wyswietl_komentarz(nick, tresc);
}

var pierwszy_raz_l = true;

function polubienie(ilosc, id, ilosc_przeciwna){
  var lista = {"ilosc": ilosc, "id": id}
  $.ajax({
    type: "GET",
    url: "ocena.php",
    data: {"ilosc":ilosc, "id":id, "lubienie": "lubienie"}
  }).done(function() {
    if (pierwszy_raz_n && pierwszy_raz_l){
    document.getElementById('ilosc_polubien').innerHTML=ilosc+1;
    pierwszy_raz_l = false;
    }
    else if(pierwszy_raz_l && !pierwszy_raz_n){
      document.getElementById('ilosc_nielubien').innerHTML=ilosc_przeciwna;
      document.getElementById('ilosc_polubien').innerHTML=ilosc+1;
      pierwszy_raz_n = false;
      pierwszy_raz_l = false;
      }
    else {
    document.getElementById('ilosc_polubien').innerHTML=ilosc;
    pierwszy_raz_l = true;
    }
  })};

  var pierwszy_raz_n = true;

  function nielubienie(ilosc, id, ilosc_przeciwna){
  var lista = {"ilosc": ilosc, "id": id}
  $.ajax({
    type: "GET",
    url: "ocena.php",
    data: {"ilosc":ilosc, "id":id, "nielubienie": "nielubienie"}
  }).done(function() {
    if (pierwszy_raz_n && pierwszy_raz_l){
    document.getElementById('ilosc_nielubien').innerHTML=ilosc+1;
    pierwszy_raz_n = false;
    }
    else if(pierwszy_raz_n && !pierwszy_raz_l){
      document.getElementById('ilosc_polubien').innerHTML=ilosc_przeciwna;
      document.getElementById('ilosc_nielubien').innerHTML=ilosc+1;
      pierwszy_raz_n = false;
      pierwszy_raz_l = false;
      }
    else {
    document.getElementById('ilosc_nielubien').innerHTML=ilosc;
    pierwszy_raz_n = true;
    }
  })};
</script>
</body>
</html>