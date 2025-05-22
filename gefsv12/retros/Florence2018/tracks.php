<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>GEFSv12 | Track Density</title>
<link rel="stylesheet" type="text/css" href="../main.css">
<script src="https://www.emc.ncep.noaa.gov/users/Alicia.Bentley/loops/settings/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="../functions_12h.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--DAP script -->
        <script src="//dap.digitalgov.gov/Universal-Federated-Analytics-Min.js?agency=DOC&amp;subagency=NOAA" id="_fed_an_ua_tag"></script>

</head>

<body>



<!-- Head element -->
<div class="page-top">
        <span><a style="color:#FFFFFF">GEFSv12 Retrospective Case Studies | Hurricane Florence (Sep 2018)</a></span>
</div>

<!-- Top menu -->
<div class="page-menu"><div class="table">
	
        <div class="element">
                <span class="bold">Initialized:</span>
                <select id="valid" onchange="changeValid(this.value)"></select>
        </div>
	<div class="element">
		<span class="bold">Version:</span>
		<select id="variable" onchange="changeVariable(this.value)"></select>
	</div>
	<div class="element">
		<span class="bold">Domain:</span>
		<select id="domain" onchange="changeDomain(this.value)"></select>
	</div>
        <div class="element">
                <span class="bold">Map Type:</span>
                <select id="maptype" onchange="changeMaptype(this.value);"></select>
        </div>



<!-- /Top menu -->
</div></div>

<!-- Middle menu -->
<div class="page-middle" id="page-middle">
<a style="color:#FF0000">Up/Down arrow keys = Change model version | Left/Right arrow keys = Change initialization time</a>
<!-- /Middle menu -->
</div>

<div id="loading"><img style="width:100%" src="https://www.emc.ncep.noaa.gov/users/Alicia.Bentley/loops/settings/loading.png"></div>

<!-- Image -->
<div id="page-map">
	<image name="map" style="width:100%">
</div>

<!-- /Footer -->
<!--<div class="page-footer"><center><br>Images created by the NCEP/EMC Model Evaluation Group (MEG).</center></div>-->


<script type="text/javascript">
//====================================================================================================
//User-defined variables
//====================================================================================================

var eventname = "Florence2018"

var cycle = 2018083112

//Global variables
var minFrame = 0; //Minimum frame for every variable
var maxFrame = 32; //Maximum frame for every variable
var incrementFrame = 1; //Increment for every frame

var startFrame = 0; //Starting frame

/*
When constructing the URL below, DDD = domain, VVV = variable, XXX = variable, Y = frame number.
For X and Y, labeling one X or Y represents an integer (e.g. 0, 10, 20). Multiple of these represent a string
format (e.g. XX = 00, 06, 12 --- XXX = 000, 006, 012).
*/

var url = "https://www.emc.ncep.noaa.gov/users/meg/gefsv12/retros/"+eventname+"/tracks/VVV_track_density_"+eventname+"_DDD_Y.png";
/* var url = "https://www.emc.ncep.noaa.gov/mmb/gmanikin/fv3gfs/20180301/fv3_DDD_VVV_2018030100_0Y.png"; */
/*  var url = "https://www.emc.ncep.noaa.gov/users/Alicia.Bentley/fv3gefs/2018030100/images/DDD/mean_diff/VVV_Y.png"; */

//====================================================================================================
//Add variables & domains
//====================================================================================================

var variables = [];
var domains = [];
var maptypes = [];





variables.push({
        displayName: "GEFSv12",
        name: "gefsv12",
});
variables.push({
        displayName: "GEFSv11",
        name: "gefsv11",
});




domains.push({
        displayName: "North Atlantic",
        name: "atlantic",
});
domains.push({
        displayName: "Carolinas",
        name: "carolinas",
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
}

//Initialize the page
function initialize(){
	
	//Set image object based on default variables
	imageObj = {
		variable: "gefsv12",
		domain: "atlantic",
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
	populateMenu('variable');
	populateMenu('valid');
	populateMenu('domain');
	populateMenu('maptype');
	
	//Populate the frames arrays
	frames = [];
	for(i=minFrame;i<=maxFrame;i=i+incrementFrame){frames.push(i);}
	
	//Predefine empty array for preloading images
	for(i=0; i<variables.length; i++){
		variables[i].images = [];
		variables[i].loaded = [];
		variables[i].dprog = [];
	}
	
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

<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100786126); }catch(e){}</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100786126ns.gif" /></p></noscript>


</body></html>
