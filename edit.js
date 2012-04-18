function showImagickMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var resizeButton = document.createElement("input");
    resizeButton.type = "button";
    resizeButton.value = "resize";
    resizeButton.onclick = resizeMenu;
    menuDiv.appendChild(resizeButton);

    var roundButton = document.createElement("input");
    roundButton.type = "button";
    roundButton.value = "round corners";
    roundButton.onclick = roundCornersMenu;
    menuDiv.appendChild(roundButton);

    var watermark = document.createElement("input");
    watermark.type = "button";
    watermark.value = "watermark";
    watermark.onclick = watermarkMenu;
    menuDiv.appendChild(watermark);

    var shave = document.createElement("input");
    shave.type = "button";
    shave.value = "shave";
    shave.onclick = shaveMenu;
    menuDiv.appendChild(shave);

    var shrink = document.createElement("input");
    shrink.type = "button";
    shrink.value = "shrink";
    shrink.onclick = shrinkMenu;
    menuDiv.appendChild(shrink);

    var quality = document.createElement("input");
    quality.type = "button";
    quality.value = "quality";
    quality.onclick = qualityMenu;
    menuDiv.appendChild(quality);

    var flip = document.createElement("input");
    flip.type = "button";
    flip.value = "flip";
    flip.onclick = function() {doImageFunction("flip");};
    menuDiv.appendChild(flip);

    var flop = document.createElement("input");
    flop.type = "button";
    flop.value = "flop";
    flop.onclick = function() {doImageFunction("flop");};
    menuDiv.appendChild(flop);

    var rotate = document.createElement("input");
    rotate.type = "button";
    rotate.value = "rotate";
    rotate.onclick = rotateMenu;
    menuDiv.appendChild(rotate);
    
    var border = document.createElement("input");
    border.type = "button";
    border.value = "border";
    border.onclick = borderMenu;
    menuDiv.appendChild(border);

    var frame = document.createElement("input");
    frame.type = "button";
    frame.value = "frame";
    frame.onclick = frameMenu;
    menuDiv.appendChild(frame);

    var modulate = document.createElement("input");
    modulate.type = "button";
    modulate.value = "modulate";
    modulate.onclick = modulateMenu;
    menuDiv.appendChild(modulate);

    var sharpen = document.createElement("input");
    sharpen.type = "button";
    sharpen.value = "sharpen";
    sharpen.onclick = sharpenMenu;
    menuDiv.appendChild(sharpen);

    var emboss = document.createElement("input");
    emboss.type = "button";
    emboss.value = "emboss";
    emboss.onclick = embossMenu;
    menuDiv.appendChild(emboss);

    var shade = document.createElement("input");
    shade.type = "button";
    shade.value = "shade";
    shade.onclick = shadeMenu;
    menuDiv.appendChild(shade);

    var paint = document.createElement("input");
    paint.type = "button";
    paint.value = "paint";
    paint.onclick = oilPaintMenu;
    menuDiv.appendChild(paint);

    var charcoal = document.createElement("input");
    charcoal.type = "button";
    charcoal.value = "charcoal";
    charcoal.onclick = charcoalMenu;
    menuDiv.appendChild(charcoal);

    var negate = document.createElement("input");
    negate.type = "button";
    negate.value = "negate";
    negate.onclick = negateMenu;
    menuDiv.appendChild(negate);
}

function roundCornersMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    menuDiv.innerHTML += " x:";
    var roundX = document.createElement("input");
    roundX.id = "one";
    roundX.type = "text";
    roundX.value = "10";
    menuDiv.appendChild(roundX);

    menuDiv.innerHTML += " y:";
    var roundY = document.createElement("input");
    roundY.id = "two";
    roundY.type = "text";
    roundY.value = "10";
    menuDiv.appendChild(roundY);

    var roundButton = document.createElement("input");
    roundButton.type = "button";
    roundButton.value = "round corners";
    roundButton.onclick = function() {doImageFunction("roundCorners");};
    menuDiv.appendChild(roundButton);
}

function resizeMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    menuDiv.innerHTML += " height:";
    var height = document.createElement("input");
    height.id = "one";
    height.type = "text";
    height.value = "500";
    menuDiv.appendChild(height);

    menuDiv.innerHTML += " width:";
    var width = document.createElement("input");
    width.id = "two";
    width.type = "text";
    width.value = "600";
    menuDiv.appendChild(width);

    var resizeButton = document.createElement("input");
    resizeButton.type = "button";
    resizeButton.value = "resize";
    resizeButton.onclick = function() {doImageFunction("resize");};
    menuDiv.appendChild(resizeButton);
}

function watermarkMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);


    menuDiv.innerHTML += " text:";
    var text = document.createElement("input");
    text.id = "one";
    text.type = "text";
    text.className = "threeHundredpix";
    text.value = "text goes here";
    menuDiv.appendChild(text);


    menuDiv.innerHTML += " font size:";
    var fontSize = document.createElement("input");
    fontSize.id = "two";
    fontSize.type = "text";
    fontSize.className = "fiftypix";
    fontSize.value = "12";
    menuDiv.appendChild(fontSize);

    menuDiv.innerHTML += " font color:";
    var fontColor = document.createElement("select");
    fontColor.id = "three";
    var black = document.createElement("option");
    black.value = "black";
    black.innerHTML = "black";
    fontColor.appendChild(black);
    var blue = document.createElement("option");
    blue.value = "blue";
    blue.innerHTML = "blue";
    fontColor.appendChild(blue);
    var red = document.createElement("option");
    red.value = "red";
    red.innerHTML = "red";
    fontColor.appendChild(red);
    var green = document.createElement("option");
    green.value = "green";
    green.innerHTML = "green";
    fontColor.appendChild(green);
    menuDiv.appendChild(fontColor);

    menuDiv.innerHTML += " offset-x:";
    var x = document.createElement("input");
    x.id = "four";
    x.type = "text";
    x.className = "fiftypix";
    x.value = "0";
    menuDiv.appendChild(x);

    menuDiv.innerHTML += " offset-y:";
    var y = document.createElement("input");
    y.id = "five";
    y.type = "text";
    y.className = "fiftypix";
    y.value = "0";
    menuDiv.appendChild(y);

    var watermarkButton = document.createElement("input");
    watermarkButton.type = "button";
    watermarkButton.value = "watermark";
    watermarkButton.onclick = function() {doImageFunction("watermark");};
    menuDiv.appendChild(watermarkButton);
}

function shaveMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);


    menuDiv.innerHTML += " x";
    var x = document.createElement("input");
    x.id = "one";
    x.type = "text";
    x.className = "fiftypix";
    x.value = "1";
    menuDiv.appendChild(x);


    menuDiv.innerHTML += " y";
    var y = document.createElement("input");
    y.id = "two";
    y.type = "text";
    y.className = "fiftypix";
    y.value = "1";
    menuDiv.appendChild(y);

    var shaveButton = document.createElement("input");
    shaveButton.type = "button";
    shaveButton.value = "shave";
    shaveButton.onclick = function() {doImageFunction("shave");};
    menuDiv.appendChild(shaveButton);
}

function shrinkMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    var percent = document.createElement("input");
    percent.id = "one";
    percent.type = "text";
    percent.className = "fiftypix";
    percent.value = "60";
    menuDiv.appendChild(percent);

    menuDiv.innerHTML += "%";

    var shrinkButton = document.createElement("input");
    shrinkButton.type = "button";
    shrinkButton.value = "shrink";
    shrinkButton.onclick =  function() {doImageFunction("percentSize");};
    menuDiv.appendChild(shrinkButton);
}

function qualityMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    var percent = document.createElement("input");
    percent.id = "one";
    percent.type = "text";
    percent.className = "fiftypix";
    percent.value = "60";
    menuDiv.appendChild(percent);

    menuDiv.innerHTML += "%";

    var qualityButton = document.createElement("input");
    qualityButton.type = "button";
    qualityButton.value = "quality";
    qualityButton.onclick = function() {doImageFunction("setCompression");};
    menuDiv.appendChild(qualityButton);
}

function rotateMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    menuDiv.innerHTML += " degrees:";
    var degrees = document.createElement("input");
    degrees.id = "one";
    degrees.type = "text";
    degrees.className = "fiftypix";
    degrees.value = "90";
    menuDiv.appendChild(degrees);

    var rotateButton = document.createElement("input");
    rotateButton.type = "button";
    rotateButton.value = "rotate";
    rotateButton.onclick = function() {doImageFunction("rotate");};
    menuDiv.appendChild(rotateButton);
}

function borderMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    menuDiv.innerHTML += " x:";
    var x = document.createElement("input");
    x.id = "one";
    x.type = "text";
    x.className = "fiftypix";
    x.value = "1";
    menuDiv.appendChild(x);

    menuDiv.innerHTML += " y:";
    var y = document.createElement("input");
    y.id = "two";
    y.type = "text";
    y.className = "fiftypix";
    y.value = "1";
    menuDiv.appendChild(y);

    menuDiv.innerHTML += " border color:";
    var borderColor = document.createElement("select");
    borderColor.id = "three";
    var black = document.createElement("option");
    black.value = "black";
    black.innerHTML = "black";
    borderColor.appendChild(black);
    var blue = document.createElement("option");
    blue.value = "blue";
    blue.innerHTML = "blue";
    borderColor.appendChild(blue);
    var red = document.createElement("option");
    red.value = "red";
    red.innerHTML = "red";
    borderColor.appendChild(red);
    var green = document.createElement("option");
    green.value = "green";
    green.innerHTML = "green";
    borderColor.appendChild(green);
    menuDiv.appendChild(borderColor);

    var borderButton = document.createElement("input");
    borderButton.type = "button";
    borderButton.value = "border";
    borderButton.onclick = function() {doImageFunction("border");};
    menuDiv.appendChild(borderButton);
}

function frameMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    menuDiv.innerHTML += " height:";
    var height = document.createElement("input");
    height.id = "one";
    height.type = "text";
    height.className = "fiftypix";
    height.value = "10";
    menuDiv.appendChild(height);

    menuDiv.innerHTML += " width:";
    var width = document.createElement("input");
    width.id = "two";
    width.type = "text";
    width.className = "fiftypix";
    width.value = "10";
    menuDiv.appendChild(width);

    menuDiv.innerHTML += " inner bevel:";
    var inner_bevel = document.createElement("input");
    inner_bevel.id = "three";
    inner_bevel.type = "text";
    inner_bevel.className = "fiftypix";
    inner_bevel.value = "2";
    menuDiv.appendChild(inner_bevel);

    menuDiv.innerHTML += " outer bevel:";
    var outer_bevel = document.createElement("input");
    outer_bevel.id = "four";
    outer_bevel.type = "text";
    outer_bevel.className = "fiftypix";
    outer_bevel.value = "2";
    menuDiv.appendChild(outer_bevel);

    menuDiv.innerHTML += " border color:";
    var borderColor = document.createElement("select");
    borderColor.id = "five";
    var black = document.createElement("option");
    black.value = "black";
    black.innerHTML = "black";
    borderColor.appendChild(black);
    var blue = document.createElement("option");
    blue.value = "blue";
    blue.innerHTML = "blue";
    borderColor.appendChild(blue);
    var red = document.createElement("option");
    red.value = "red";
    red.innerHTML = "red";
    borderColor.appendChild(red);
    var green = document.createElement("option");
    green.value = "green";
    green.innerHTML = "green";
    borderColor.appendChild(green);
    menuDiv.appendChild(borderColor);

    var frameButton = document.createElement("input");
    frameButton.type = "button";
    frameButton.value = "frame";
    frameButton.onclick = function() {doImageFunction("frame");};
    menuDiv.appendChild(frameButton);
}

function modulateMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    menuDiv.innerHTML += " brightness:";
    var brightness = document.createElement("input");
    brightness.id = "one";
    brightness.type = "text";
    brightness.className = "fiftypix";
    brightness.value = "70";
    menuDiv.appendChild(brightness);

    menuDiv.innerHTML += " saturation:";
    var saturation = document.createElement("input");
    saturation.id = "two";
    saturation.type = "text";
    saturation.className = "fiftypix";
    saturation.value = "50";
    menuDiv.appendChild(saturation);

    menuDiv.innerHTML += " hue:";
    var hue = document.createElement("input");
    hue.id = "three";
    hue.type = "text";
    hue.className = "fiftypix";
    hue.value = "50";
    menuDiv.appendChild(hue);
    
    var modulateButton = document.createElement("input");
    modulateButton.type = "button";
    modulateButton.value = "modulate";
    modulateButton.onclick = function() {doImageFunction("modulate");};
    menuDiv.appendChild(modulateButton);
}

function sharpenMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    menuDiv.innerHTML += " sigma:";
    var sigma = document.createElement("input");
    sigma.id = "one";
    sigma.type = "text";
    sigma.className = "fiftypix";
    sigma.value = "70";
    menuDiv.appendChild(sigma);
    
    var sharpenButton = document.createElement("input");
    sharpenButton.type = "button";
    sharpenButton.value = "sharpen";
    sharpenButton.onclick = function() {doImageFunction("sharpen");};
    menuDiv.appendChild(sharpenButton);
}

function embossMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    menuDiv.innerHTML += " sigma:";
    var sigma = document.createElement("input");
    sigma.id = "one";
    sigma.type = "text";
    sigma.className = "fiftypix";
    sigma.value = "70";
    menuDiv.appendChild(sigma);
    
    var embossButton = document.createElement("input");
    embossButton.type = "button";
    embossButton.value = "emboss";
    embossButton.onclick = function() {doImageFunction("emboss");};
    menuDiv.appendChild(embossButton);
}

function shadeMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    menuDiv.innerHTML += " gray:";
    var gray = document.createElement("input");
    gray.id = "one";
    gray.type = "checkbox";
    gray.className = "fiftypix";
    gray.value = "TRUE";
    menuDiv.appendChild(gray);

    menuDiv.innerHTML += " azimuth:";
    var azimuth = document.createElement("input");
    azimuth.id = "two";
    azimuth.type = "text";
    azimuth.className = "fiftypix";
    azimuth.value = "70";
    menuDiv.appendChild(azimuth);

    menuDiv.innerHTML += " elevation:";
    var elevation = document.createElement("input");
    elevation.id = "three";
    elevation.type = "text";
    elevation.className = "fiftypix";
    elevation.value = "70";
    menuDiv.appendChild(elevation);
    
    var shadeButton = document.createElement("input");
    shadeButton.type = "button";
    shadeButton.value = "shade";
    shadeButton.onclick = function() {doImageFunction("shade");};
    menuDiv.appendChild(shadeButton);
}


function oilPaintMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    menuDiv.innerHTML += " radius:";
    var radius = document.createElement("input");
    radius.id = "one";
    radius.type = "text";
    radius.className = "fiftypix";
    radius.value = "1";
    menuDiv.appendChild(radius);
    
    var oilPaintButton = document.createElement("input");
    oilPaintButton.type = "button";
    oilPaintButton.value = "oil paint";
    oilPaintButton.onclick = function() {doImageFunction("oilPaint");};
    menuDiv.appendChild(oilPaintButton);
}

function charcoalMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    menuDiv.innerHTML += " radius:";
    var radius = document.createElement("input");
    radius.id = "one";
    radius.type = "text";
    radius.className = "fiftypix";
    radius.value = "1";
    menuDiv.appendChild(radius);

    menuDiv.innerHTML += " sigma:";
    var sigma = document.createElement("input");
    sigma.id = "two";
    sigma.type = "text";
    sigma.className = "fiftypix";
    sigma.value = "1";
    menuDiv.appendChild(sigma);
    
    var charcoalButton = document.createElement("input");
    charcoalButton.type = "button";
    charcoalButton.value = "charcoal";
    charcoalButton.onclick = function() {doImageFunction("charcoal");};
    menuDiv.appendChild(charcoalButton);
}

function negateMenu() {
    var menuDiv = document.getElementById("menuDiv");
    menuDiv.innerHTML = "";

    var mainMenu = document.createElement("input");
    mainMenu.type = "button";
    mainMenu.value = "main menu";
    mainMenu.onclick = showImagickMenu;
    menuDiv.appendChild(mainMenu);

    menuDiv.innerHTML += " gray:";
    var gray = document.createElement("input");
    gray.id = "one";
    gray.type = "checkbox";
    gray.className = "fiftypix";
    gray.value = "TRUE";
    menuDiv.appendChild(gray);
    
    var negateButton = document.createElement("input");
    negateButton.type = "button";
    negateButton.value = "negate";
    negateButton.onclick = function() {doImageFunction("negate");};
    menuDiv.appendChild(negateButton);
}

function doImageFunction(func) {
    update_images++;
    var user_id = userXMLDoc.getElementsByTagName("user_id")[0].firstChild.nodeValue;
    var image = document.getElementById("editView").getElementsByTagName("img")[0];
    var image_src = "modifyUserImage.php?user_id="+user_id+"&image_id="+selected_image_id+"&function="+func;
    var count = ["one","two","three","four","five","six","seven"];
    var parameters = new Array();
    for(var a=0; a < count.length; a++) {
	if(document.getElementById(count[a])) {
	    if(document.getElementById(count[a]).type == "text") {
		parameters[a] = document.getElementById(count[a]).value;
	    }
	    else if(document.getElementById(count[a]).type == "checkbox") {
		if(document.getElementById(count[a]).checked == true) parameters[a] = "TRUE";
		else parameters[a] = "FALSE";
	    }
	    else if(document.getElementById(count[a]).options) {
		parameters[a] = document.getElementById(count[a]).options[document.getElementById(count[a]).selectedIndex].value;
	    }
	}
    }
    for(var i=0; i < parameters.length; i++) {
	if(parameters[i]) image_src += "&param[]="+parameters[i];
    }
    //alert(image_src);
    image.src = image_src;
}
