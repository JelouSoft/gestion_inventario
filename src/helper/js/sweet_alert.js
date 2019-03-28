const error_sweet_alert = (text = "") => Swal.fire("¡Encontramos un detalle!", text, "warning");
const success_sweet_alert = (text = "") => Swal.fire("¡Enhorabuena!", text, "success");
const confirm_sweet_alert = (title, text, callback) => {
	Swal.fire({
	  title,
	  html: text,
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Si, deseo continuar',
	  cancelButtonText: 'Dejame pensarlo'
	}).then((result) => { if (result.value) { callback(); } });
}
export {
	error_sweet_alert, success_sweet_alert, confirm_sweet_alert
}