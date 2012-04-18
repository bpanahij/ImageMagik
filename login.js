var loginReq;
var domReq;
var userXMLDoc;

window.onload = loginUser;

function loginUser() {
    var user_id;// = getCookie("user_id");
    if(user_id) {
	var url = "loginUser.php?user_id="+user_id;
	alert(user_id);
    }
    else {
	var username = document.getElementById("username").value;
	var password = document.getElementById("password").value;
	var url = "loginUser.php?username="+username+"&password="+password;
    }
    loadUser(url);
}

function loadUser(url) {
    if (window.XMLHttpRequest) {
	loginReq = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
	loginReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (loginReq != undefined) {
	loginReq.onreadystatechange = function() {loginDone();};
	loginReq.open("GET", url, true);
	loginReq.send("");
    }
}


function loginDone() {
    if(loginReq.readyState == 4) { 
	if(loginReq.status == 200) { 
	    var xmlString = loginReq.responseText;
	    //alert(xmlString);
	    userXMLDoc = loadXMLFromString(xmlString);
	    //setCookie("user_id",userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue, 1);
	    var statusDiv = document.getElementById("statusDiv");
	    var userName = document.createElement("DIV");
	    userName.className = "userName";
	    userName.innerHTML = userXMLDoc.getElementsByTagName("username")[0].firstChild.nodeValue;
	    statusDiv.innerHTML = "";
	    statusDiv.appendChild(userName);
	    changePage("images");
	}
    }
}



function loadXMLFromString(string) {
    var xmlDoc;
    try {
	xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
	xmlDoc.async="false";
	xmlDoc.loadXML(string);
    }
    catch(e){
	try {
	    parser=new DOMParser();
	    xmlDoc=parser.parseFromString(string,"text/xml");
	}
	catch(e) {alert(e.message)}
    }
    return xmlDoc;
}
