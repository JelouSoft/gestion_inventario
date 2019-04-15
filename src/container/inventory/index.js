import { filtrar_tabla, ordenar_fila } from '../../helper/js/_general.js';
import { ajax_call } from '../../helper/js/ajax.js';
import { 
	success_sweet_alert, 
	error_sweet_alert, 
	confirm_sweet_alert 
} from '../../helper/js/sweet_alert.js';

$("#sign_in").click(function(){
	const password = $("#password").val();
	ajax_call("sign_in", { password }, "POST", ({isOk, message}) => {
		if(!isOk){
			error_sweet_alert(message);
			return;
		}
		success_sweet_alert(message);
		$(".btn").prop('disabled', false);
		$('#sign_in_modal').modal('hide');
		return;
	});
});

$("#sign_in_modal").modal('show');
$("#menu .btn").prop('disabled', true);

$("table.table thead").on('click','th.ordenar_por_texto',function(){
	ordenar_fila(this,'texto');
});

$("#busqueda").keyup(function(){
	filtrar_tabla($(this), [0, 3], $("#main tbody tr"));
});

$('#open_get_inventory_control_modal').click(function(){
	ajax_call("get_inventory_control", { }, "GET", (data) => {
		$('.general_inventory_control').removeClass('d-none');
		$("table#inventory_control tbody").empty();
		$('#get_inventory_control_modal').modal('show');
		if(data.length === 0){
			$("table#inventory_control tbody").append('<tr>' +
				'<td colspan="4" class="text-center">' +
					'No se encontraron registros en base de datos' +
				'</td>' +
			'</tr>');
			return;
		}
		for(let v of data) {
			const new_stock = (parseInt(v.action) === 0) 
				? parseFloat(v.current_stock) - parseFloat(v.quantity)
				: parseFloat(v.current_stock) + parseFloat(v.quantity);

			$("table#inventory_control tbody").append('<tr>' + 
				'<td>' + v.product_name + '</td>' +
				'<td class="text-center">' + code_to_symbol(parseInt(v.action)) + v.quantity + '</td>' +
				'<td class="text-center">' + v.current_stock + ' >>> ' + new_stock  + '</td>' +
				'<td>' + v.annotation + '</td>' +
				'<td>' + v.created_at + '</td>' +
			'</tr>');
		}	
	});
});

$('#open_product_modal').click(function(){
	clear_product_form();
	$('#product_modal').modal('show');
});

$('#create_inventory_control').click(function(){
	
	const product_id = parseInt($('#create_inventory_control').data('id'));

	const quantity = parseFloat($('#quantity').val());
	const annotation = $('#annotation').val();
	const action = $('#action').val();

	if(isNaN(quantity) || quantity <= 0){
		error_sweet_alert("Proporciona la cantidad a inventariar.");
		return;
	}

	if(annotation === ""){
		error_sweet_alert("Proporciona una anotación.");
		return;
	}

	const url = "create_inventory_control";
	const type = "POST";
	const data = { product_id, quantity, annotation, action };

	ajax_call(url, data, type, ({isOk, message}) => {
		if(!isOk){
			error_sweet_alert(message);
			return;
		}
		success_sweet_alert(message);
		clear_inventory_control_form();
		get_all_products();
		$('#inventory_control_modal').modal('hide');
		return;
	});


});

$('#create_product').click(function(){

	const product_id = parseInt($('#create_product').data('id'));

	const name = $('#name').val();
	const unit = $('#unit').val();
	const classification = $('#classification').val();
	const location = $('#location').val();
	const stock = parseFloat($('#stock').val());
	const feature = $('#feature').val();

	if(name === ""){
		error_sweet_alert("Proporciona el nombre del producto.");
		return;
	}

	if(unit === ""){
		error_sweet_alert("Proporciona el nombre de la unidad.");
		return;
	}

	if(isNaN(stock) || stock < 0){
		error_sweet_alert("Proporciona las unidades en existencia.");
		return;
	}
	
	const url = isNaN(product_id) ? "create_product" : "update_product";
	const type = "POST";
	const data = { product_id, name, unit, classification, location, stock, feature };

	ajax_call(url, data, type, ({isOk, message}) => {
		if(!isOk){
			error_sweet_alert(message);
			return;
		}
		success_sweet_alert(message);
		clear_product_form();
		get_all_products();
		$('#product_modal').modal('hide');
		return;
	});

});

$("table.table tbody").on('click', '.delete_product', function() {
	const product_id = $(this).data('id');
	const title = "¡Necesitamos tu confirmación!";
	const text = "Se eliminará el producto y no podrás recuperarlo.<br/>¿Deseas proceder?";
	confirm_sweet_alert(title, text, () => {
		ajax_call("delete_product", { product_id }, "POST", ({ isOk, message }) => {
			if(!isOk){ error_sweet_alert(message); return; }
			success_sweet_alert(message);
			get_all_products();
		});
	});
});

