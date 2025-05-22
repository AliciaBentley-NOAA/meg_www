<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>GFSv16 Soundings</title>
<link rel="stylesheet" type="text/css" href="../../main.css">
<script src="https://www.emc.ncep.noaa.gov/users/meg/physics_eval/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="../../functions_soundings_6h.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--DAP script -->
        <script src="//dap.digitalgov.gov/Universal-Federated-Analytics-Min.js?agency=DOC&amp;subagency=NOAA" id="_fed_an_ua_tag"></script>


</head>

<body>



<!-- Head element -->
<div class="page-top">
	<span><a href="https://www.emc.ncep.noaa.gov/users/meg/gfsv16/" style="color:#FFFFFF">GFSv16 Evaluation</a> | <a href="https://www.emc.ncep.noaa.gov/users/meg/gfsv16/soundings/" style="color:#FFFFFF">GFSv16 Retro Soundings</a> | TC Florence (Sept 2018)</a></span>
</div>

<!-- Top menu -->
<div class="page-menu"><div class="table">
	
        <div class="element">
                <span class="bold">Initialized:</span>
                <select id="domain" onchange="changeDomain(this.value);"></select>
        </div>
	<div class="element">
                <span class="bold">Valid:</span>
                <select id="valid" onchange="changeValid(this.value)"></select>
        </div>
	<div class="element">
		<span class="bold">Sounding Location:</span>
		<select id="variable" onchange="changeVariable(this.value)"></select>
	</div>


<!-- /Top menu -->
</div></div>

<!-- Middle menu -->
<div class="page-middle" id="page-middle">
Up/Down arrow keys = Change sounding location | Left/Right arrow keys = Change valid time
<!-- /Middle menu -->
</div>

<div id="loading"><img style="width:100%" src="https://www.emc.ncep.noaa.gov/users/Alicia.Bentley/loops/settings/loading.png"></div>

<!-- Image -->
<div id="page-map">
	<image name="map" style="width:100%">
</div>

<!-- /Footer -->
<div class="page-footer">
</div>

<script type="text/javascript">
//====================================================================================================
//User-defined variables
//====================================================================================================
12
//Global variables
var minFrame = 0; //Minimum frame for every variable
var maxFrame = 20; //Maximum frame for every variable
var incrementFrame = 1; //Increment for every frame


var minRun = 0; //Latest run (should be zero)
var maxRun = 0; //Number of hours difference between the last available run & current run
var incrementRun = 6; //Interval between each run (6 hours)


var startFrame = 0; //Starting frame

var cycle = "2018090600"
var casename = "Florence2018"

/*
When constructing the URL below, DDD = domain, VVV = variable, XXX = variable, Y = frame number.
For X and Y, labeling one X or Y represents an integer (e.g. 0, 10, 20). Multiple of these represent a string
format (e.g. XX = 00, 06, 12 --- XXX = 000, 006, 012).
*/

var url = "https://www.emc.ncep.noaa.gov/users/meg/gfsv16/soundings/loops/"+casename+"/DDD_VVV_Y.gif";
/* var url = "http://www.emc.ncep.noaa.gov/mmb/gmanikin/fv3gfs/20180301/fv3_DDD_VVV_2018030100_0Y.png"; */
/*  var url = "http://www.emc.ncep.noaa.gov/users/Alicia.Bentley/fv3gefs/2018030100/images/DDD/mean_diff/VVV_Y.png"; */

//====================================================================================================
//Add variables & domains
//====================================================================================================
var domains = [];
var variables = [];

domains.push({
        displayName: "00Z Thu 9/06/18",
        name: "2018090600",
});
domains.push({
        displayName: "12Z Sun 9/09/18",
        name: "2018090912",
});
domains.push({
        displayName: "00Z Wed 9/12/18",
        name: "2018091200",
});
domains.push({
        displayName: "12Z Sat 9/15/18",
        name: "2018091512",
});


