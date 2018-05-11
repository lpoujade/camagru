function notif(text) {
	var popup = document.createElement("div");
	popup.innerHTML = text;
	header.appendChild(popup);
}

function post_form(url, datas, callback) {
	var xhr = new XMLHttpRequest();
	xhr.open('POST', url);
	xhr.addEventListener('progress', function(e) {
		progress.max = e.total;
		progress.value = e.loaded;
	});
	xhr.addEventListener('load', function() {
		callback(JSON.parse(xhr.response));
	});
	var form = new FormData();
	for (i in datas) {
		form.append(i, datas[i]);
	}
	console.log('post form to ' + url);
	xhr.send(form);
}

form_log.addEventListener('submit', function() {
	post_form("/log", {"mail": form_log.mail.value,
						"pass": form_log.pass.value}, window.handler['account']);
});

form_register.addEventListener('submit', function() {
	post_form("/register", {"username": form_register.username.value,
						"mail": form_register.mail.value,
						"pass": form_register.pass.value}, window.handler['account']);
});
