function notif(resp) {
	var popup = document.getElementById("d_notif");
	popup.style.display = "";
	if (resp.status === true) {
		popup.style.background = "green";
		popup.firstElementChild.innerHTML = 'Success ' + (resp.reason ?resp.reason: '!');
	}
	else {
		popup.firstElementChild.innerHTML = 'Error: ' + resp.reason;
		popup.style.background = "red";
	}
	popup.style.opacity = 100;
	setTimeout(function() {
		popup.style.opacity = 0;
	}, 2000);
	setTimeout(function() {
		popup.style.display = "none";
	}, 3000);
}

function post_form(url, datas, callback) {
	var xhr = new XMLHttpRequest();
	xhr.open('POST', url);
	/*
	xhr.onprogress = function(e) {
		var percent = (e.loaded/e.total) * 100;
		d_progress.style.width = percent + "%";
		console.log(percent + "%");
		//progress.value = e.loaded;
	};
	*/
	xhr.addEventListener('load', function() {
		if (callback)
			callback(JSON.parse(xhr.response));
		notif(JSON.parse(xhr.response));
	});
	var form = new FormData();
	for (i in datas) {
		form.append(i, datas[i]);
	}
	console.log('post form to ' + url);
	xhr.send(form);
	changes = true;
}

form_log.addEventListener('submit', function() {
	post_form("/log", {"mail_log": form_log.mail_log.value,
						"pass_log": form_log.pass_log.value}, window.handler['account']);
});

form_register.addEventListener('submit', function() {
	post_form("/register", {"username": form_register.username.value,
						"mail": form_register.mail.value,
						"pass": form_register.pass.value}, window.handler['account']);
});

//btn_moduser.addEventListener('click', function() {
form_moduser.addEventListener('submit', function() {
	post_form('/mod', {
		'username': mod_username.value,
		'mail': mod_mail.value,
		'pass': mod_pass.value,
		'notif_mail': notif_mail.value,
		'newpass': mod_pass_new.value}, function() {
		console.log('posted');
	});
});

form_forgot.addEventListener('submit', function() {
	post_form('/forgot', {'mail_forgot': mail_forgot.value});
});
