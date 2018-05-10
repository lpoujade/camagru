function notif(text) {
	var popup = document.createElement("div");
	popup.innerHTML = text;
	body.appendChild(popup);
}


btn_log.addEventListener('click', function() {
	var xhr = new XMLHttpRequest();
	xhr.open('POST', "/log");
	xhr.addEventListener('load', function() {
		notif("OK : " + xhr.response);
	});

	var form = new FormData();
	form.append('mail', form_log.mail.value);
	form.append('pass', form_log.pass.value);
	console.log(form_log.pass.value);
	console.log(form_log.mail.value);
	xhr.send(form);
});
