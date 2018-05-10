function notif(text) {
	var popup = document.createElement("div");
	popup.innerHTML = text;
	body.appendChild(popup);
}

function post_form(url, datas) {
	var xhr = new XMLHttpRequest();
	xhr.open('POST', url);
	xhr.addEventListener('load', function() {
		notif("OK : " + xhr.response);
	});

	var form = new FormData();
	for (i in datas) {
		form.append(i, datas[i]);
	}
	xhr.send(form);
}

form_log.addEventListener('submit', function() {
	post_form("/log", {"mail": form_log.mail.value,
						"pass": form_log.pass.value});
});

form_register.addEventListener('submit', function() {
	post_form("/register", {"username": form_register.username.value,
						"mail": form_register.mail.value,
						"pass": form_register.pass.value});
});
