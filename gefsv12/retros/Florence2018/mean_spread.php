
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>GEFS | Mean/Spread</title>
<link rel="stylesheet" type="text/css" href="../main.css">
<script type="text/javascript" src="../functions_gefs_emc.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

        <!--DAP script -->
        <script src="//dap.digitalgov.gov/Universal-Federated-Analytics-Min.js?agency=DOC&amp;subagency=NOAA" id="_fed_an_ua_tag"></script>

<body>


<!-- Head element -->
<div class="page-top">
        <span><a style="color:#FFFFFF">GEFSv12 Retrospective Case Studies | Hurricane Florence (Sep 2018)</a></span>
</div>

<!-- Top menu -->
<div class="page-menu"><div class="table">
	
	<div class="element">
		<span class="bold">Variable:</span>
		<select id="variable" onchange="changeVariable(this.value)">
		</select>
	</div>
	<div class="element">
		<span class="bold">Initialized:</span>
		<select id="init" onchange="changeInit(this.value);">
		</select>
	</div>
	<div class="element">
		<span class="bold">Valid:</span>
		<select id="valid" onchange="changeValid(this.value)">
		</select>
	</div>
	<div class="element">
		<span class="bold">Domain:</span>
		<select id="domain" onchange="changeDomain(this.value)">
		</select>
	</div>
	<div class="element">
		<span class="bold">Map Type:</span>
		<select id="maptype" onchange="changeMaptype(this.value);">
		</select>
	</div>

<!-- /Top menu -->
</div></div>

<!-- Middle menu -->
<div class="page-middle" id="page-middle">
<a style="color:#FF0000">Up/Down arrow keys = Change initialization time | Left/Right arrow keys = Change valid time</a>
<!-- /Middle menu -->
</div>

<div id="loading"><img style="width:100%" src="../loading.png"></div>

<!-- Image -->
<div id="page-map">
	<image name="map" style="width:100%">
</div>

<!-- /Footer -->
<div class="page-footer">
<!--	<span>This webpage is experimental and data may occasionally be missing.  Contact Alicia.Bentley@noaa.gov with any questions.</span>
--></div>

<script type="text/javascript">
//====================================================================================================
//User-defined variables
//====================================================================================================

var eventname = "Florence2018"

var cycle = 2018091412

//Global variables
var minFrame = 0; //Minimum frame for every variable
var maxFrame = 40; //Maximum frame for every variable
var incrementFrame = 1; //Increment for every frame

var minRun = 0; //Latest run (should be zero)
var maxRun = 240; //Number of hours difference between the last available run & current run
var incrementRun = 24; //Interval between each run (6 hours)

var startFrame = 0; //Starting frame
var startRun = 0; //Starting run

/*
When constructing the URL below, DDD = domain, VVV = variable, XXX = hours difference between latest run and this run, Y = frame number.
For X and Y, labeling one X or Y represents an integer (e.g. 0, 10, 20). Multiple of these represent a string
format (e.g. XX = 00, 06, 12 --- XXX = 000, 006, 012).
*/
var url = "https://www.emc.ncep.noaa.gov/users/meg/gefsv12/retros/"+eventname+"/pXXX/gefs_DDD_VVV_"+eventname+"_Y.png";

//====================================================================================================
//Add variables & domains
//====================================================================================================
var runs = [];
var variables = [];
var domains = [];
var maptypes = [];

variables.push({
        displayName: "6-h Precipitation",
        name: "6hqpf",
});
variables.push({
        displayName: "24-h Precipitation",
        name: "24hqpf",
});
variables.push({
        displayName: "Total Precipitation",
        name: "totalqpf",
});
variables.push({
	displayName: "Sea Level Pressure",
	name: "slp",
});
variables.push({
        displayName: "2-m Temperature",
        name: "2mt",
});
variables.push({
        displayName: "10-m Wind (u)",
        name: "10mu",
});
variables.push({
        displayName: "10-m Wind (v)",
        name: "10mv",
});
variables.push({
        displayName: "850-hPa Wind (u)",
        name: "850u",
});
variables.push({
        displayName: "850-hPa Wind (v)",
        name: "850v",
});
variables.push({
        displayName: "850-hPa Temperature",
        name: "850t",
});
variables.push({
        displayName: "Precipitable Water",
        name: "pw",
});
variables.push({
        displayName: "500-hPa Geo. Height",
        name: "500g",
});
variables.push({
        displayName: "250-hPa Wind (u)",
        name: "250u",
});
variables.push({
        displayName: "250-hPa Wind (v)",
        name: "250v",
});
variables.push({
        displayName: "CAPE",
        name: "cape",
});






