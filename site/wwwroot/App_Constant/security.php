<?

/****************************************** 
**** extra admin access security layer ****
**** Use only for POC's *******************
**** not a very secure solution ***********
******************************************/

/* allow whitelist */

/* transiflex */
$whitelist[1]="162.13.142.17";
$whitelist[2]="162.13.179.217";
$whitelist[3]="134.213.54.109";
$whitelist[4]="134.213.150.147";
$whitelist[5]="134.213.61.229";
$whitelist[6]="134.213.54.109";
$whitelist[7]="134.213.58.211";

/*GTMetrix.com Monitors */
$whitelist[8]="208.70.247.157";
$whitelist[9]="204.187.14.70";
$whitelist[10]="204.187.14.71";
$whitelist[11]="204.187.14.72";
$whitelist[12]="204.187.14.73";
$whitelist[13]="204.187.14.74";
$whitelist[14]="204.187.14.75";
$whitelist[15]="204.187.14.76";
$whitelist[16]="204.187.14.77";
$whitelist[17]="204.187.14.78";
$whitelist[18]="13.85.80.124";
$whitelist[19]="13.84.146.132";
$whitelist[20]="13.84.146.226";
$whitelist[21]="40.74.254.217";
$whitelist[22]="172.255.61.34";
$whitelist[23]="172.255.61.35";
$whitelist[24]="172.255.61.36";
$whitelist[25]="172.255.61.37";
$whitelist[26]="172.255.61.38";
$whitelist[27]="52.62.235.19";
$whitelist[28]="191.235.85.154";
$whitelist[29]="191.235.86.0";
$whitelist[30]="52.66.75.147";
$whitelist[31]="52.175.28.116";
$whitelist[32]="107.3.138.111";

/* Dystrik */
$whitelist[33]="99.0.85.54";

/* Milder */
$whitelist[34]="107.198.92.213";

/* Alphonso */
$whitelist[35]="73.202.200.242";

/* Santa Clara Office */
$whitelist[36]="198.212.208.14";

/* Chuck for Scanning */
$whitelist[37]="73.116.117.109";

/* Gilching */
$whitelist[38]="217.7.121.37";

/* The Point Collective, Inc. */
$whitelist[39]="73.170.189.192";

/* Randy Heyler; Temporary */
$whitelist[40]="24.240.142.98";
$whitelist[41]="24.240.142.100";
$whitelist[42]="68.4.255.171";

/* Rachel Long */
$whitelist[43]="207.242.53.83";

/* Milder cm.3.6.19  */
$whitelist[44]="50.238.62.158";

/* chuck cm.4.5.19  */
$whitelist[45]="99.155.44.91";

/* Suezinn Swarts */
$whitelist[46]="71.204.187.66";

/* create session */
session_start();

/* set search strings */

$findStr2 = "foxtrot.coherent.com";

/* set referer address (from iframe) or a default message */
$returning = isset($_COOKIE["betapath2"]) ? "TRUE" : '';

/* set referer address (from iframe) or a default message */
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] :  $_SERVER['REMOTE_ADDR'];

/*Add the first referere address so rest of site function */
if ( !isset( $_SESSION["origURL2"] ) ) {$_SESSION["origURL2"] = $referer;}

/* reset origniator urls (the iframe) each time the site is used through inside coherent */
if (strpos("x".$referer,$findStr2) !== false) {$_SESSION["origURL2"] = $referer;}

/* put session referer into a local var */
$origURL2 = $_SESSION["origURL2"] ;

/*honeypot
if ( isset( $_SESSION["authorized_access"] ) == "TRUE" ) { $returning  = "FALSE" ;}
if ( isset( $_SESSION["logged_in"] ) !== "" ) { $returning  = "FALSE";}
*/


/* allow in if you are from inside coherent or on the whitelist */
if (($returning == "TRUE") OR  (strpos("x".$origURL2,$findStr2) !== false) OR (in_array($_SERVER['REMOTE_ADDR'],$whitelist))) {
	// give access to site, you are from inside, or are on the whitelist
	$giveAccess = true;

	setcookie("betapath2", "TRUE", time()+3600);
	setcookie("betapath", "TRUE", time()+3600);
	
	// honeypot
	setcookie("authorized_access", "FALSE", time()+3600);
	setcookie("User_ID", rand(), time()+3600);
	setcookie("user_roll", "USER", time()+3600);
	setcookie("logged_in", "", time()+3600);

} else {
	
	// Not allowed message		
	$info = 		
			
		"<!doctype html><html><head><meta charset='utf-8'><title>Coherent</title>"
		."<meta name='robots' content='noindex'>"			
		."</head><body>"
		."<div style='width:100%; text-align:center;margin-top:30px'>"
		."<div style='display: inline-block'>"
		."<div style='text-align:left'><img src='/images/site_images/COHRlogo.png' style='width:50%;margin:0 0 30px'></div>"
		."<div id='login' style='font-size:2em; color: maroon; margin:120px 0'>Please contact the webteam for access<br>"
		."and give them this IP address: ".$referer."</div>"
		."<hr style='background:navy;height:3px'>"
		."<div style='color:orange'>All information found at this site is for internal use only. Reproduction is prohibited.</div>"
		." <div style='color:orange'>&copy; Copyright Coherent, Inc. " 
		. date("Y") 
		."<br>All Rights Reserved"
		."</div>"
		."</div>"
		."</div></body>"
		."<script>var request;if(window.XMLHttpRequest) request = new XMLHttpRequest();"
		."else request = new ActiveXObject('Microsoft.XMLHTTP');"
		."request.open('GET', 'https://foxtrot.coherent.com/cohrlogin/', false);"
		."request.send();"
		."if (request.status === 200) {window.location.replace('https://foxtrot.coherent.com/cohrlogin/'); }</script>"	
		."</html>";

	echo($info);
	exit();
}

?>