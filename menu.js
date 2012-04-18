var menuReq;

function changePage(tabName)
{
    var tab = document.getElementById(tabName).firstChild;
    var tabs = tab.parentNode.parentNode.getElementsByTagName("a");
    for(var t = 0; t < tabs.length; t++){
	tabs[t].className = "tabOff";
    }
    tab.className = "tabOn";
    var page = tab.parentNode.getAttribute("id")+".html";
    loadView(page, "view");
}

function loadView(url, target) {
    if (window.XMLHttpRequest) {
	menuReq = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
	menuReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    if (menuReq != undefined) {
	menuReq.onreadystatechange = function() {loadDone(url, target);};
	menuReq.open("GET", url, true);
	menuReq.send("");
    }
}  

function loadDone(url, target) {
  if (menuReq.readyState == 4) { 
    if (menuReq.status == 200) { 
      document.getElementById(target).innerHTML = menuReq.responseText;
      if(url == "upload.html") {
	  hideEditMenu();
      }
      if(url == "images.html") {
	  if(selected_image_id) showEditMenu();
	  else hideEditMenu();
	  updateDOMImages();
      }
      if(url == "edit.html") {
	  hideEditMenu();
	  getEditImage();
	  showImagickMenu();
      }
      if(url == "macros.html") {
	  hideEditMenu();
	  updateDOMMacros();
      }
      if(url == "savedMacros.html") {
	  hideEditMenu();
	  updateDOMSavedMacros();
      }
    } 
    else {
      document.getElementById(target).innerHTML=" Load Error:\n"+ menuReq.status + "\n" +menuReq.statusText;
    }
  }
}
