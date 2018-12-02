// Copyright 2018 The Chromium Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can be
// found in the LICENSE file.


document.getElementById("button").addEventListener("click", sendSearch);

function sendSearch (){
	var search = document.getElementById("search").value;
	
	var campoVacio = verificarDatos(search);

	if (campoVacio){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				if (this.responseText !== "null"){
					generarFeeds(this.responseText);
				} else {
					alert("Ingrese una palabra de busqueda valida.");
				}
				
			}
		};
		xmlhttp.open("GET", "https://localhost/RSS/servidor.php?tipo=obtener&search="+ search, true);
		xmlhttp.send();
	} else {
		alert("Ingrese una palabra de busqueda valida.");
	}
	0
	
}

function searchAll (){
	
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			generarFeeds(this.responseText);
		}
	};
	xmlhttp.open("GET", "https://localhost/RSS/servidor.php?tipo=all", true);
	xmlhttp.send();
	
}

function verificarDatos (search){
	if (search !== ""){
		return true;
	}	else {
		return false;
	}
}
searchAll ();
function generarFeeds (texto){
	if (texto !== "{}"){
		var jsonInfo = JSON.parse(texto);
	}
}


