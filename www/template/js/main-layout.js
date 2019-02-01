
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-63270016-1', 'auto');
  ga('send', 'pageview');


function invalidateWindowSize(event)
{
	var vh = window.innerHeight;
	var ctn = $("#content");
	var top = ctn.position().top;
	top += parseInt(ctn.css("margin-top"));
	ctn.css("min-height", Math.floor(vh - top - $("footer").height() - 17) + "px");
		
	var elmt = $("#modal .popUp.on .content");
	if(elmt !== undefined && elmt.length)
		elmt.css("max-height", (vh - 100) + "px");
}

window.onresize = invalidateWindowSize;