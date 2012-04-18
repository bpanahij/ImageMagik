var numberofuploads = 0;

function addUpload(e) 
{
    var user_id = userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;

    var imageUploadDiv = document.getElementById("imageUpload");
    var iframe;
    try {
	iframe = document.createElement('<iframe name="upload_target">');
    } 
    catch (ex) {
	iframe = document.createElement('iframe');
    }
    iframe.id = "upload_target"+numberofuploads;
    iframe.name = "upload_target"+numberofuploads;
    iframe.src = "#";
    iframe.style.width="0px";
    iframe.style.height="0px";
    imageUploadDiv.appendChild(iframe);

    var imageForm = document.createElement("FORM");
    imageForm.id = "imageForm";
    imageForm.action = "upload.php";
    imageForm.className = "imageForm";
    var target = document.createAttribute("target");
    target.nodeValue = "upload_target"+numberofuploads;
    imageForm.setAttributeNode(target);
    var encType = document.createAttribute("enctype");
    encType.nodeValue = "multipart/form-data";
    imageForm.setAttributeNode(encType);
    imageForm.method = "post";
    imageForm.onsubmit = "startUpload(this)";
    imageUploadDiv.appendChild(imageForm);

    var userid = document.createElement("INPUT");
    userid.name = "user_id";
    userid.type = "hidden";
    userid.value = user_id;
    imageForm.appendChild(userid);

    var filename = document.createElement("INPUT");
    filename.name = "userfile";
    filename.type = "file";
    imageForm.appendChild(filename);

    var submitButton = document.createElement("INPUT");
    submitButton.name = "Submit";
    submitButton.type = "submit";
    submitButton.value = "Upload";
    imageForm.appendChild(submitButton);
}

function startUpload(e)
{
    var loaderDiv = document.createElement("div");
    loaderDiv.innerHTML = e.firstChild.value;
    var loaderImage = new Image();
    loaderImage.src = "images/loader.gif";
    loaderDiv.appendChild(loaderImage);
    e.parentNode.replaceChild(loaderDiv,e);
}

function stopUpload() {
    updateDOM();
}