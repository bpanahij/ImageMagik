var macroReqStart;
var macroReqEnd;
var activeMacro;

function startMacro() {
    if (window.XMLHttpRequest) {
	macroReqStart = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
	macroReqStart = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (macroReqStart != undefined) {
	var url = "startMacro.php?user_id="+userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
	macroReqStart.onreadystatechange = function() {startMacroDone();};
	macroReqStart.open("GET", url, true);
	macroReqStart.send("");
	//alert(url);
    }
}

function startMacroDone() {
    if(macroReqStart.readyState == 4) { 
	if(macroReqStart.status == 200) { 
	    var success = macroReqStart.responseText; 
	    //alert("MACRO STARTED");
	}
    }
}

function getUserMacros() {
    var user_id = userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
    var macrosViewPane = document.getElementById("macrosView");
    macrosViewPane.innerHTML = "";

    var macrosDOM = userXMLDoc.getElementsByTagName("macro");
    for(var i=0; i < macrosDOM.length; i++) {
	var macroView = document.createElement("DIV");
	if(macrosDOM[i].getAttribute('macro_name') == activeMacro) macroView.className = "activeMacro";
	else macroView.className = "macroView";
	macroView.id = macrosDOM[i].getAttribute('macro_name');
	var macroName = document.createElement("h2");
	macroName.innerHTML = macrosDOM[i].getAttribute('macro_name');
	macroView.appendChild(macroName);
	if(activeMacro ==  macrosDOM[i].getAttribute('macro_name')) macroView.onclick = function() {prepForSaveMacro(this);};
	else macroView.onclick = function() {activateTempMacro(this);};
	var stepsList = document.createElement("OL");
	var stepsDOM = macrosDOM[i].getElementsByTagName('step');
	for(var j=0; j < stepsDOM.length; j++) {
	    var stepView = document.createElement("LI");
	    stepView.className = "stepView";
	    stepView.innerHTML = stepsDOM[j].firstChild.nodeValue;
	    stepsList.appendChild(stepView);
	}
	macroView.appendChild(stepsList);
	macrosViewPane.appendChild(macroView);
    }
}

function getSavedUserMacros() {
    var user_id = userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
    var macrosViewPane = document.getElementById("macrosView");
    macrosViewPane.innerHTML = "";

    var savedMacrosDOM = userXMLDoc.getElementsByTagName("savedMacro");
    for(var i=0; i < savedMacrosDOM.length; i++) {
	var macroView = document.createElement("DIV");
	if(savedMacrosDOM[i].getAttribute('macro_name') == activeMacro) macroView.className = "activeMacro";
	else macroView.className = "macroView";
	macroView.id = savedMacrosDOM[i].getAttribute('macro_name');
	var macroName = document.createElement("h2");
	macroName.innerHTML = savedMacrosDOM[i].getAttribute('macro_name');
	macroView.appendChild(macroName);
	if(activeMacro ==  savedMacrosDOM[i].getAttribute('macro_name')) macroView.onclick = function() {prepForDeleteMacro(this);};
	else macroView.onclick = function() {activateSavedMacro(this);};
	var stepsList = document.createElement("OL");
	var stepsDOM = savedMacrosDOM[i].getElementsByTagName('step');
	for(var j=0; j < stepsDOM.length; j++) {
	    var stepView = document.createElement("LI");
	    stepView.className = "stepView";
	    stepView.innerHTML = stepsDOM[j].firstChild.nodeValue;
	    stepsList.appendChild(stepView);
	}
	macroView.appendChild(stepsList);
	macrosViewPane.appendChild(macroView);
    }
}

function activateSavedMacro(e) {
    var macrosViewPane = document.getElementById("macrosView");
    var macros = macrosViewPane.getElementsByTagName("DIV");
    for(var i=0; i < macros.length; i++) {
	macros[i].className = "macroView";
	macros[i].onclick = function() {activateSavedMacro(this);};
    }
    e.className = "activeMacro";
    e.onclick = function() {prepForDeleteMacro(e);};
    activeMacro = e.id;
}

function prepForDeleteMacro(e) {
    var macrosViewPane = document.getElementById("macrosView");
    var macros = macrosViewPane.getElementsByTagName("DIV");
    for(var i=0; i < macros.length; i++) {
	macros[i].className = "macroView";
	macros[i].onclick = function() {activateSavedMacro(this);};
    }
    e.className = "prepForDeleteMacro";
    e.onclick = function() {deleteMacro(e);};
}

function deleteMacro(e) {
    if (window.XMLHttpRequest) {
	domReq = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
	domReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (domReq != undefined) {
	var user_id =  userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
	var url = "deleteMacro.php?user_id="+user_id+"&macro_id="+encodeURIComponent(e.id);
	domReq.onreadystatechange = function() {deleteMacroDone(e);};
	domReq.open("GET", url, true);
	domReq.send("");
    }
}

function deleteMacroDone(e) {
    if(domReq.readyState == 4) { 
	if(domReq.status == 200) { 
	    var macrosViewPane = document.getElementById("macrosView");
	    var success = domReq.responseText;
	    if(success) macrosViewPane.removeChild(e);
	    else alert(success);
	}
    }
}

function activateTempMacro(e) {
    var macrosViewPane = document.getElementById("macrosView");
    var macros = macrosViewPane.getElementsByTagName("DIV");
    for(var i=0; i < macros.length; i++) {
	macros[i].className = "macroView";
	if(macros[i].firstChild.firstChild.value) {
	    macros[i].firstChild.innerHTML = macros[i].firstChild.firstChild.value;
	}
	macros[i].onclick = function() {activateTempMacro(this);};
    }
    e.className = "activeMacro";
    e.onclick = function() {prepForSaveMacro(e);};
    activeMacro = e.id;
}

function prepForSaveMacro(e) {
    var macrosViewPane = document.getElementById("macrosView");
    var macros = macrosViewPane.getElementsByTagName("DIV");
    for(var i=0; i < macros.length; i++) {
	macros[i].className = "macroView";
	macros[i].onclick = function() {activateTempMacro(this);};
    }
    e.className = "prepForSaveMacro";
    var nameInput = document.createElement("input");
    nameInput.type = "text";
    nameInput.value = e.firstChild.innerHTML;
    var submit = document.createElement("input");
    submit.type = "button";
    submit.value = "Save Macro";
    submit.onclick = function() {saveMacro(e, nameInput);};
    e.firstChild.innerHTML = "";
    e.firstChild.appendChild(nameInput);
    e.firstChild.appendChild(submit);
    e.onclick = donothing();
}

function saveMacro(e, nameInput) {
    if (window.XMLHttpRequest) {
	domReq = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
	domReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (domReq != undefined) {
	var user_id =  userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
	var macroName = encodeURIComponent(nameInput.value);
	macroName = macroName.replace(/\'/g,"\"");
	var url = "saveMacro.php?user_id="+user_id+"&macro_id="+e.id+"&newMacroName="+macroName;
	domReq.onreadystatechange = function() {saveMacroDone(e);};
	domReq.open("GET", url, true);
	domReq.send("");
    }
}

function saveMacroDone(e) {
    if(domReq.readyState == 4) { 
	if(domReq.status == 200) { 
	    var macrosViewPane = document.getElementById("macrosView");
	    var success = domReq.responseText;
	    if(success) changePage("savedMacros");
	    else alert(success);
	}
    }
}


function doActiveMacro() {
    var user_id = userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
    var macroImage = document.getElementById(selected_image_id);
    var src_url = "macroUserImage.php?user_id="+user_id+"&image_id="+selected_image_id+"&macroName="+activeMacro;
    macroImage.src = src_url;
}

function updateDOMMacros() {
    var user_id =  userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
    var url = "updateDom.php?user_id="+user_id;
    loadDOMMacros(url);
}

function loadDOMMacros(url) {
    if (window.XMLHttpRequest) {
	domReq = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
	domReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (domReq != undefined) {
	domReq.onreadystatechange = function() {updateDOMMacrosDone();};
	domReq.open("GET", url, true);
	domReq.send("");
    }
}

function updateDOMMacrosDone() {
    if(domReq.readyState == 4) { 
	if(domReq.status == 200) { 
	    var xmlString = domReq.responseText;
	    //alert(xmlString);
	    userXMLDoc = loadXMLFromString(xmlString);
	    getUserMacros();
	}
    }
}

function updateDOMSavedMacros() {
    var user_id =  userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
    var url = "updateDom.php?user_id="+user_id;
    loadDOMSavedMacros(url);
}

function loadDOMSavedMacros(url) {
    if (window.XMLHttpRequest) {
	domReq = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
	domReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (domReq != undefined) {
	domReq.onreadystatechange = function() {updateDOMSavedMacrosDone();};
	domReq.open("GET", url, true);
	domReq.send("");
    }
}

function updateDOMSavedMacrosDone() {
    if(domReq.readyState == 4) { 
	if(domReq.status == 200) { 
	    var xmlString = domReq.responseText;
	    //alert(xmlString);
	    userXMLDoc = loadXMLFromString(xmlString);
	    getSavedUserMacros();
	}
    }
}
