// Copyright 2018 The Chromium Authors. All rights reserved.
// Use of this source code is governed by a BSD-style license that can be
// found in the LICENSE file.


document.getElementById("button").addEventListener("click", sendSearch);

function sendSearch (){
	var search = document.getElementById("search").value;
	
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("rss").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET", "https://localhost/RSS/servidor.php?tipo=obtener&search="+ search, true);
	xmlhttp.send();
	
}

sendSearch ();
function generarFeeds (texto){

}


