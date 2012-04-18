var selected_image_id;
var update_images = 0;

function getUserImages() {
    var user_id = userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
    var imagesDOM = userXMLDoc.getElementsByTagName("image");
    var imagesViewPane = document.getElementById("imagesView");
    imagesViewPane.innerHTML = "";
    update_images++;
    for(var i=0; i < imagesDOM.length; i++) {
	var image = new Image();
	image.id = imagesDOM[i].firstChild.nodeValue;
	if(imagesDOM[i].firstChild.nodeValue == selected_image_id) image.className = "smallImageH";
	else image.className = "smallImage";
	image.src = "getThumbnail.php?user_id="+user_id+"&image_id="+imagesDOM[i].firstChild.nodeValue+"&ud="+update_images;
	image.onclick = highlightImage;
	imagesViewPane.appendChild(image);
    }
}

function highlightImage() {
    selected_image_id = this.id;
    var imagesHTML = document.getElementById("imagesView").childNodes;
    for(var i=0; i < imagesHTML.length; i++) {
	imagesHTML[i].className = "smallImage";
    }
    this.className = "smallImageH";
    showEditMenu();   
    startMacro();
}

function showEditMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";
    var editButton = document.createElement("input");
    editButton.type = "button";
    editButton.value = "edit";
    editButton.onclick = function() {changePage("edit");};
    menuDiv.appendChild(editButton);
    var copyButton = document.createElement("input");
    copyButton.type = "button";
    copyButton.value = "copy";
    copyButton.onclick = copyImage;
    menuDiv.appendChild(copyButton);
    var deleteButton = document.createElement("input");
    deleteButton.type = "button";
    deleteButton.value = "delete";
    deleteButton.onclick = deleteImage;
    menuDiv.appendChild(deleteButton);
    var macroButton = document.createElement("input");
    macroButton.type = "button";
    macroButton.value = activeMacro;
    macroButton.onclick = doActiveMacro;
    menuDiv.appendChild(macroButton);
}

function hideEditMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";
}

function copyImage() {
    var user_id = userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
    var imagesDOM = userXMLDoc.getElementsByTagName("image");
    var image = new Image();
    image.className = "smallImage";
    update_images++;
    image.src = "copyUserImage.php?user_id="+user_id+"&image_id="+selected_image_id+"&ud="+update_images;
    var imagesViewPane = document.getElementById("imagesView");
    imagesViewPane.appendChild(image);
}

function deleteImage() {
    var user_id = userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
    var deletedImage = document.getElementById(selected_image_id);
    deletedImage.src = "deleteUserImage.php?user_id="+user_id+"&image_id="+selected_image_id;
    var imagesViewPane = document.getElementById("imagesView");
    imagesViewPane.removeChild(deletedImage);
}

function getEditImage() {
    var image_id;
    var user_id = userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
    var imagesDOM = userXMLDoc.getElementsByTagName("image");
    var editView = document.getElementById("editView");
    var image = new Image();
    image.id = image_id;
    image.className = "largeImage";
    image.src = "getFullImage.php?user_id="+user_id+"&image_id="+selected_image_id+"&ud="+update_images;
    editView.appendChild(image);
}


function updateDOMImages() {
    var user_id =  userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
    var url = "updateDom.php?user_id="+user_id;
    loadDOMImages(url);
}

function loadDOMImages(url) {
    if (window.XMLHttpRequest) {
	domReq = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
	domReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (domReq != undefined) {
	domReq.onreadystatechange = function() {updateDOMImagesDone();};
	domReq.open("GET", url, true);
	domReq.send("");
    }
}

function updateDOMImagesDone() {
    if(domReq.readyState == 4) { 
	if(domReq.status == 200) { 
	    var xmlString = domReq.responseText;
	    userXMLDoc = loadXMLFromString(xmlString);
	    getUserImages();
	}
    }
}
