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
	this.titleField = null;
	this.infoField = null;	
	
	this.ajaxUrl = url; 
	this.numItems = numItems;
	this.itemTotal = itemTotal;
	this.itemData = {};
	this.currentIndex = 0;
	this.initialize(selector);
	var n = this.listTarget.children("li").length;
	for(; n < this.numItems; n++)
		this.listTarget.append($("<li></li>"));
	this.itemData[this.currentIndex] = this.listTarget;
	this.updateInfoField();
};
ListController.prototype = 
{
	initialize:function(selector)
	{
		var ctn = $(selector).first();
		var list = ctn.find("ul").first();
		var title = ctn.find(".titleField").first();
		ctn = ctn.find(".ajaxProdButtonBar").first();
		
		this.setup(list,
				ctn.find(".prevBtn").first(),
				ctn.find(".nextBtn").first(), 
				title, 
				ctn.find(".prodInfoField").first());
	},
	
	setup:function(
			listTarget,
			prevButton, nextButton,
			titleField, infoField)
	{
		this.listTarget = listTarget;
		this.prevButton = prevButton;
		this.nextButton = nextButton;
		this.titleField = titleField;
		this.infoField = infoField;
		
		this.setButtonEnabled(this.prevButton, false);
		var nextEnabled = this.itemTotal > this.numItems;
		this.setButtonEnabled(this.nextButton, nextEnabled);
		if(nextEnabled)
		{
			this.nextButton.click($.proxy(this.nextClickHandler, this));
			this.prevButton.click($.proxy(this.prevClickHandler, this));
		}
	},
	
	prevClickHandler:function()
	{
		this.currentIndex -= this.numItems;
		if(this.currentIndex == 0)
		{
			this.setButtonEnabled(this.prevButton, false);
		}
		this.setButtonEnabled(this.nextButton, true);
		
		this.setupAjax();
	},
	
	nextClickHandler:function()
	{
		this.currentIndex += this.numItems;
		this.setButtonEnabled(this.prevButton, true);
		if(this.currentIndex + this.numItems >= this.itemTotal)
			this.setButtonEnabled(this.nextButton, false);
		
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
//			data.find("img").css("opacity", 0);
//			data.find("img").imgLoad(function(){$(this).fadeTo(500,1)});
		}
		this.listTarget.replaceWith(data);
		this.listTarget = data;
		this.updateInfoField();
	},
	
	updateInfoField:function()
	{
		var a = this.currentIndex + 1;
		var b = Math.min(this.currentIndex + this.numItems, this.itemTotal);
		this.infoField.text(a + "-" + b + " / " + this.itemTotal);
	},
	
	setButtonEnabled:function(target, value)
	{
		if( ! value)
			target.attr("disabled", "disabled");
		else
			target.removeAttr("disabled");
	}
};

