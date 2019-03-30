const filtrar_tabla = (filtro, columna, tabla) => {
	let rex = new RegExp(filtro.val(), 'i');
    tabla.addClass('d-none').removeClass('d-show');

    tabla.filter(function(i, v) {
        var $t = $(this).children(":eq(" + columna + ")");
        return rex.test($t.text());
    }).removeClass('d-none').addClass('d-show');
};

const ordenar_fila = (elemento, tipo = 'texto') => {
	let tipo_comparador = comparer($(elemento).index(), tipo);
	let table = $(elemento).parents('table').eq(0);
	let rows = table.find('tr:gt(0)').toArray().sort(tipo_comparador)
	elemento.asc = !elemento.asc
	if (!elemento.asc){rows = rows.reverse()}
	for (var i = 0; i < rows.length; i++){table.append(rows[i])}
}

const comparer = (index, tipo) => {
    return function(a, b) {
        var valA = get_cell_value(a, index), valB = get_cell_value(b, index);

        if(tipo == 'moneda'){
			valA = valA.substring(0, valA.indexOf(" "));
			valB = valB.substring(0, valB.indexOf(" "));
		}

        if(tipo == 'numero'){
			
		}

		if(tipo == 'fecha'){
			valA = (!isNaN(convert_to_time(valA))) ? convert_to_time(valA) : -999999999999;
			valB = (!isNaN(convert_to_time(valB))) ? convert_to_time(valB) : -999999999999;
		}

		return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB)
    }
}
const convert_to_time = (myDate) => {
	let date_split=myDate.split(" ");
	let date=date_split[0].split("/");
	let newDate=date[2]+"/"+date[1]+"/"+date[0]+" "+date_split[1];
	return new Date(newDate).getTime();
}

const get_cell_value = (row, index) => { return $(row).children('td').eq(index).text() }


export { filtrar_tabla, ordenar_fila };