<!--

/* ============================================================================================================= */
/* Preloading & displaying functions */
/* ============================================================================================================= */

//Populate the dropdown menu with items
function populateMenu(mode){
	if(mode == 'init'){
		var element = document.getElementById("init");
		for(i = element.options.length - 1 ; i >= 0 ; i--){element.remove(i);}
		
		for(i=minRun; i<=maxRun; i=i+incrementRun){
			var option = document.createElement("option");
			option.text = formatDate(i*-1,'init');
			option.value = i;
			element.add(option);
		}
	}
	else if(mode == 'valid'){
		var element = document.getElementById("valid");
		for(i = element.options.length - 1 ; i >= 0 ; i--){element.remove(i);}
		
		for(i=minFrame; i<=maxFrame; i=i+incrementFrame){
			var option = document.createElement("option");
			option.text = formatDate((i*6) - parseInt(imageObj.run),'valid') + " (" + (i*6) + " h)";
			option.value = i;
			element.add(option);
		}
	}
	else if(mode == 'domain'){
		var element = document.getElementById("domain");
		for(i = element.options.length - 1 ; i >= 0 ; i--){element.remove(i);}
		
		for(i=0; i<domains.length; i++){
			var option = document.createElement("option");
			option.text = domains[i].displayName;
			option.value = domains[i].name;
			element.add(option);
		}
	}
	else if(mode == 'variable'){
		var element = document.getElementById("variable");
		for(i = element.options.length - 1 ; i >= 0 ; i--){element.remove(i);}
		
		for(i=0; i<variables.length; i++){
			var option = document.createElement("option");
			option.text = variables[i].displayName;
			option.value = variables[i].name;
			element.add(option);
		}
	}
	else if(mode == 'maptype'){
		var element = document.getElementById("maptype");
		for(i = element.options.length - 1 ; i >= 0 ; i--){element.remove(i);}
		
		for(i=0; i<maptypes.length; i++){
			var option = document.createElement("option");
			option.text = maptypes[i].displayName;
			option.value = maptypes[i].name;
			element.add(option);
		}
	}
}

//Format URL to the requested domain, variable, run & frame
function getURL(domain,variable,run,frame){
	var newurl = url.replace("VVV",variable);
	newurl = newurl.replace("DDD",domain);
	newurl = newurl.replace("XXX",run);
	newurl = newurl.replace("Y",frame);
	return newurl;
}

//Search for a name within an object
function searchByName(keyname, arr){
    for (var i=0; i < arr.length; i++){
        if (arr[i].name === keyname){
            return i;
        }
    }
	return -1;
}

//Display the current image object
function showImage(){
	
	//get URL for current object
	//document.map.src = imageObj.images[imageObj.frame].src;
	
	//Update user on whether image is still loading
	if(imageObj.images[imageObj.frame].loaded == false){
		document.getElementById('loading').style.display = "block";
	}
	else{
		document.getElementById('loading').style.display = "none";
		document.map.src = imageObj.images[imageObj.frame].src;
	}
	
	//Update dropdown menus
	document.getElementById("valid").selectedIndex = (parseInt(imageObj.frame) / incrementFrame);
	document.getElementById("init").selectedIndex = (parseInt(imageObj.run) / incrementRun);
	document.getElementById("domain").selectedIndex = searchByName(imageObj.domain,domains);
	document.getElementById("variable").selectedIndex = searchByName(imageObj.variable,variables);
	document.getElementById("maptype").selectedIndex = searchByName(imageObj.maptype,maptypes);
	
	//Update URL in address bar
	generate_url();
}

//Display the current image object
function showImage_dprog(){
	
	//get URL for current object
	document.map.src = imageObj.dprog[imageObj.run].src;
	
	//Update dropdown menus
	document.getElementById("valid").selectedIndex = (parseInt(imageObj.frame) / incrementFrame);
	document.getElementById("init").selectedIndex = (parseInt(imageObj.run) / incrementRun);
	document.getElementById("domain").selectedIndex = searchByName(imageObj.domain,domains);
	document.getElementById("variable").selectedIndex = searchByName(imageObj.variable,variables);
	document.getElementById("maptype").selectedIndex = searchByName(imageObj.maptype,maptypes);
	
	//Update URL in address bar
	generate_url();
}

//Format integer as a string by number of characters
function formatString(i,val){
	if(val==3){
		if(i<10){return "00"+i;}
		if(i<100){return "0"+i;}
		return i;
	}
}

