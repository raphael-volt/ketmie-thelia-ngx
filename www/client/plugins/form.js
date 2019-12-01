
function dpxSubmit(event, inputId, value, formId) {
    event.stopImmediatePropagation()
    event.preventDefault()
	var e = document.getElementById(inputId);
	if(!e)
		return
    e.setAttribute('value', value)
    console.log('set value=' + value + ' to ' + inputId)
    if (!formId)
        formId = '.submit()'
	console.log(formId + ' to ' + inputId)            
    //document.getElementById(formId).submit()
    return false
}