variables.push({
        displayName: "ABR - ABERDEEN, SD (726590)",
        name: "726590",
});
variables.push({
        displayName: "ABQ - ALBUQUERQUE, NM (723650)",
        name: "723650",
});
variables.push({
        displayName: "ALB - ALBANY, NY (725180)",
        name: "725180",
});
variables.push({
        displayName: "AMA - AMARILLO, TX (723630)",
        name: "723630",
});
variables.push({
        displayName: "APX - GAYLORD, MI (726340)",
        name: "726340",
});
variables.push({
        displayName: "BIS - BISMARK, ND (727640)",
        name: "727640",
});
variables.push({
        displayName: "BMX - SHELBY, AL (722300)",
        name: "722300",
});
variables.push({
        displayName: "BNA - NASHVILLE, TN (723270)",
        name: "723270",
});
variables.push({
        displayName: "BOI - BOISE, ID (726810)",
        name: "726810",
});
variables.push({
        displayName: "BRO - BROWNSVILLE, TX (722500)",
        name: "722500",
});
variables.push({
        displayName: "BUF - BUFFALO, NY (725280)",
        name: "725280",
});
variables.push({
        displayName: "CAR - CARIBOU, ME (727120)",
        name: "727120",
});
variables.push({
        displayName: "CHH - CHATHAM, MA (744940)",
        name: "744940",
});
variables.push({
        displayName: "CHS - CHARLESTON, SC (722080)",
        name: "722080",
});
variables.push({
        displayName: "CRP - CORPUS CHRISTI, TX (722510)",
        name: "722510",
});
variables.push({
        displayName: "DDC - DODGE CITY, KS (724510)",
        name: "724510",
});
variables.push({
        displayName: "DNR - DENVER, CO (724690)",
        name: "724690",
});
variables.push({
        displayName: "DRT - DEL RIO, TX (722610)",
        name: "722610",
});
variables.push({
        displayName: "DTX - DETROIT, MI (726320)",
        name: "726320",
});
variables.push({
        displayName: "DVN - QUAD CITIES, IA (744550)",
        name: "744550",
});
variables.push({
        displayName: "EPZ - SANTA TERESA, NM (723640)",
        name: "723640",
});
variables.push({
        displayName: "FGZ - FLAGSTAFF, AZ (723760)",
        name: "723760",
});
variables.push({
        displayName: "FWD - FORT WORTH, TX (722490)",
        name: "722490",
});
variables.push({
        displayName: "GGW - GLASGOW, MT (727680)",
        name: "727680",
});
variables.push({
        displayName: "GJT - GRAND JUNCTION, CO (724760)",
        name: "724760",
});
variables.push({
        displayName: "GRB - GREEN BAY, WI (726450)",
        name: "726450",
});
variables.push({
        displayName: "GSO - GREENSBORO, NC (723170)",
        name: "723170",
});
variables.push({
        displayName: "GYX - GRAY, ME (743890)",
        name: "743890",
});
variables.push({
        displayName: "IAD - STERLING, VA (724030)",
        name: "724030",
});
variables.push({
        displayName: "ILN - WILMINGTON, OH (724260)",
        name: "724260",
});
variables.push({
        displayName: "ILX - LINCOLN, IL (745600)",
        name: "745600",
});
variables.push({
        displayName: "INL - INTERNATIONAL FALLS, MN (727470)",
        name: "727470",
});
variables.push({
        displayName: "JAN - JACKSON, MS (722350)",
        name: "722350",
});
variables.push({
        displayName: "JAX - JACKSONVILLE, FL (722060)",
        name: "722060",
});
variables.push({
        displayName: "KEY - KEY WEST, FL (722010)",
        name: "722010",
});
variables.push({
        displayName: "LBF - NORTH PLATTE, NE (725620)",
        name: "725620",
});
variables.push({
        displayName: "LCH - LAKE CHARLES, LA (722400)",
        name: "722400",
});
variables.push({
        displayName: "LIX - SLIDELL, LA (722330)",
        name: "722330",
});
variables.push({
        displayName: "LKN - ELKO, NV (725820)",
        name: "725820",
});
variables.push({
        displayName: "LZK - LITTLE ROCK, AR (723400)",
        name: "723400",
});
variables.push({
        displayName: "MAF - MIDLAND, TX (722650)",
        name: "722650",
});
variables.push({
        displayName: "MFL - MIAMI, FL (722020)",
        name: "722020",
});
variables.push({
        displayName: "MFR - MEDFORD, OR (725970)",
        name: "725970",
});
variables.push({
        displayName: "MHX - NEWPORT, NC (723050)",
        name: "723050",
});
variables.push({
        displayName: "MPX - CHANHASSEN, MN (726490)",
        name: "726490",
});
variables.push({
        displayName: "NKX - MIRAMAR, CA (722930)",
        name: "722930",
});
variables.push({
        displayName: "NSTU - PAGO PAGO, SAMOA (917650)",
        name: "917650",
});
variables.push({
        displayName: "OAK - OAKLAND, CA (724930)",
        name: "724930",
});
variables.push({
        displayName: "OAX - OMAHA, NE (725580)",
        name: "725580",
});
variables.push({
        displayName: "OKX - BROOKHAVEN, NY (725010)",
        name: "725010",
});
variables.push({
        displayName: "OTX - SPOKANE, WA (727860)",
        name: "727860",
});
variables.push({
        displayName: "OUN - NORMAN, OK (723570)",
        name: "723570",
});
variables.push({
        displayName: "PABE - BETHEL, AK (702190)",
        name: "702190",
});
variables.push({
        displayName: "PACD - COLD BAY, AK (703160)",
        name: "703160",
});
variables.push({
        displayName: "PADQ - KODIAK, AK (703500)",
        name: "703500",
});
variables.push({
        displayName: "PAFA - FAIRBANKS, AK (702610)",
        name: "702610",
});
variables.push({
        displayName: "PAKN - KING SALMON, AK (703260)",
        name: "703260",
});
variables.push({
        displayName: "PAMC - MCGRATH, AK (702310)",
        name: "702310",
});
variables.push({
        displayName: "PANC - ANCHORAGE, AK (702730)",
        name: "702730",
});
variables.push({
        displayName: "PAOM - NOME, AK (702000)",
        name: "702000",
});
variables.push({
        displayName: "PAOT - KOTZEBUE, AK (701330)",
        name: "701330",
});
variables.push({
        displayName: "PASN - SAINT PAUL ISLAND, AK (703080)",
        name: "703080",
});
variables.push({
        displayName: "PASY - SHEMYA, AK (704140)",
        name: "704140",
});
variables.push({
        displayName: "PKMJ - MAJURO ATOLL, MARSHALL ISLANDS (913760)",
        name: "913760",
});
variables.push({
        displayName: "PGUM - GUAM INTL ARPT, GUAM (912120)",
        name: "912120",
});
variables.push({
        displayName: "PHLI - LIHUE, HI (911650)",
        name: "911650",
});
variables.push({
        displayName: "PHTO - HILO, HI (912850)",
        name: "912850",
});
variables.push({
        displayName: "PIT - PITTSBURGH, PA (725200)",
        name: "725200",
});
variables.push({
        displayName: "PTRO - KOROR, PALAU (914080)",
        name: "914080",
});
variables.push({
        displayName: "PTYA - YAP INTL ARPT, YAP (914130)",
        name: "914130",
});
variables.push({
        displayName: "RAP - RAPID CITY, SD (726620)",
        name: "726620",
});
variables.push({
        displayName: "REV - RENO, NV (724890)",
        name: "724890",
});
variables.push({
        displayName: "RNK - ROANOKE, VA (723180)",
        name: "723180",
});
variables.push({
        displayName: "RIW - RIVERTON, WY (726720)",
        name: "726720",
});
variables.push({
        displayName: "SGF - SPRINGFIELD, MO (724400)",
        name: "724400",
});
variables.push({
        displayName: "SHV - SHREVEPORT, LA (722480)",
        name: "722480",
});
variables.push({
        displayName: "SLC - SALT LAKE CITY, UT (725720)",
        name: "725720",
});
variables.push({
        displayName: "SLE - SALEM, OR (726940)",
        name: "726940",
});
variables.push({
        displayName: "TBW - TAMPA, FL (722100)",
        name: "722100",
});
variables.push({
        displayName: "TFX - GREAT FALLS, MT (727760)",
        name: "727760",
});
variables.push({
        displayName: "TJSJ - SAN JUAN, PR (785260)",
        name: "785260",
});
variables.push({
        displayName: "TLH - TALLAHASSEE, FL (722140)",
        name: "722140",
});
variables.push({
        displayName: "TOP - TOPEKA, KS (724560)",
        name: "724560",
});
variables.push({
        displayName: "TUS - TUCSON, AZ (722740)",
        name: "722740",
});
variables.push({
        displayName: "UIL - QUILLAYUTE, WA (727970)",
        name: "727970",
});
variables.push({
        displayName: "VEF - LAS VEGAS, NV (723880)",
        name: "723880",
});
variables.push({
        displayName: "VBG - VANDENBERG, CA (723930)",
        name: "723930",
});
variables.push({
        displayName: "WAL - WALLOPS ISLAND, VA (724020)",
        name: "724020",
});
variables.push({
        displayName: "XMR - CAPE CANAVERAL, FL (747940)",
        name: "747940",
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
		domain: cycle,
                variable: "723170",
		frame: startFrame,
	};
	
        //Change variable based on passed argument, if any
        var passed_variable = "";
        if(passed_variable!=""){
                if(searchByName(passed_variable,variables)>=0){
                        imageObj.variable = passed_variable;
                }
        }

//        cycle = document.getElementById("domain").value;
	
	//Populate forecast hour and dprog/dt arrays for this run and frame
//	populateMenu('init')
	populateMenu('variable');
	populateMenu('valid');
        populateMenu('domain');
	
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