//Preload images for the current run, variable & projection
function preload(obj){
	
	obj.images = [];
    obj.loaded = [];
	obj.dprog = [];
	
	//Arrange list of hour indices to loop through
	var frameidx = imageObj.frame / incrementFrame;
	var hrs_loop = [frameidx];
	
	for(i=1; i<frames.length; i++){
		
		var idx_up = frameidx + i;
		var idx_down = frameidx - i;
		
		if(idx_up<=(maxFrame/incrementFrame)){hrs_loop.push(idx_up);}
		if(idx_down>=(minFrame/incrementFrame)){hrs_loop.push(idx_down);}
	}
	
	//Loop through all forecast hours & pre-load image
	for (i2=0; i2<hrs_loop.length; i2++){
		var i1 = hrs_loop[i2];
		var i = frames[i1];

		var strRun = formatString(obj.run,3);
		var urls = getURL(obj.domain,obj.variable,strRun,i);
		obj.images[i] = new Image();
		obj.images[i].loaded = false;
		obj.images[i].id = i;
		obj.images[i].onload = function(){this.loaded = true; remove_loading(this.run,this.id);};
		obj.images[i].onerror = function(){remove_loading(this.run,this.id);};
		obj.images[i].src = urls;
		obj.images[i].run = obj.run;
    }
}

function preload_dprog(obj){
	obj.dprog = [];
	
	//Arrange list of run indices to loop through
	var runidx = imageObj.run / incrementRun;
	var frameidx = imageObj.frame / incrementFrame;
	var hrs_loop = [runidx];
	var frames_loop = [frameidx];
	
	for(i=1; i<runs.length; i++){
		
		var idx_up = runidx + i;
		var idx_down = runidx - i;
		var frame_up = frameidx + i;
		var frame_down = frameidx - i;
		
		if((idx_up<=(maxRun/incrementRun)) && (frame_up >= (minFrame/incrementFrame))){hrs_loop.push(idx_up); frames_loop.push(frame_up);}
		if((idx_down>=(minRun/incrementRun)) && (frame_up <= (maxFrame/incrementFrame))){hrs_loop.push(idx_down); frames_loop.push(frame_down);}
	}

	//Loop through all runs & pre-load iamage
	for (i2=0; i2<hrs_loop.length; i2++){
		var i1 = hrs_loop[i2];
		var i = runs[i1];
		var j1 = frames_loop[i2];
		var j = frames[j1];

		var strRun = formatString(i,3);
		var urls = getURL(obj.domain,obj.variable,strRun,j);
		obj.dprog[i] = new Image();
		obj.dprog[i].loaded = false;
		obj.dprog[i].id = i;
		obj.dprog[i].onload = function(){this.loaded = true;};
		obj.dprog[i].onerror = function(){};
		obj.dprog[i].src = urls;
		obj.dprog[i].fhr = j;
    }
	
}

//Remove sign of loading image
function remove_loading_dprog(idx_run,idx_frame,dprog){
	return;
	check1a = parseInt(idx_run);
	check1b = imageObj.run;
	check2a = frames.indexOf(parseInt(idx_frame));
	check2b = frames.indexOf(parseInt(imageObj.frame));
	
	console.log(check1a + " --- " + check1b)
	console.log(check2a + " --- " + check2b)
	console.log("")
	
	//Remove if the image just loaded for the currently displayed image
	if((check1a == check1b) && (check2a == check2b)){
		console.log("YES")
		document.getElementById('loading').style.display = "none";
		document.map.src = imageObj.dprog[imageObj.run].src;
	}
}

//Remove sign of loading image
function remove_loading(idx_run,idx_frame,dprog){
	check1a = parseInt(idx_run);
	check1b = imageObj.run;
	check2a = frames.indexOf(parseInt(idx_frame));
	check2b = frames.indexOf(parseInt(imageObj.frame));
	
	//Remove if the image just loaded for the currently displayed image
	if((check1a == check1b) && (check2a == check2b)){
		document.getElementById('loading').style.display = "none";
		document.map.src = imageObj.images[imageObj.frame].src;
	}
}

/* ============================================================================================================= */
/* Dropdown menu functions */
/* ============================================================================================================= */

//Change the valid frame from dropdown menu
function changeValid(id){
	imageObj.frame = id;
	
	//Determine if need to preload (coming off of dprog/dt)
	if(imageObj.images[0].run != imageObj.run){preload(imageObj);}
	
	showImage();
	document.getElementById("valid").blur();
}

//Change the map domain from dropdown menu
function changeDomain(id){
	imageObj.domain = id;
	preload(imageObj);
	showImage();
	document.getElementById("domain").blur();
}

//Change the variable from dropdown menu
function changeVariable(id){
	imageObj.variable = id;
	preload(imageObj);
	showImage();
	document.getElementById("variable").blur();
}

//Change the initialized run from dropdown menu
function changeInit(id){
	
	//Determine how many hours to change the valid frame
	id = parseInt(id);
	var old_id = parseInt(imageObj.run);
	var diff = id - old_id;
	var frameDiff = (diff / incrementRun) * (incrementRun/6);

	//Update frame
	var curFrame = parseInt(imageObj.frame);
	var newFrame = curFrame + frameDiff;
	if((newFrame <= maxFrame) && (newFrame >= minFrame)){curFrame = newFrame;}
	else{
		if(newFrame>maxFrame){newFrame = maxFrame;}
		if(newFrame<minFrame){newFrame = minFrame;}
	}
	imageObj.frame = curFrame;
	
	//Update new run
	imageObj.run = parseInt(id);
	populateMenu('valid');
	preload(imageObj);
	showImage();
	document.getElementById("init").blur();
}

