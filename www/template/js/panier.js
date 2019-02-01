var hasPrice=false;
var rowIndex=-1;
var lastSpan=null;

$(document).ready(function() 
{
	$(".bo_tarif span").click(tableRowClickHandler);
});

function tableRowClickHandler(event)
{
	var i = $(this).parent().index();
	if(i == rowIndex)
		return;
	rowIndex = i;
	$("#declisel").prop("selectedIndex", i-1);
	if(lastSpan)
	{
		lastSpan.fadeTo(200, 1);
		lastSpan.css("cursor", "pointer");
	}
	lastSpan = $(this);
	lastSpan.fadeTo(200, .5);
	lastSpan.css("cursor", "default");
	
	if( ! hasPrice)
	{
		hasPrice = true;
		$("#boPanier").fadeTo(200, 1);
		$("#ajouterPanier").addClass("on");
		$("#ajouterPanier").css("cursor", "pointer");
	}
	i ++;
	var target = $(".head td:nth-child(" + i + ")");
	var str = target.html() + " cm / " + $(this).html() + " €";
	$("#boTarifDesc").html(str);
	$("#rowIndex").attr("value", rowIndex - 1);
	
}