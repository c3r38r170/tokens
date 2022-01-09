'use strict';

// Funciones
function setTokens(newValue){
	tokens=newValue;
	gEt('tokens').innerText = newValue;
	gEt('form-enviar-cantidad').max=newValue;
}

// Definiciones
let formEnviarSubmit=gEt('form-enviar-submit');
let formContraseñaSubmit=gEt('form-contraseña-submit');

// Event Listeners

gEt('form-enviar-usuario').onchange =function () {
	let invalido=!+this.value;
	formEnviarSubmit.disabled=invalido;
	formEnviarSubmit.title=invalido?'Debe elegir un usuario para enviarle tokens.':'';
};

SqS('[name="form-contraseña-new-2"]').onchange
= SqS('[name="form-contraseña-new-2"]').onkeyup
=	function(){
	let invalido=this.value!=SqS('[name="form-contraseña-new-1"]').value;
	formContraseñaSubmit.disabled=invalido;
	formContraseñaSubmit.title=invalido?'La contraseña nueva y la confirmación no coinciden.':'';
};

gEt('salir').onclick=()=>{
	let fieldsets=SqS('body > fieldset',{n:ALL});
	for(let fieldset of fieldsets)
		fieldset.disabled=true;
	let restoreFieldsets=message=>{
		for(let fieldset of fieldsets)
			fieldset.disabled=false;
		toast.error(message);
	}

	sendJSON('/api/user/logout/')
		.then(res=>res.text())
		.then(txt=>{
			if(+txt){
				W.location='/';
			}else{
				restoreFieldsets(`Ha ocurrido un error inesperado${txt.length<100?`: ${res}`:'.'} Vuelva a intentarlo más tarde.`);
			}
		})
		.catch(err=>restoreFieldsets(`Ha ocurrido un error inesperado${err.length<100?`: ${res}`:'.'} Vuelva a intentarlo más tarde.`));
}

	// onsubmit

	gEt('form-enviar').onsubmit=function () {
		let fieldset=this.firstElementChild;
		fieldset.disabled=true;
		let cantidad=+this['form-enviar-cantidad'].value;
		sendJSON('/api/tokens/give/',{
			cantidad,
			usuario:+this['form-enviar-usuario'].value
		})
			.then(res=>res.text())
			.then(res=>{
				if(+res){
					setTokens(tokens-cantidad);
					toast.success(`Se han enviado ${cantidad} tokens a ${this['form-enviar-usuario'].selectedOptions[0].innerText}.`);
				}else{
					console.log(res);
					toast.error(`Ha ocurrido un error inesperado${res.length<100?`: ${res}`:'.'} Vuelva a intentarlo más tarde.`);
				}
			})
			.catch(err=>{
				console.error(err);
			})
			.finally(()=>{
				fieldset.disabled=false;
			});

		return false;
	};

	gEt('form-contraseña').onsubmit=function () {
		let fieldset=this.firstElementChild;
		fieldset.disabled=true;

		sendJSON('/api/user/change-password/',{
			old:this['form-contraseña-old'].value.trim()
			,new:this['form-contraseña-new-1'].value.trim()
		})
			.then(res=>res.text())
			.then(res=>{
				if(+res){
					toast.success(`Se ha cambiado la contraseña. ¡No la olvide!`);
				}else{
					console.log(res);
					toast.error(`Ha ocurrido un error inesperado${res.length<100?`: ${res}`:'.'} Vuelva a intentarlo más tarde.`);
				}
			})
			.catch(err=>{
				console.error(err);
			})
			.finally(()=>{
				fieldset.disabled=false;
			});

		return false;
	};