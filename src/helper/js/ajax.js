const ajax_call = (url, data, type, callback) => {
	$.ajax({
		url: `../../controller/${url}.php`,
		data,
		type,
		dataType:'json',
		success: callback,
		error: (xhr, desc, err) => console.log("Details: " + desc + "\nError: " + err)
	});
}

export { ajax_call }