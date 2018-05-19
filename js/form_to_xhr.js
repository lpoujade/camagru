form_log.addEventListener('submit', function() {
	post_form("/log", {"mail_log": form_log.mail_log.value,
						"pass_log": form_log.pass_log.value}, window.handler['account']);
});

form_register.addEventListener('submit', function() {
	post_form("/register", {"username": form_register.username.value,
						"mail": form_register.mail.value,
						"pass": form_register.pass.value}, window.handler['account']);
});

form_moduser.addEventListener('submit', function() {
	post_form('/mod', {
		'username': mod_username.value,
		'mail': mod_mail.value,
		'pass': mod_pass.value,
		'notif_mail': notif_mail.value,
		'newpass': mod_pass_new.value}, function() {
	});
});

form_forgot.addEventListener('submit', function() {
	post_form('/forgot', {'mail_forgot': mail_forgot.value});
});