domains.push({
        displayName: "N. Hemisphere",
        name: "nh",
});
domains.push({
	displayName: "Northwest Atlantic",
	name: "nwatl",
});
domains.push({
        displayName: "Carolinas",
        name: "NC",
});




maptypes.push({
	url: "mean_spread.php",
	displayName: "Mean/Spread",
	name: "mean_spread",
});
maptypes.push({
        url: "lows_spaghetti.php",
        displayName: "Lows/Spaghetti",
        name: "lows_spaghetti",
});
maptypes.push({
        url: "probability.php",
        displayName: "Probability",
        name: "probability",
});
maptypes.push({
        url: "tracks.php",
        displayName: "Track Density",
        name: "tracks",
});


//====================================================================================================
//Initialize the page
//====================================================================================================

//function for keyboard controls
document.onkeydown = keys;

//Decare object containing data about the currently displayed map
imageObj = {};

//Initialize the page
initialize();

//Format initialized run date & return in requested format
function formatDate(offset,format){
	var newdate = String(cycle);
	var yyyy = newdate.slice(0,4);
	var mm = newdate.slice(4,6);
	var dd = newdate.slice(6,8);
	var hh = newdate.slice(8,10);
	var curdate = new Date(yyyy,parseInt(mm)-1,dd,hh);
	
	//Offset by run
	var newOffset = curdate.getHours() + offset;
	curdate.setHours(newOffset);
	
	var yy = String(curdate.getFullYear()).slice(2,4);
	yyyy = curdate.getFullYear();
	mm = curdate.getMonth()+1;
	dd = curdate.getDate();
	if(dd < 10){dd = "0" + dd;}
	hh = curdate.getHours();
	if(hh < 10){hh = "0" + hh;}
	
	var wkday = curdate.getDay();
	var day_str = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
	
	//Return in requested format
	if(format == 'valid'){
		//06Z Thu 03/22/18 (90 h)
		var txt = hh + "Z " + day_str[wkday] + " " + mm + "/" + dd + "/" + yy;
		return txt;
	}
	if(format == 'init'){
		//06Z Thu 03/22/18
		var txt = hh + "Z " + day_str[wkday] + " " + mm + "/" + dd + "/" + yy;
		return txt;
	}
}

//Initialize the page
function initialize(){
	
	//Set image object based on default variables
	imageObj = {
		variable: "slp",
		domain: "nwatl",
		run: startRun,
		frame: startFrame,
	};
	
	//Change domain based on passed argument, if any
	var passed_domain = "";
	if(passed_domain!=""){
		if(searchByName(passed_domain,domains)>=0){
			imageObj.domain = passed_domain;
		}
	}
	
	//Change variable based on passed argument, if any
	var passed_variable = "";
	if(passed_variable!=""){
		if(searchByName(passed_variable,variables)>=0){
			imageObj.variable = passed_variable;
		}
	}
	
	//Populate forecast hour and dprog/dt arrays for this run and frame
	populateMenu('init');
	populateMenu('valid');
	populateMenu('domain');
	populateMenu('variable');
	populateMenu('maptype');
	
	//Populate the frames and runs arrays
	frames = [];
	runs = [];
	for(i=minFrame;i<=maxFrame;i=i+incrementFrame){frames.push(i);}
	for(i=minRun;i<=maxRun;i=i+incrementRun){runs.push(i);}
	
	//Preload images and display map
	preload(imageObj);
	showImage();
	
	//Update mobile display for swiping
	updateMobile();

}

var xInit = null;                                                        
var yInit = null;                  
var xPos = null;
var yPos = null;

</script>

</body>
</html>

