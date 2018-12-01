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
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="./ejemplo.css">
	<title>¡Titulares!</title>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">Periodico</a>
			</div>
			<ul class="nav navbar-nav">
				<li class="active"><a href="#">Home</a></li>
			</ul>
		</div>
	</nav>
	<div class="container opa" align="center">
		<div class="panel-group">
			<?php

			foreach ($feed->get_items(0, 0) as $item) {
				echo '<div class="panel panel-default">';
				
				echo '<div class="panel-heading uwu">';
				echo '<a href="' . $item->get_link() . '">' . $item->get_title() . '</a></div>';
				echo '<div class="panel-body">';
				echo '<p>Author: ' . $item->get_author()->get_name() . '</p>';
				echo '<p>Date: ' . $item->get_date('Y-m-d H:i:s') . '</p>';
				echo '<p>' . $item->get_description() . '</p>';
				echo "</div>";
				//echo $item->get_content(true);
				echo "</div>";
			}

			?>
		</div>
	</div>

</body>

</html>