//Change the initialized run from keyboard (dprog/dt loop)
function changeDprog(id){
	
	//Determine how many hours to change the valid frame
	id = parseInt(id);
	var old_id = parseInt(imageObj.run);
	var diff = id - old_id;
	var frameDiff = (diff / incrementRun) * (incrementRun/6);

	//Update frame
	var curFrame = parseInt(imageObj.frame);
	var newFrame = curFrame + frameDiff;
	if((newFrame <= maxFrame) && (newFrame >= minFrame)){curFrame = newFrame;}else{return;}
	imageObj.frame = curFrame;
	
	//Update new run & preload via dprog/dt settings
	imageObj.run = parseInt(id);
	populateMenu('valid');
	preload_dprog(imageObj);
	
	//Update image
	showImage_dprog();
}

//Change the map type
function changeMaptype(id){
	var newUrl = maptypes[searchByName(id,maptypes)].url;
	window.open(newUrl,"_self");
}

/* ============================================================================================================= */
/* Keyboard controls */
/* ============================================================================================================= */

function keys(e){
	//Left
	if(e.keyCode == 37){
		prevFrame();
		return !(e.keyCode);
	}
	//Up
	else if(e.keyCode == 38){
		prevRun();
		return !(e.keyCode);
	}
	//Right
	else if(e.keyCode == 39){
		nextFrame();
		return !(e.keyCode);
	}
	//Down
	else if(e.keyCode == 40){
		nextRun();
		return !(e.keyCode);
	}
}

function prevFrame(){
	var curFrame = parseInt(imageObj.frame);
	if(curFrame > minFrame){curFrame = curFrame - incrementFrame;}
	changeValid(curFrame);
}

function nextFrame(){
	var curFrame = parseInt(imageObj.frame);
	if(curFrame < maxFrame){curFrame = curFrame + incrementFrame;}
	changeValid(curFrame);
}

function prevRun(){
	var curRun = parseInt(imageObj.run);
	if(curRun > minRun){curRun = curRun - incrementRun;}
	changeDprog(curRun);
}

function nextRun(){
	var curRun = parseInt(imageObj.run);
	if(curRun < maxRun){curRun = curRun + incrementRun;}
	changeDprog(curRun);
}

/* ============================================================================================================= */
/* Additional functions */
/* ============================================================================================================= */

//Update the URL in the address bar by the current domain and variable
function generate_url(){
	
	var url = window.location.href.split('?')[0] + "?";
	var append = "";
	
	//Add domain
	append += "domain=" + imageObj.domain;
	
	//Add variable
	append += "&variable=" + imageObj.variable;

	//Get new URL
	var total = url + append;
	
	//Update in address bar without reloading page
	var pagename = window.location.href.split('/');
	pagename = pagename[pagename.length-1];
	pagename = pagename.split(".php")[0];
	var stateObj = { foo: "bar" };
	history.replaceState(stateObj, "", pagename+".php?"+append);
	
	//Update selected menu item based on this
	document.getElementById('maptype').selectedIndex = searchByName(pagename,maptypes);

	return total;
}

function updateMobile(){
	if( navigator.userAgent.match(/Android/i)
	|| navigator.userAgent.match(/webOS/i)
	|| navigator.userAgent.match(/iPhone/i)
	|| navigator.userAgent.match(/iPod/i)
	|| navigator.userAgent.match(/BlackBerry/i)
	|| navigator.userAgent.match(/Windows Phone/i)
	){
		document.getElementById('page-middle').innerHTML = "Swipe Up/Down = Change initialization time | Swipe Left/Right = Change valid time";
	}


	//Swipe for mobile devices only when focused on image
	var element = document.getElementsByName("map")[0];
	element.addEventListener("touchstart", touchStart, false);
	element.addEventListener("touchend", touchEnd, false);
	element.addEventListener("touchmove", touchMove, false);

}

function touchStart(e){
    xInit = e.touches[0].clientX;
    yInit = e.touches[0].clientY;
};

function touchMove(e){
	e.preventDefault();
    xPos = e.touches[0].clientX;
    yPos = e.touches[0].clientY;
};

function touchEnd() {
    if ( ! xPos || ! yPos ) {
        return;
    }
	
    //Get difference in x & y positions
    var xDiff = xInit - xPos;
    var yDiff = yInit - yPos;
	
	//Determine whether swipe was vertical or horizontal
    if ( Math.abs(xDiff) > Math.abs(yDiff) ){
        if( xDiff > 0 ){
            //Left swipe
			nextFrame();
        }
		else{
            //Right swipe
			prevFrame();
        }                       
    }
	else{
        if ( yDiff > 0 ){
            //Up swipe
			nextRun();
        }
		else{ 
            //Down swipe
			prevRun();
        }                                                                 
    }
	
    //reset values
    xInit = null;
    yInit = null;  
	xPos = null;
	yPos = null;
};

-->
