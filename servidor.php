<?php

require_once './vendor/simplepie/simplepie/autoloader.php';
//$url = 'http://rss.nytimes.com/services/xml/rss/nyt/HomePage.xml';
$url = 'http://gizmodo.com/rss';
$feed = new SimplePie();
$feed->set_feed_url($url);
$feed->enable_cache();
$feed->set_cache_location($_SERVER['DOCUMENT_ROOT'] . '/RSS');
$feed->init();


//$item = $feed->get_item(0);
function obtenerRSS($feed){
	$cadena = "";
	foreach ($feed->get_items(0, 0) as $item) {
		$cadena .= '<div class="panel panel-default">';

		$cadena .= '<div class="panel-heading">';
		$cadena .= '<a href="' . $item->get_link() . '">' . $item->get_title() . '</a></div>';
		$cadena .= '<div class="panel-body">';
		$cadena .= '<p>Author: ' . $item->get_author()->get_name() . '</p>';
		$cadena .= '<p>Date: ' . $item->get_date('Y-m-d H:i:s') . '</p>';
		$cadena .= '<p>' . $item->get_description() . '</p>';
		$cadena .= "</div>";
		$cadena .= $item->get_content(true);
		$cadena .= "</div>";
	}

	return $cadena;
}

function almacenarBaseDatos($feed){
	$mysqli = new mysqli('127.0.0.1', 'root', '', 'rss');
	if(!$mysqli){
		return false; 
	}
	else{
		foreach ($feed->get_items(0, 0) as $item) {
			$url = $item->get_link();
			$titulo = $item->get_title();
			$nombreAutor= $item->get_author()->get_name();
			$fecha = $item->get_date('Y-m-d');
			$descripcion=$item->get_description();
			$cadena = $item->get_content(true);

			$sql = "";
			$resultado = $mysqli->query($sql);
		}

		
		$mysqli->close();              
	}
}

//echo obtenerRSS($feed);

if(isset($_GET['tipo']) && !empty($_GET['tipo'])) {
	$action = $_GET['tipo'];
	switch($action) {
		case "obtener" : echo obtenerRSS($feed);break;
	}
}
