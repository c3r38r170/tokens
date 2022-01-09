'use strict';

// Definiciones
let formCrearSubmit=gEt('form-crear-submit');

// Event Listeners
SqS('[name="form-crear-nick"]').onchange
	= SqS('[name="form-crear-nick"]').onkeyup
	=	function(){
		let invalido=usuarios.includes(this.value);
		formCrearSubmit.disabled=invalido;
		formCrearSubmit.title=invalido?'El nombre de usuario ya existe.':'';
	};

	// onsubmit
	
	gEt('form-crear').onsubmit=function () {
		let fieldset=this.firstElementChild;
		fieldset.disabled=true;
		let name=this['form-crear-name'].value.trim()
			,nick=this['form-crear-nick'].value.trim();
		sendJSON('/api/user/create/',{
			name,
			nick
			,pass:this['form-crear-pass'].value.trim()
		})
			.then(res=>res.text())
			.then(res=>{
				if(+res){
					addElement(SqS('[name="form-enviar-usuario"]'),['OPTION',{innerText:name,value:+res}]);
					usuarios.push(nick);
					toast.success(`Se ha generado exitosamente el usuario para ${name}.`);
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