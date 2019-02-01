var ListController = function(selector, url, numItems, fond, itemTotal, fadeDuration)
{
	
	this.ajaxParams = {num:numItems};
	if(fond != undefined)
		this.ajaxParams.fond = fond;
	
	if(fadeDuration != undefined)
		this.fadeDuration = fadeDuration;
	else
		this.fadeDuration = 500;
	
	this.listTarget = null;
	this.prevButton = null;
	this.nextButton = null;
	
	this.ajaxUrl = url; 
	this.numItems = numItems;
	this.itemTotal = itemTotal;
	this.indexMax = Math.ceil(itemTotal / numItems) * numItems;
	this.indexMin = this.indexMax - numItems;
	
	this.itemData = {};
	this.currentIndex = 0;
	this.initialize(selector);
	var n = this.listTarget.children("li").length;
	for(; n < this.numItems; n++)
		this.listTarget.append($("<li></li>"));
	this.itemData[this.currentIndex] = this.listTarget;
};
ListController.prototype = 
{
	initialize:function(selector)
	{
		var ctn = $(selector).first();
		var list = ctn.find("ul").first();
		this.setup(
				list,
				ctn.find(".prevBtn").first(),
				ctn.find(".nextBtn").first()
		);
	},
	
	setup:function(
			listTarget,
			prevButton, 
			nextButton)
	{
		this.listTarget = listTarget;
		this.prevButton = prevButton;
		this.nextButton = nextButton;
		
		var nextEnabled = this.itemTotal > this.numItems;
		this.setButtonEnabled(this.prevButton, nextEnabled);
		this.setButtonEnabled(this.nextButton, nextEnabled);
		if(nextEnabled)
		{
			this.nextButton.click($.proxy(this.nextClickHandler, this));
			this.prevButton.click($.proxy(this.prevClickHandler, this));
		}
	},
	
	prevClickHandler:function()
	{
		var newIndex = this.currentIndex - this.numItems;
		if(newIndex < 0)
			newIndex = this.indexMin;
		console.log("\t[-] currentIndex:"+this.currentIndex + " > " + newIndex);
		this.currentIndex = newIndex;
		
		this.setupAjax();
	},
	
	nextClickHandler:function()
	{
		/*
num:6, total:24
	24
		 */
		var newIndex = this.currentIndex + this.numItems;
		if(newIndex >= this.indexMax)
			newIndex = 0;
		console.log("\t[+] currentIndex:"+this.currentIndex + " > " + newIndex);
		this.currentIndex = newIndex;
		this.setupAjax();
	},
	
	dataFadeOutHandler:function()
	{
		var next = this.itemData[this.currentIndex];
		next.css("opacity", 0);
		this.updateList(next, false);
		this.listTarget.fadeTo(this.fadeDuration/2, 1);
	},
	setupAjax:function()
	{
		console.log("setupAjax[" + this.currentIndex + "]");
		if(this.itemData.hasOwnProperty(this.currentIndex))
		{
			
			this.listTarget.fadeTo(this.fadeDuration/2, 0, $.proxy(this.dataFadeOutHandler, this));
			return;
		}
		
		var modal = $('<div class="ajaxModal"}"></div>');
		modal.fadeTo(0, 0);
		this.listTarget.parent().append(modal);
		modal.fadeTo(200, .2);
		var data = {type:"GET", url:this.ajaxUrl, context:this};
		this.ajaxParams.start = this.currentIndex;
		
		data.data = this.ajaxParams;
		
		var request = $.ajax(data);
		request.fail(this.ajaxFaultHandler);
		request.done(this.ajaxResultHandler);
	},
	
	ajaxResultHandler:function(result)
	{
		var node = $(result);
		
		
		var n = node.children("li").length;
		for(; n < this.numItems; n++)
			node.append($("<li></li>"));
		
		this.itemData[this.currentIndex] = node;
		this.updateList(node, true);
		this.listTarget.parent().find(".ajaxModal").fadeTo(200, 0, function(){$(this).remove();});
	},
	
	ajaxFaultHandler:function()
	{
		try {
			console.log("ListController::ajax request FAULT");
		} catch (e) {
			// TODO: handle exception
		}
	},
	
	updateList:function(data, animate)
	{
		if(animate)
		{
			var imgs = data.find("img");
			var n = imgs.length;
			var i;
			var img;
			var completeHandler = function(){$(this).fadeTo(this.fadeDuration, 1)};
			for(i=0; i<n; i++)
			{
				img = $(imgs[i]);
				if( ! img.complete || img.height() < 1)
				{
					img.css("opacity", 0);
					img.one("load", completeHandler);
				}
			}
		}
		this.listTarget.replaceWith(data);
		this.listTarget = data;
	},
	
	setButtonEnabled:function(target, value)
	{
		if( ! value)
			target.attr("disabled", "disabled");
		else
			target.removeAttr("disabled");
	}
};

