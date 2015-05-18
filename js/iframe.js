$(document).ready(main);

function main()
{
	$('iframe').attr('src', getIframeUrl() );
	registerEvents();
	resizeIframe();
}

function getIframeUrl()
{
	var url = window.location.href;
	var param_start = url.indexOf("iframe=");
	if( param_start != -1 ) 
		iframe_url = url.substr(param_start+7,url.length-param_start-7);
	if( iframe_url.indexOf("http://") == -1) 
		iframe_url = "http://" + iframe_url;
	return iframe_url;
}

function registerEvents()
{
	$(window).resize( function() {resizeIframe();} );
	$("#close").bind("click", function(){window.location.href = $("iframe").attr("src");});
}

function resizeIframe()
{
	var toolbar = getObjHeight(document.getElementById("toolbar"));
	$("#iframe").height( WindowHeight() - toolbar);
	$("#iframe").css("margin-top", toolbar+1);
}

function WindowHeight()
{
	var de = document.documentElement;
	return self.innerHeight || 
	(de && de.clientHeight ) ||
	document.body.clientHeight;
}

function getObjHeight(obj)
{
	if( obj.offsetWidth )
	{
		return obj.offsetHeight;
	}		
	return obj.clientHeight;
}
function title()
{
	var title = $("#iframecontent").contents( ).find( "title").html();
	$( document).find("title").html(title);
}