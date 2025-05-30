
<!DOCTYPE html
<html>
<head>
<meta charset="UTF-8">
<title>GEFSv12 Retros</title>
<link rel="stylesheet" type="text/css" href="../../gefsv12/retros/main.css">
<script src="jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="functions.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--DAP script -->
        <script src="//dap.digitalgov.gov/Universal-Federated-Analytics-Min.js?agency=DOC&amp;subagency=NOAA" id="_fed_an_ua_tag"></script>


</head>

<body>



<!-- Head element -->
<div class="page-top">
	<span><a style="color:#ffffff">GEFSv12 Retrospective Case Studies</a></span>
</div>

<!-- Top menu -->
<div class="page-menu"><div class="table">
	
<!--        <div class="element">
                <span class="bold">Valid:</span>
                <select id="validtime" onchange="changeValidtime(this.value);"></select>
            </div> 

        <div class="element">
                <span class="bold">Season:</span>
                <select id="season" onchange="changeSeason(this.value)"></select>
        </div>
        <div class="element">
                <span class="bold">Region:</span>
                <select id="domain" onchange="changeDomain(this.value)"></select>
        </div>
        <div class="element">
                <span class="bold">Statistic:</span>
                <select id="variable" onchange="changeVariable(this.value)"></select>
        </div>
        <div class="element">
                <span class="bold">Level:</span>
                <select id="level" onchange="changeLevel(this.value)"></select>
        </div>

-->

<!-- /Top menu -->
</div></div>

<!-- Middle menu -->
<!-- <div class="page-middle" id="page-middle"> -->
<!-- /Middle menu -->
</div>


<div id="loading"><img style="width:100%" src="loading.png"></div>


<body>
<div id="pageContents">
<center><font size="5">
<img src="https://www.emc.ncep.noaa.gov/users/verification/style/images/ncep_logo.gif" alt="" width="200" />
<br>
<br>
The National Centers for Environmental Prediction (NCEP) Global Ensemble Forecast System (GEFS) accounts for uncertainty in weather forecasts by generating an ensemble of multiple forecasts, each slightly different (i.e., perturbed) from the original forecast, in order to capture the range of possible forecast outcomes.
<br>
<br>
<a style="color:#ff0000">This webpage contains graphics from the <u>GEFSv12 retrospective case studies</u>.
<br>
Please use the links on the left to navigate to GEFSv12 verification statistics.</a>
<br>
<br>
<b>Additional Information:</b>
<br>
GEFSv12 will be the next operational version of GEFS. GEFSv12 will be run 4-times daily (0000, 0600, 1200, and 1800 UTC) with 31 ensemble members out to 16 days. One GEFSv12 run each day (i.e., 0000 UTC) will be extended out to 35 days.  
<br>
<br>
The official evaluation of GEFSv12 is scheduled for Q2FY20.
<br>
<a style="color:#1917BE"><b>GEFSv12 Official Evaluation Webpage: </b><a href=https://www.emc.ncep.noaa.gov/users/meg/gefsv12/ target="_blank">[click here]</a>.</a>
</center>
</div>
</body>



<!-- Image -->
<div id="page-map">
	<image name="map" style="width:100%">
</div>

<!-- /Footer -->
<div class="page-footer">
        <span></div>


<script type="text/javascript">
//====================================================================================================
//User-defined variables
//====================================================================================================

//Global variables
var minFrame = 0; //Minimum frame for every variable
var maxFrame = 26; //Maximum frame for every variable
var incrementFrame = 1; //Increment for every frame

var startFrame = 0; //Starting frame

var cycle = 2018100600;

/*
When constructing the URL below, DDD = domain, VVV = variable, LLL = level, SSS = season, Y = frame number.
For X and Y, labeling one X or Y represents an integer (e.g. 0, 10, 20). Multiple of these represent a string
format (e.g. XX = 00, 06, 12 --- XXX = 000, 006, 012).
*/

var url = "https://www.emc.ncep.noaa.gov/gmb/yluo/naefs/VRFY_STATS/NCEP_NCEPb/DDDzLLL_VVV_SSS.gif";
/* var url = "https://www.emc.ncep.noaa.gov/mmb/gmanikin/fv3gfs/20180301/fv3_DDD_VVV_2018030100_0Y.png"; */
/*  var url = "https://www.emc.ncep.noaa.gov/users/Alicia.Bentley/fv3gefs/2018030100/images/DDD/mean_diff/VVV_Y.png"; */

//====================================================================================================
//Add variables & domains
//====================================================================================================

var variables = [];
var domains = [];
var levels = [];
var seasons = [];
var maptypes = [];
var validtimes = []; 



variables.push({
        displayName: "ROC curve",
        name: "roc",
});
variables.push({
        displayName: "Economic Values",
        name: "eval",
});
variables.push({
        displayName: "Ranked Prob Skill Score",
        name: "rpss",
});
variables.push({
        displayName: "Brier Skill Score",
        name: "bss",
});
variables.push({
        displayName: "CRP Score",
        name: "crp",
});
variables.push({
        displayName: "CRP Skill Score",
        name: "crps",
});
variables.push({
        displayName: "RMSE/Ensemble Spread",
        name: "rms",
});
variables.push({
        displayName: "Mean/Absolute Error",
        name: "err",
});
variables.push({
        displayName: "Anomaly Correlation",
        name: "pac",
});
variables.push({
        displayName: "Histogram Distrib.",
        name: "his",
});



domains.push({
        displayName: "N. Hemisphere",
        name: "nh",
});
domains.push({
        displayName: "S. Hemisphere",
        name: "sh",
});
domains.push({
        displayName: "Tropics",
        name: "tr",
});







levels.push({
        displayName: "500 hPa",
        name: "500",
});
levels.push({
        displayName: "1000 hPa",
        name: "1000",
});




seasons.push({
        displayName: "Spring 2019",
        name: "spr2019",
});
seasons.push({
        displayName: "Winter 2018/2019",
        name: "win1819",
});
seasons.push({
        displayName: "Fall 2018",
        name: "fal2018",
});
seasons.push({
        displayName: "Summer 2018",
        name: "sum2018",
});
seasons.push({
        displayName: "Spring 2018",
        name: "spr2018",
});
seasons.push({
        displayName: "Winter 2017/2018",
        name: "win1718",
});
seasons.push({
        displayName: "Fall 2017",
        name: "fal2017",
});
seasons.push({
        displayName: "Summer 2017",
        name: "sum2017",
});
seasons.push({
        displayName: "Spring 2017",
        name: "spr2017",
});


validtimes.push({
        displayName: "0000 UTC",
        name: "00Z",
});
validtimes.push({
        displayName: "1200 UTC",
        name: "12Z",
});


maptypes.push({
        url: "geo_00Z.php",
        displayName: "0000 UTC",
        name: "geo_00Z",
});
maptypes.push({
        url: "geo_12Z.php",
        displayName: "1200 UTC",
        name: "geo_12Z",
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
		variable: "roc",
		domain: "nh",
		level: "500",
                season: "spr2019",
//                validtime: "00Z",
//                frame: startFrame,
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
	populateMenu('domain');
	populateMenu('level');
	populateMenu('season');
 //       populateMenu('validtime');
	
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


</body></html>
