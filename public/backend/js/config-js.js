// JavaScript Document
var server				= window.location.hostname;
var webRoot    			= "";


webRoot      	 		= location.protocol+"//"+server+"/";
API_URL			 		= location.protocol+"//"+server+"/albacars/api/";

if(server == '35.160.175.165' ) {
    API_URL			 		= location.protocol+"//"+server+"/portfolio/albacars/api/";
}


var imgPath    			= webRoot+"img/";
var userImagePath 		= webRoot+"app/webroot/files/userImages/";

var uploadedImagePath 		= webRoot+"app/webroot/files/";

var resizeImagePath = webRoot+'app/webroot/thumb/_thumb.php';
var resizeImagePath2 = webRoot+'app/webroot/thumb/_thumb2.php';

var cUrl 		= window.location;
var sPath 		= window.location.pathname;
var sPage 		= sPath.substring(sPath.lastIndexOf('/') + 1);   //if(sPage == 'index.php')

// if ($.browser.mozilla) 			{Globalbrowser 	= 'Firefox';}
// else if ($.browser.msie) 		{Globalbrowser  = 'Explorer';}
// else if (navigator.userAgent.match(/Chrome/i)){	  Globalbrowser	= 'Chrome';}
// else if ($.browser.safari)		{Globalbrowser  = 'Safari';}
// else if ($.browser.opera) 		{Globalbrowser 	= 'Opera';}
// else if ($.browser.camino) 		{Globalbrowser 	= 'Camino';}
// else if ($.browser.konqueror) 	{Globalbrowser 	= 'Konqueror';}
// else if ($.browser.icab) 		{Globalbrowser 	= 'iCab';}
// //else if ($.browser.netscape) 	{Globalbrowser 	= 'Netscape';}
// else if ($.browser.OmniWeb) 	{Globalbrowser 	= 'OmniWeb';}
// else   							{Globalbrowser  = 'Other';}

var GlobalUserId;
