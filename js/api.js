function api_get(url, callback) {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', url);
	xhr.addEventListener('load', function() {
	console.log('received from ' + url);
		if (callback != null && xhr.response)
			callback(JSON.parse(xhr.response));
	});
	console.log('api_get ' + url);
	xhr.send();
}
