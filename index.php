<?php
  ini_set('display_errors', 0);

  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST');
  header("Access-Control-Allow-Headers: X-Requested-With");
  $api_key = "xxx";
  require "simple_html_dom.php";
  function check_404($url) {
   $headers=get_headers($url, 1);
   if ($headers[0]!='HTTP/1.1 200 OK') return true; else return false;
  }
  function contains($needle, $haystack) {
    return strpos($haystack, $needle) !== false;
  }
  if ($_GET['id'] == "120089" && $_GET['s'] == "2") {
    die("50602");
  }
  else if ($_GET['id'] == "120089" && $_GET['s'] == "3") {
    die("53887");
  }
  if (check_404("https://api.themoviedb.org/3/tv/$_GET[id]/season/$_GET[s]?api_key=$api_key") && $_GET['s'] != "1") {
    $mal_id = file_get_contents("tm-to-mal/?id=$_GET[id]&s=1");
    $mal_html = file_get_html("https://myanimelist.net/anime/$mal_id/");
    $x = 0;
    $found = false;
    $fElement;
    foreach($mal_html->find(".ar.fw-n.borderClass") as $item) {
      if ($item->innertext() == "Sequel:") {
        $found = true;
        $fElement = explode("/", explode("/anime/", $item->parent()->find("td")[1]->find("a")[strval($_GET['s']) - 2]->href)[1])[0];
      }
      $x++;
    }
    if ($found == true) {
      die($fElement);
    }
    else {
      die("Warning: not found");
    }
  }
  $json = json_decode(file_get_contents("https://api.themoviedb.org/3/tv/$_GET[id]?api_key=$api_key"));
  $m = json_decode(file_get_contents("https://api.themoviedb.org/3/tv/$_GET[id]/season/$_GET[s]?api_key=$api_key"));
  $title = urlencode(str_replace("-", " ", $json->name));
  $date = $m->air_date;
  $month = explode("-", $date)[1];
  $year = explode("-", $date)[0];
  $day = explode("-", $date)[2];
  $yearAndMonth = $year.'-'.$month;
  $html = file_get_html("https://myanimelist.net/anime.php?cat=anime&q=$title&type=1&score=0&status=0&p=0&r=0&sm=$month&sd=$day&sy=$year&em=0&ed=0&ey=0&c%5B%5D=d");
  $i = -1;
  foreach($html->find('.borderClass.ac[width="80"]') as $item) {
    $i++;
    if (explode("-", $item->innertext())[0] == $month && explode("-", $item->innertext())[1] == $day) {
      die(explode("/", $html->find(".hoverinfo_trigger.fw-b.fl-l")[$i]->href)[4]);
    }
  }
  $i = -1;
  foreach($html->find('.borderClass.ac[width="80"]') as $item) {
    $i++;
    if (explode("-", $item->innertext())[0] == $month && explode("-", $item->innertext())[1] == ($day - 1)) {
      die(explode("/", $html->find(".hoverinfo_trigger.fw-b.fl-l")[$i]->href)[4]);
    }
  }
  $i = -1;
  foreach($html->find('.borderClass.ac[width="80"]') as $item) {
    $i++;
    if (explode("-", $item->innertext())[0] == $month && explode("-", $item->innertext())[1] == ($day + 1)) {
      die(explode("/", $html->find(".hoverinfo_trigger.fw-b.fl-l")[$i]->href)[4]);
    }
  }
  echo "Warning: not found";