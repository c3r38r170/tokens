'use strict';

// UI
gEt('tokens-circulando').innerText =tokensCirculating;

// Event Listeners

	// onsubmit
	
	gEt('form-generar').onsubmit=function () {
		let fieldset=this.firstElementChild;
		fieldset.disabled=true;
		let cantidad=+this['form-generar-cantidad'].value;
		sendJSON('/api/tokens/generate/',{
			cantidad
		})
			.then(res=>res.text())
			.then(res=>{
				if(+res){
					setTokens(tokens+cantidad);
					gEt('tokens-circulando').innerText=tokensCirculating+=cantidad;
					toast.success(`Se han generado ${cantidad} tokens y se han añadido a su cuenta.`);
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