$("table.table tbody").on('click', '.get_inventory_control', function() {
	const product_id = $(this).data('id');
	ajax_call("get_inventory_control_by_product_id", { product_id }, "GET", (data) => {
		$('.general_inventory_control').addClass('d-none');
		$("table#inventory_control tbody").empty();
		$("#get_inventory_control_modal").modal('show');
		if(data.length === 0){
			$("table#inventory_control tbody").append('<tr>' +
				'<td colspan="4" class="text-center">' +
					'No se encontraron registros en base de datos' +
				'</td>' +
			'</tr>');
			return;
		}
		for(let v of data) {
			const new_stock = (parseInt(v.action) === 0) 
				? parseFloat(v.current_stock) - parseFloat(v.quantity)
				: parseFloat(v.current_stock) + parseFloat(v.quantity);

			$("table#inventory_control tbody").append('<tr>' + 
				'<td class="text-center">' + code_to_symbol(parseInt(v.action)) + v.quantity + '</td>' +
				'<td class="text-center">' + v.current_stock + ' >>> ' + new_stock  + '</td>' +
				'<td>' + v.annotation + '</td>' +
				'<td>' + v.created_at + '</td>' +
			'</tr>');
		}	
	});
});

$("table.table tbody").on('click', '.edit_stock', function() {
	const product_id = $(this).data('id');
	clear_inventory_control_form();
	$('#create_inventory_control').data('id', product_id);
	$('#inventory_control_modal').modal('show');
});

$("table.table tbody").on('click', '.edit_product', function() {
	const product_id = $(this).data('id');
	ajax_call("get_product_by_id", { product_id }, "GET", (data) => {
		if(data.length === 0){ return; }
		$('#name').val(data.name);
		$('#unit').val(data.unit);
		$('#classification option[value="' + data.classification + '"]').prop("selected", true);
		$('#location').val(data.location);
		$('#stock').val(data.stock);
		$('#feature').val(data.feature);
		$('#create_product').data('id', data.product_id);
		$('#create_product').html('Actualizar');
		$('.stock').hide();
		$('#product_modal').modal('show');
	});
});

const get_all_products = () => {
	ajax_call("get_all_products", {}, "GET", (data) => {
		$("table.table tbody").empty();
		for(let v of data) {
			$("table.table tbody").append('<tr>' + 
				'<td>' + v.name + '</td>' +
				'<td>' + convert_classification_to_text(v.classification) + '</td>' +
				'<td>' + v.location + '</td>' +
				'<td>' + v.feature + '</td>' +
				'<td>' + v.unit + '</td>' +
				'<td class="text-center">' + parseFloat(v.stock).toFixed(2) + '</td>' +
				'<td>' + 
					'<div class="width-10 float-right">' +
		      	'<button data-id="' + v.product_id + '" class="edit_stock btn btn-sm btn-warning text-white mr-1" data-toggle="tooltip" data-placement="bottom" title="Editar Stock" disabled><i class="fas fa-cash-register"></i></button>' +
		      	'<button data-id="' + v.product_id + '" class="get_inventory_control btn btn-sm btn-info mr-1" data-toggle="tooltip" data-placement="bottom" title="Movimientos de Inventario" disabled><i class="fas fa-chart-line"></i></button>' +
		      	'<button data-id="' + v.product_id + '" class="edit_product btn btn-sm btn-primary mr-1" data-toggle="tooltip" data-placement="bottom" title="Editar Producto" disabled><i class="fas fa-edit"></i></button>' +
		      	'<button data-id="' + v.product_id + '" class="delete_product btn btn-sm btn-danger" data-toggle="tooltip" data-placement="bottom" title="Eliminar Producto" disabled><i class="fas fa-trash-alt"></i></button>' +
		      '</div>' +
				'</td>' +
			'</tr>');
		}	
	});
}

const clear_product_form = () => {
	$('#name').val('');
	$('#unit').val('Pieza');
	$('#stock').val('');
	$('#location').val('');
	$('#feature').val('');
	$('.stock').show();
	$('#create_product').data('id', 'undefined');
	$('#create_product').html('Guardar');
}

const clear_inventory_control_form = () => {
	$('#quantity').val('');
	$('#annotation').val('');
	$('#create_inventory_control').data('id', 'undefined');
}

const code_to_symbol = (code) => {
	const symbols = { 0: "-", 1: "+" };
	return symbols[code];
}

const convert_classification_to_text = key => {
	const classifications = [
		"Indefinido",
		"Herramientas",
		"Tuberías y Conexiones",
		"Válvulas",
		"Equipo de Seguridad",
		"Equipo de Limpieza",
		"Equipo de Laboratorio",
		"Motores y bombas",
		"Almacenamiento",
		"Misceláneo"
	];
	return classifications[key];
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

get_all_products();