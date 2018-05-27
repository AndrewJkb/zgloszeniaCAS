function wyslijPlik() {
	var plik=document.getElementById("plik").files[0];
	
	var formularz=new FormData(); //tworzymy nowy formularz do wysłania
	formularz.append("plik", plik); //dodajemy do formularza pole z naszym plikiem
	
	/* wysyłamy formularz za pomocą AJAX */
	var xhr=new XMLHttpRequest();
	xhr.upload.addEventListener("progress", postepWysylania, false);
	xhr.addEventListener("load", zakonczenieWysylania, false);
	xhr.addEventListener("error", bladWysylania, false);
	xhr.addEventListener("abort", przerwanieWysylania, false);
	xhr.open("POST", "./upload.php", true);
	xhr.send(formularz);
}

function postepWysylania(event) {
	var procent=Math.round((event.loaded/event.total)*100);
	document.getElementById("status").innerHTML="Wysłano "+konwersjaBajtow(event.loaded)+" z "+konwersjaBajtow(event.total)+" ("+procent+"%)";
	document.getElementById("postep").value=procent;
}

function zakonczenieWysylania(event) {
	document.getElementById("status").innerHTML=event.target.responseText;
}

function bladWysylania(event) {
	document.getElementById("status").innerHTML="Wysyłanie nie powiodło się";
}

function przerwanieWysylania(event) {
	document.getElementById("status").innerHTML="Wysyłanie zostało przerwane";
}

function konwersjaBajtow(bajty) {
	var kilobajt=1024;
	var megabajt=kilobajt*1024;
	var gigabajt=megabajt*1024;
	var terabajt=gigabajt*1024;
	
	if (bajty>=0 && bajty<kilobajt) return bajty+" B";
	else if(bajty>=kilobajt && bajty<megabajt) return Math.round(bajty/kilobajt)+" kB";
	else if(bajty>=megabajt && bajty<gigabajt) return Math.round(bajty/megabajt)+" MB";
	else if(bajty>=gigabajt && bajty<terabajt) return Math.round(bajty/gigabajt)+" GB";
	else if(bajty>=terabajt) return Math.round(bajty/terabajt)+" TB";
	else return bajty+" B";
}