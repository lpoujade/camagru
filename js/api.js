function api_get(url, callback) {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', url);
	xhr.addEventListener('load', function() {
		if (callback != null && xhr.response)
			callback(JSON.parse(xhr.response));
	});
	xhr.send();
}
