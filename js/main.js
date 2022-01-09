'use strict';

gEt('ingreso').onsubmit=function(){
	let fieldset=this.firstElementChild;
	fieldset.disabled=true;
	let restoreFieldset=message=>{
		fieldset.disabled=false;
		toast.error(message);
	}

	sendJSON('/api/user/login/',{nick:this.nick.value.trim(),password:this.password.value.trim()})
		.then(res=>{
			if (res.ok) {
				W.location='/panel';
			}else res.text().then(restoreFieldset);
		})
		.catch(()=>restoreFieldset("Ha ocurrido un error inesperado. Vuelva a intentarlo más tarde."));

	return false;
}