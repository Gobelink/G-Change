
function checkData(form, entity){
	var numberOfEntities = form.to.value - form.from.value;
	var stringOfNumberOfEntities = numberOfEntities.toString();
	return confirm("Vous êtes sur le point de mettre à jour " + stringOfNumberOfEntities + " " + entity + "(s)");
}