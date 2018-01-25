
	$(".connectLink").click(function(event)
	{
		event.preventDefault();
		formController.showConnection();
		return false;
	});
	
	$(".newAccountLink").click(function(event)
	{
		event.preventDefault();
		formController.createAccount("#VARIABLE(urlsite)/?fond=ajax/form/accountFormNew");
		return false;
	});