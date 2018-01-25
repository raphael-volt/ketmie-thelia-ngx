function svgMouseStateHandler(target, stateName)
{
	if(target.attr("disabled") != "disabled")
	{
		target.attr("class", stateName);
	}
}

var ImgPopUp = function(screenPadding, fadeDuration)
{
	if(screenPadding == undefined)
		screenPadding = 35;
	
	if(fadeDuration == undefined)
		fadeDuration = 300;
	
	this.screenPadding = screenPadding;
	
	this.urlSite = null;
	this.linkButtonLabel = "Voir le produit";
	this.popUp = null;
	this.list = null;
	this.linkList = null;
	this.urls = null;
	this.numUrls = 0;
	this.btnFlag = false;
	this.prevBtn = null;
	this.nextBtn = null;
	this.closeBtn = null;
	this.linkTarget = null;
	this.img = null;
	this.imgW = 0;
	this.imgH = 0;
	
	this.modal = new Modal(undefined, fadeDuration);
	
	this.currentId = -1;
	this.fadeDuration = fadeDuration;
	
	this.resizeProxy = $.proxy(this.windowResizeHandler, this);
};

ImgPopUp.prototype = 
{
	linkItemClickHandler:function(index, event)
	{
		event.preventDefault();
		this.open(index);
		return false;
	},
	
	addListItemHandlers:function(list)
	{
		var linkList = list.find("a");
		var urls = [];
		var i;
		var item;
		var n = linkList.length;
		for(i=0; i<n; i++)
		{
			item = $(linkList[i]);
			urls.push(item.attr("href"));
			item.on("click", $.proxy(this.linkItemClickHandler, this, i));
		}
		return urls;
	},
	setup:function(popUp, list)
	{
		this.list = list;
		this.urls = this.addListItemHandlers(list);
		this.numUrls = this.urls.length;
		
		this.imgW = this.imgH = 0;
		this.prevBtn = popUp.find(".btn-coll.left > svg").first();
		this.nextBtn = popUp.find(".btn-coll.right > svg").first();
		this.closeBtn = popUp.find(".img-box > svg").first();
		this.img = popUp.find(".img-box > img").first();
		
		this.popUp = popUp;
		this.currentId = -1;
		
		this.setOpacity(popUp, 0);
		this.setImgOpacity(0);
		this.initButtons();
	},

	setImgOpacity:function(value, animate, proxy)
	{
		if(animate === true)
		{
			this.img.parent().fadeTo(this.fadeDuration, value, proxy);
		}
		else
			this.setOpacity(this.img.parent(), value);
	},
	
	closeClickHandler:function()
	{
		this.removeModal(true);
	},
	
	prevClickHandler:function()
	{
		this.loadNext(-1);
	},
	
	nextClickHandler:function()
	{
		this.loadNext(1);
	},
	
	loadNext:function(inc)
	{
		inc = this.currentId + inc;
		if(inc >= this.numUrls)
			inc = 0;
		if(inc < 0)
			inc = this.numUrls - 1;
		
		this.currentId = inc;
		this.setImgOpacity(0, true, $.proxy(this.loadImage, this));
	},
	
	initButtons:function()
	{
		var enabled = this.numUrls > 1;
		this.enableButton(this.prevBtn, enabled);
		this.enableButton(this.nextBtn, enabled);
		
		if(enabled && ! this.btnFlag)
		{
			this.btnFlag = true;
			this.prevBtn.on("click", $.proxy(this.prevClickHandler, this));
			this.nextBtn.on("click", $.proxy(this.nextClickHandler, this));
		}
		if( ! enabled && this.btnFlag)
		{
			this.btnFlag = false;
			this.prevBtn.off("click", $.proxy(this.prevClickHandler, this));
			this.nextBtn.off("click", $.proxy(this.nextClickHandler, this));
		}
	},

	enableButton:function(target, value)
	{
		var d = "disabled";
		var disabled = target.attr(d);
		if(disabled == undefined)
			disabled = false;
		else
			disabled = true;
		
		if(disabled != value)
			return false;
		
		if(value)
			target.removeAttr(d);
		else
			target.attr(d, d);
		
		this.setOpacity(target, value ? 1:0);
		
		return true;
	},	
	
	open:function(newIndex)
	{
		if(this.numUrls < 1)
			return;
		
		if(newIndex === undefined)
			newIndex = this.currentId;
		
		if(newIndex < 0 || newIndex > this.numUrls)
			newIndex = 0;
		
		var indexChange = newIndex != this.currentId; 
		this.currentId = newIndex;
		
		this.setImgOpacity(0, false);		
		
		this.setupModal();
		
		if(this.imgW != 0)
		{
			if( ! indexChange)
			{
				this.setImgOpacity(1, false);
				this.invalidateProductLink(this.currentId);
				return;
			}
		}
		this.loadImage();
	},
	invalidateProductLink:function(index)
	{
		var item = $(this.list.find("a").get(index));
		if(this.linkTarget)
		{
			this.linkTarget.remove();
			this.linkTarget = null;
		}
		var links = item.find("input");
		if(links.length)
		{
			var link = links.first();
			var id = link.val();
			this.linkTarget = $("<form action=\"" + this.urlSite + "\" method=\"get\"/>");
			this.linkTarget.append("<input type=\"hidden\" name=\"fond\" value=\"produit\"/>");
			this.linkTarget.append("<input type=\"hidden\" name=\"id_produit\" value=\"" + link.val() + "\"\/>");
			this.linkTarget.append($("<button type=\"submit\">" + this.linkButtonLabel + "</button>"));
			this.popUp.find(".img-box").first().append(this.linkTarget);
		}
	},
	
	loadImage:function()
	{
		this.img.one("load", $.proxy(this.imgCompleteHandler, this));
		this.img.css("width", "auto");
		this.img.css("height", "auto");
		this.img.attr("src", this.urls[this.currentId]);
	},
	
	imgCompleteHandler:function()
	{
		this.imgW = this.img.width();
		this.imgH = this.img.height();
		this.windowResizeHandler();
		this.setImgOpacity(1, true);
		this.invalidateProductLink(this.currentId);
	},
	
	setOpacity:function(target, opacity)
	{
		target.css("opacity", opacity);
		
	},
	
	removeModal:function(animate)
	{
		if(animate == undefined)
			animate = true;
		this.modal.hide(animate);
		if(animate)
		{
			this.popUp.fadeTo(this.fadeDuration, 0, $.proxy(this.removeModalComplete, this));
		}
		else
			this.removeModalComplete();
	},
	
	removeModalComplete:function()
	{
		this.setImgOpacity(0, false);
		
		this.setOpacity(this.popUp, 0);
		this.popUp.removeClass("on");
		this.popUp.removeAttr("style");
		
		var l = this.popUp.find("svg.btn");
		l.off("mouseover");
		l.off("mouseout");
		l.off("mousedown");
		l.off("mouseup");
		svgMouseStateHandler(this.closeBtn, "btn");
		$(window).off("resize", $.proxy(this.windowResizeHandler, this));
	},
	
	setupModal:function()
	{
		this.setOpacity(this.popUp, 0);
		this.popUp.addClass("on");

		this.closeBtn.one("click", $.proxy(this.closeClickHandler, this));
		var l = this.popUp.find("svg.btn");
		l.on("mouseover", function(){svgMouseStateHandler($(this), "btn active");});
		l.on("mouseout", function(){svgMouseStateHandler($(this), "btn");});
		l.on("mousedown", function(){svgMouseStateHandler($(this), "btn active down");});
		l.on("mouseup", function(){svgMouseStateHandler($(this), "btn active");});
		
		this.modal.show();
		
		this.popUp.fadeTo(this.fadeDuration, 1);
		$(window).on("resize", $.proxy(this.windowResizeHandler, this));
		this.windowResizeHandler();
	},
	
	windowResizeHandler:function()
	{		
		if(this.imgW == 0)
			return;
		
		var w = this.modal.width(); 
		var h = this.modal.height();
		var maxH = h - this.screenPadding*2;
		var iW = this.imgW;
		var iH = this.imgH;
		
		if(maxH < iH)
		{
			var sx = maxH / iH;
			this.img.css("width", Math.floor(this.imgW * sx) + "px");
			this.img.css("height", Math.floor(this.imgH * sx) + "px");
		}
		else
		{
			if(this.img.css("width") != "auto")
			{
				this.img.css("width", "auto");
				this.img.css("height", "auto");			
			}
		}
	}
};