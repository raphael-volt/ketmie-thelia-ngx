
var Modal = function(id, fadeDuration)
{
	if(id === undefined)
		id = "modal";
	
	if(fadeDuration === undefined)
		fadeDuration = 300;
	
	this.fadeDuration = fadeDuration;
	this.id = id;
	
	this.displayed = false;
	this.animDir = 0;
};

Modal.prototype = 
{
	
	show:function()
	{
		if(this.jq == null)
			this.jq = this.createDiv();
		
		if(this.displayed)
		{
			if(this.animDir == 1)
				return;
			else
			{
				this.animateOpacity(1);
			}
			return;
		}
		
		this.displayed = true;
		this.animDir = 1;
		
		this.jq.attr("class", "on");
		
		this.animateOpacity(1);		
	},
	
	hide:function(animate)
	{
		if(this.jq == null)
			this.jq = this.createDiv();
		
		if(animate == undefined)
			animate = true;
		if( ! this.displayed)
			return;
		if(this.animDir == -1)
			return;
		if( ! animate)
		{
			this.jq.css("opacity", 0);
			this.fadeOutCompleteHandler();
		}
		else
		{
			this.animDir = -1;
			this.animateOpacity(0, $.proxy(this.fadeOutCompleteHandler, this));		
		}
	},
	
	width:function()
	{
		if(this.jq == null)
			this.jq = this.createDiv();
		
		return this.jq.width();
	},
	
	height:function()
	{
		if(this.jq == null)
			this.jq = this.createDiv();
		
		return this.jq.height();
	},
	
	animateOpacity:function(opacity, completeHandler)
	{
		this.jq.animate(
				{opacity: opacity}, 
				this.fadeDuration, 
				"linear", 
				completeHandler);
	},
	
	fadeOutCompleteHandler:function()
	{
		this.jq.attr("class", "");//css("display", "none");
		this.displayed = false;
		this.animDir = 0;
	},
	
	getZindex:function()
	{
		return this.zIndex;
	},
	
	createDiv:function()
	{
		var div = $("#" + this.id);
		if(div.length)
			return div.first();
		
		div = $("<div id=\"" + this.id + "\"/>");
		div.css("opacity", 0);
		$("body").append(div);
		return div;
	}
};