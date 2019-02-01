	$(".radioBox input").change(
		function(event)
		{
			var btn = $("#ajouterPanier");
			if(btn.prop("disabled"))
				btn.prop("disabled", false);
		}
	);
	var l = $(".metal-select");
	if(l.length)
	{
		l.selectmenu({change:			
			function(event, ui)
			{
				var index = Number(ui.item.index);
				var ul = $("#prodForm ul.on").first();
				ul.find("input:checked").removeAttr("checked");
				ul.removeClass("on").addClass("off");
				ul = $("#prodForm ul:eq(" + index + ")").first();
				ul.removeClass("off").addClass("on");
				$("#ajouterPanier").attr("disabled", "disabled");
			}
		});
	}