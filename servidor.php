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
	$search = $_GET["search"];
	if (trim($search) == ""){
		return "null";
	}
	$mysqli = new mysqli('127.0.0.1', 'root', '', 'rss');
	$cadena = "";
	if(!$mysqli){
		return false; 
	} else{
		$sql = "SELECT * FROM `feeds` WHERE MATCH (nombreAutor,descripcion,titulo)
		AGAINST ('+$search*' IN BOOLEAN MODE)";
		$resultado = $mysqli->query($sql);
		$numeroDeFeeds = $resultado->num_rows;
		if ($numeroDeFeeds > 0){
			$arrayName = array();
			for ($i=0; $i < $numeroDeFeeds; $i++) { 
				$arrayName[] = $resultado->fetch_array(MYSQLI_ASSOC);
			}
			$cadena = json_encode($arrayName);
		} else {
			return noHayFeeds();
		}
	}

	return $cadena;
}


function noHayFeeds (){
	$cadena = "{}";
	return $cadena;
}

function almacenarBaseDatos($feed){
	$mysqli = new mysqli('127.0.0.1', 'root', '', 'rss');
	if(!$mysqli){
		return false; 
	}
	else{
		$sql = "SELECT idFeed FROM `feeds`  ORDER BY idFeed DESC LIMIT 0, 1";
		$resultado = $mysqli->query($sql);
		if ($resultado->num_rows > 0){
			$fila =  $resultado->fetch_array(MYSQLI_ASSOC);
			$idFeed = $fila["idFeed"] + 1;
		} else {
			$idFeed = 1;
		}
		foreach ($feed->get_items(0, 0) as $item) {
			
			$url = str_replace("'", "", $item->get_link());
			$titulo = str_replace("'", "",$item->get_title());
			$nombreAutor= str_replace("'", "",$item->get_author()->get_name());
			$fecha = $item->get_date('Y-m-d');
			$descripcion=obtenerURLImagen(str_replace("'", "", $item->get_description()),$idFeed);
			$sql = "INSERT INTO `feeds`(`idFeed` ,`URL`, `titulo`, `nombreAutor`, `descripcion`, `fecha`) VALUES ($idFeed,'$url','$titulo','$nombreAutor','$descripcion','$fecha')";
			$resultado = $mysqli->query($sql);
			if ($resultado){
				//echo "El registro ".$idFeed." se ha logrado <br>";
			} else {
				//echo "$sql <br>";
			}
			$idFeed++;
		}

		
		$mysqli->close();              
	}
}

function obtenerURLImagen($descripcion, $idFeed){
	$nuevaDescripcion = "";
	$descripcionDetallada = explode("<", $descripcion);
	$etiquetaImagen = "";
	$existe = false;
	for ($i = 0; $i < count($descripcionDetallada); $i++){
		$mystring = $descripcionDetallada[$i];
		$findme   = 'img src';
		$pos = strpos($mystring, $findme);
		if (!($pos === false)) {
			$etiquetaImagen = $mystring;
			$existe = true;
			break;
		}
	}

	if ($existe){
		//echo $etiquetaImagen;
		$url = explode('"', $etiquetaImagen)[1];
		//echo $url;
		$arregloTemporal = explode(".", $url);
		$extension = $arregloTemporal[count($arregloTemporal) - 1];
		//echo $extension;
		descargarImagenes($url,$idFeed,$extension);

		$nuevaURL = "https://localhost/RSS/RecursosFeeds/".$idFeed.".".$extension;
		$etiquetaImagenNueva = str_replace($url,$nuevaURL, $etiquetaImagen);
		//echo $etiquetaImagenNueva."<br>".$etiquetaImagen;
		$nuevaDescripcion = str_replace($etiquetaImagen,$etiquetaImagenNueva,$descripcion);
		//echo $nuevaDescripcion;
		return $nuevaDescripcion;
	} else {
		return $descripcion;
	}

}


function descargarImagenes($urlImagen, $idFeed,$extension){
	$img = file_get_contents($urlImagen);
	file_put_contents($_SERVER['DOCUMENT_ROOT']."/RSS/RecursosFeeds/".$idFeed.".".$extension, $img);
}

function obtenerRSSRecientes($feed){
	$mysqli = new mysqli('127.0.0.1', 'root', '', 'rss');
	if(!$mysqli){
		return false; 
	}
	else{
		$cadena = "";
		$sql = "SELECT * FROM `feeds`  ORDER BY fecha DESC LIMIT 0, 1";
		$resultado = $mysqli->query($sql);
		$numeroDeFeeds = $resultado->num_rows;
		if ($numeroDeFeeds > 0){
			$arrayName = array();
			for ($i=0; $i < $numeroDeFeeds; $i++) { 
				$arrayName[] = $resultado->fetch_array(MYSQLI_ASSOC);
			}
			$cadena = json_encode($arrayName);
			return $cadena;
		} else {
			return noHayFeeds();
		}
	}
}

//echo obtenerRSS($feed);
//almacenarBaseDatos($feed);
//echo $_SERVER['DOCUMENT_ROOT']."RSS/";
if(isset($_GET['tipo']) && !empty($_GET['tipo'])) {
	$action = $_GET['tipo'];
	switch($action) {
		case "obtener" : echo obtenerRSS($feed);break;
		case "cargar" : echo almacenarBaseDatos($feed);break;
		case "all" : echo obtenerRSSRecientes($feed);break;
	}
}
