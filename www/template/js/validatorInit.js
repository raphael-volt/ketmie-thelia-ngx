$.validator.addMethod(
		"cRequired", 
		$.validator.methods.required,
		"champs requis");

$.validator.addMethod(
		"cMinlength", 
		$.validator.methods.minlength,
		$.validator.format("{0} caractères minimum"));

$.validator.addMethod(
		"cDigits", 
		$.validator.methods.digits,
		"uniquement des nombres");

$.validator.addMethod(
		"cMail", 
		function(value, element) 
		{
		   return /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value);     
		},
		"email invalide");

$.validator.addClassRules("email", 
{
	cRequired: true,
	cMail: true
});	

$.validator.addClassRules("pwd", 
{
	cRequired: true,
	cMinlength:6
});

$.validator.addClassRules("pwdUp", 
{
	cRequired: false,
	cMinlength:6
});

$.validator.addClassRules("required", 
{
	cRequired:true
});

$.validator.addClassRules("len3", 
{
	cMinlength:3
});

$.validator.addClassRules("numField", 
{
	cDigits:true
});