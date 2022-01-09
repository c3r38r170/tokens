'use strict';

let baseURL="https://unpkg.com/egalink-toasty.js@1.5.5//dist/sounds/"
var toast = new Toasty({enableSounds:true,sounds: {
	info: baseURL+"info/1.mp3",
	success: baseURL+"success/1.mp3",
	warning: baseURL+"warning/1.mp3",
	error: baseURL+"error/1.mp3",
}});

/* const toasts={
	fail:mensaje=>Toastify({
		text: mensaje,
		duration: 6000,
		gravity: "top", // `top` or `bottom`
		position: "left", // `left`, `center` or `right`
		stopOnFocus: true, // Prevents dismissing of toast on hover
		style: {
			background: 'linear-gradient(to right, #ff5f6d, #ffc371)',
		}
	}).showToast()
	,success:mensaje=>Toastify({
		text: mensaje,
		duration: 4000,
		gravity: "top", // `top` or `bottom`
		position: "left", // `left`, `center` or `right`
		stopOnFocus: true, // Prevents dismissing of toast on hover
		style: {
			background: "linear-gradient(to right, #00b09b, #96c93d)",
		}
	}).showToast()
}
// Replace tast.error with toasts.fail and toast.success with toasts.success.
*/