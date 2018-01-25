
var AjaxFormController = function()
{
	this.redirect = null;
	
	this.srcPwd = null;
	this.initPwd = null;
	this.srcEmail = null;
	
	this.createAccountFlag = false;
	this.updateAccountFlag = false;
	this.connectFlag = false;
	this.form = null;
	this.ajaxUrl = null;
	this.popup = null;
	this.submitButton = null;
	this.closeButton = null;
	
	this.validateFlag = false;
	this.clientId = -1;
	this.addressId = -1;
	this.redirectOnConnected = null;
	this.updateCaptcha = false;

	this.modal = new Modal();
	this.connectPanel=null;
};

AjaxFormController.prototype = 
{
	alert:function(popup)
	{
		this.popup = popup;
		this.showModal();
		this.addPopup(false, this.hidePopup);
	},
	
	getConnectPopUp:function()
	{
		if(this.connectPanel)
			return this.connectPanel;
		// this.connectPanel = this.findFirst($("body"), "#connectPanel");
		this.connectPanel = this.findFirst($("body"), "#connexionForm");
		return this.connectPanel;		
	},
	
	showConnection:function()
	{
		this.connectFlag = true;
		this.validateFlag = false;
		this.popup = this.getConnectPopUp();
		this.showModal();
		this.addPopup(false, this.submitConnectionClickHandler);
	},
	
	createAccount:function(formUrl)
	{
		this.createAccountFlag = true;
		this.initFormLoad(formUrl);
		this.setupAjax(this.getFormResultHandler, null);
	},
	
	getFormParams:function()
	{
		if(this.form)
			return this.form.serializeArray();
		return null;
	},
	
	submitConnectionClickHandler:function(event)
	{
		event.preventDefault();
		this.setupAjax(this.submitConnexionResultHandler, this.getFormParams());
		return false;
	},
	validateUrl:function(url)
	{
		return url.replace(/\amp;/g, "");
	},
	submitConnexionResultHandler:function(res, arg1)
	{
		res = Number(res);
		if(res == 1)
		{
			$("#compte li.off").removeClass("off");
			$("#compte li.on").toggleClass("off");
			this.findFirst(this.popup, ".emailmdperr").css("display","none");
			this.hidePopup();
			if(this.redirectOnConnected != null)
				window.location.href = this.validateUrl(this.redirectOnConnected);
		}
		else
		{
			this.findFirst(this.popup, ".emailmdperr").css("display","block");
			this.setButtonDisabled(this.submitButton, false);
			this.setButtonDisabled(this.closeButton, false);
		}
	},
	/*
	 * Coords and account
	 */
	/**
	 * 
	 * @param ajaxUrl
	 */
	updateCoords:function(formUrl)
	{
		this.initFormLoad(formUrl);
		this.setupAjax(this.getFormResultHandler, null);
	},
	
	updateConnection:function(formUrl)
	{
		var target = this.getConnectPopUp();
		this.validateFlag = true;
		this.updateAccountFlag = true;
		this.initFormLoad(formUrl);
		this.setupAjax(this.getFormResultHandler, null);		
	},
	
	/*
	 * Address
	 */
	addAddress:function(formUrl)
	{
		this.initFormLoad(formUrl);
		this.setupAjax(this.getFormResultHandler, null);
	},
	/**
	 * @param ajaxUrl String template url
	 * @param target JQuery object
	 */
	updateAddress:function(formUrl, target)
	{
		this.initFormLoad(formUrl);
		this.addressId = this.getAddressId(target);
		this.setupAjax(this.getFormResultHandler, {adresse:this.addressId});
	},
	
	showModal:function()
	{

		this.modal.show();
		
		//$("#modal").css("display", "block");
	},
	
	initFormLoad:function(formUrl)
	{
		this.showModal();
		this.submitButton = null;
		this.closeButton = null;
		this.ajaxUrl = formUrl;
	},
	/**
	 * @param ajaxUrl String template url
	 * @param target JQuery object
	 */
	deleteAddress:function(ajaxUrl, target)
	{
		this.addressId = this.getAddressId(target);
		this.ajaxUrl = ajaxUrl;
		this.setupAjax(this.redirectAfterSubmited, {id:this.addressId, client_id:this.clientId});
	},
	
	getFormResultHandler:function(result)
	{
		this.popup = $(result);
		this.addPopup(true, this.sendForm);
		this.setupValidators();
	},
	
	sendForm:function(event)
	{
		event.preventDefault();
		this.setupAjax(this.redirectAfterSubmited, this.getFormParams());
		return false;
	},
	updateConnectForm:function()
	{
		var login = this.form.find(".emailSrc").val();
		var pwd = this.form.find(".pwdSrc").val();
		var conform = this.findFirst(this.getConnectPopUp(), "form");
		var input = conform.find("input[name='email']");
		input.val(login);
		input = conform.find("input[name='motdepasse']");
		input.val(pwd);
		this.ajaxUrl = conform.attr("action");
		this.setupAjax(this.reconnectSubmitHandler, conform.serializeArray());
	},
	
	reconnectSubmitHandler:function(res)
	{
		res = Number(res);
		if(res == 1)
		{
			window.location.href = this.redirectOnConnected == null ? this.validateUrl(this.redirect):this.validateUrl(this.redirectOnConnected);			
		}
		else
		{
			alert(this.getErrorResultMessage(res));
		}
	},
	
	redirectAfterSubmited:function(res)
	{
		res = Number(res);
		this.setButtonDisabled(this.submitButton, false);
		this.setButtonDisabled(this.closeButton, false);
		if(res == 1)
		{
			this.reconnectSubmitHandler(res);
		}
		else
			alert(this.getErrorResultMessage(res));
	},
	getErrorResultMessage:function(result)
	{
		message = "Erreur:\n";
		switch (result) 
		{
			case 0:
			{
				message += "La requête a échoué.";
				break;
			}
			case 2:
			{
				message += "Accés refusé.";
				break;
			}
			case 3:
			{
				message += "Données de formulaire manquantes.";
				break;
			}
			case 4:
			{
				message += "Cet email est déjà enregistré.";
				break;
			}
			case 5:
			{
				message += "Cet email n'est pas valide.";
				break;
			}
			case 6:
			{
				message += "Mot de passe invalide.";
				break;
			}
			case 7:
			{
				message += "Données de formulaire invalide.";
				break;
			}
			case 8:
			{
				message += "Aucun changement.";
				break;
			}
			case 9:
			{
				message += "Au moins un numéro de téléphone doit être renseigné.";
				break;
			}
			case 10:
			{
				message += "Opération inconnue.";
				break;
			}
			default:
			{
				message = "Erreur inconnue : " + result;
			}
		}
		return message;
	},
	getAddressId:function(target)
	{
		return this.findFirst(target.parent().parent(), "input").val();
	},
	
	findFirst:function(target, id)
	{
		var result = target.find(id);
		if(result === undefined || ! result.length)
			return null;
		return result.first();
	},
	panierAction:function()
	{
		this.showModal();
		this.popup = $("#panierPopup");
		this.addPopup(false, null);
	},
	
	addPopup:function(setupSelects, submitHandler)
	{
		this.popup.addClass("on");
		this.popup.css("max-height", (window.innerHeight -100) + "px");
		this.popup.appendTo(this.modal.jq);
		
		//if(setupSelects === true)
		if(true)
		{
			this.popup.find("select").selectmenu();
			var dd = this.popup.find(".countryDD");
			dd.selectmenu().selectmenu("menuWidget").addClass("overflowDD");
			dd.selectmenu( "option", "position",{my:"left top", at:"left bottom", collision:"flip"});
		}
		if(this.getTagName(this.popup) == "form")
			this.form = this.popup;
		else	
			this.form = this.findFirst(this.popup, "form");
		if(this.form)
		{
			this.ajaxUrl = this.form.attr("action");
			this.submitButton = this.findFirst(this.popup, ".submitBtn");
			if(this.submitButton)
			{	
				this.submitButton.bind(
						"click", 
						$.proxy(submitHandler, this)
				);
			}
			this.closeButton = this.findFirst(this.popup, ".cancelBtn");
			if(this.closeButton)
			{
				this.closeButton.bind(
						"click", 
						$.proxy(this.hidePopup, this));
			}
			
			this.setupTabindexes(this.form);
		}
		try {
			invalidateWindowSize();
		} catch (e) {
			// TODO: handle exception
		}
	},
	getTagName:function(element)
	{
		if(element && element.length)
			return element[0].tagName.toLowerCase();
		return null;
	},
	
	setupTabindexes:function(form)
	{
		var table = form.find("table");
		
		if( ! table.length)
			return;
		table = table.first();
		var indexed = table.find("*[tabindex]");
		
		var fg = table.find(".focusguard1");
		if(fg.length)
			return;

		var inputs = table.find('input,textarea').not('input[type=hidden]');
		var n = $(inputs).length;
		var i;
		var ti;
		var min = Number.MAX_VALUE;
		var max = Number.MIN_VALUE;
		var last;
		var first;
		var target;
		for(i=0; i<n; i++)
		{
			target = $(inputs[i]);
			if(target.attr("tabindex") !== undefined)
			{
				ti = Number(target.attr("tabindex"));
				if(ti < min)
				{
					min = ti;
					first = target;
				}
				if(ti > max)
				{
					max = ti;
					last = target;
				}
			}
		}
		if(indexed.length)
		{
			min = Number.MAX_VALUE;
			max = Number.MIN_VALUE;
			for(i=0; i<indexed.length; i++)
			{
				target = $(indexed[i]);
				ti = Number(target.attr("tabindex"));
				if(ti <= 0)
					continue;
				if(ti < min)
				{
					min = ti;
				}
				if(ti > max)
				{
					max = ti;
				}
			}
			if(min > 0)
				min--;
		}
		
		first.addClass("firstInput");
		last.addClass("lastInput");
		
		fg = $('<div class="focusguard1" tabindex="' + min + '"></div>');
		table.prepend(fg);
		fg.on('focus', function() {
			  $('.lastInput').focus();
			});
		fg = $('<div class="focusguard2" tabindex="' + (max+1) +'"></div>');
		fg.on('focus', function() {
			  $('.firstInput').focus();
			});
		table.append(fg);
		first.focus();
	},
	
	hidePopup:function(event)
	{
		this.popup.removeClass("on");
		this.popup.remove();
		this.modal.hide();
		this.updateAccountFlag = false;
		this.createAccountFlag = null;
		this.initPwd = null;
		this.connectFlag = false;
		this.popup = null;
		this.form = null;
		this.ajaxUrl = null;
		this.submitButton = null;
		this.closeButton = null;
	},
	
	setupAjax:function(resultHandler, data)
	{
		this.setButtonDisabled(this.submitButton, true);
		this.setButtonDisabled(this.closeButton, true);
		
		var params = {
				context:this,
				url:this.ajaxUrl,
				type:"POST",
				data:data
		};
		var request = $.ajax(params);
		request.fail(this.ajaxFaultHandler);							
		request.done(resultHandler);
		return request;
	},
	
	ajaxFaultHandler:function()
	{
		alert("request fault\n" + this.ajaxUrl);
		this.setButtonDisabled(this.closeButton, false);
		if(this.validateFlag)
		{
			this.validateForm();
		}
		else
		{
			this.setButtonDisabled(this.submitButton, false);
		}
		
	},
	
	setButtonDisabled:function(button, disabled)
	{
		if( ! button)
			return;
		if(disabled)
		{
			button.attr("disabled", "disabled");
		}
		else
		{
			button.removeAttr("disabled");
		}
	},
	
	setupValidators:function()
	{
		if(this.createAccountFlag || this.updateAccountFlag)
		{
			this.srcEmail = this.form.find("input.emailSrc").first();
			var message = "les valeurs doivent correspondre";
			$.validator.addMethod(
					"emailValid",
					$.proxy(this.emailValidEqualsSrc, this),
					message);
			
			this.srcPwd = this.form.find("input.pwdSrc").first();
			$.validator.addMethod(
					"pwdValid",
					$.proxy(this.pwdValidEqualsSrc, this),
					message);
		}
		var proxy = $.proxy(this.validateForm, this);
		this.form.validate({
			errorElement: "span",
			errorPlacement: function(error, element) 
			{
				error.prependTo(element.parent());
			},
			success:$.proxy(this.setButtonDisabled, this, this.submitButton, false),
			onkeyup:proxy,
			focusCleanup:proxy,
			onfocusout:proxy,
			onclick:proxy,
			focusInvalid:proxy
		});
	},
	
	validateForm:function()
	{
		this.setButtonDisabled(this.submitButton, ! this.form.valid());		
	},
	

	emailValidEqualsSrc:function(value, element)
	{
		var src = this.srcEmail.val();
		return src == value;
	},
	
	pwdValidEqualsSrc:function(value, element)
	{
		if(this.updateAccountFlag)
		{
			if(this.initPwd == null)
			{
				this.initPwd = this.srcPwd.val();
			}
		}
		var src = this.srcPwd.val();
		if(src == this.initPwd && value == "")
			return true;
		return src == value;
	},
	
};
