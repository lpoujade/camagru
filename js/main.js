function show_elem(elem) {
	/* hide other sections */
	if (cam.srcObject)
		stop_webcam();
	var e = document.getElementById(elem);
	if (e.style.display != "none")
		return true;
	var sections = document.getElementsByTagName('section');
	var i = 0;
	while (i < sections.length) {
		sections[i].style.display = "none";
		i++;
	}
	if (e)
		e.style.display = "";
	return false;
}

handler = {
	'gallery': function() {
		show_elem('s_gallery');
		if (!changes && d_gallery.childElementCount >= 2)
			return ;
		api_get("/gallery", gallery_addimgs);
	},
	'create': function() {
		if (connected === false)
			handler['account']();
		else {
			show_elem('s_create');
			start_cam();
			if (d_userimg.childElementCount >= 3)
				return ;
			api_get('/gallery/mines', function(response) {
				for (i in response) {
					var ac_div = document.getElementById('d_userimg_0');
					var div = ac_div.cloneNode(true);
					div.style.display = "";
					div.id = 'd_userimg_' + response[i].id;
					div.getElementsByTagName('img')[0].id += response[i].id;
					div.getElementsByTagName('img')[0].src = "/datas/"+response[i].id+".png";
					div.getElementsByClassName('btn')[0].addEventListener('click', function() {
						var elem = this.parentNode.parentNode;
						api_get('/creation/delete/' + elem.id.split('_').pop());
						var elem_gallery = document.getElementById('d_img_' + elem.id.split('_').pop());
						if (elem_gallery)
							elem_gallery.remove()
						elem.remove();
					});
					d_userimg.appendChild(div);
				}
			});
		}
	},
	'logout': function() {
		handler['gallery']();
		api_get('/flush_session', null);
		connected = false;
	},
	'account': function() {
		show_elem('s_account');
		api_get('/log/infos', function(response) {
			if (response.status == true) {
				d_account.style.display = "";
				d_logform.style.display = "none";
				mod_mail.value = response.mail;
				mod_username.value = response.user;
				span_username.innerHTML = response.user + " " + response.mail;
				username = response.user;
				if (response.notif_mail == 1 && notif_mail.value != 1) {
					lever.click();
				}
				connected = true;
			} else {
				d_account.style.display = "none";
				d_logform.style.display = "";
				connected = false;
			}
		});

	}
};


function check_url() {
	var url = window.location.href.split('/').pop().split('#');
	last_url_elem = null;
	if (url)
		last_url_elem = url.pop().replace('?', '');
	if (last_url_elem && last_url_elem.length > 1 && handler[last_url_elem])
		handler[last_url_elem]();
	else
		handler['gallery']();
}

connected = false;
changes = false;
username = 'unknown';

api_get('/log/infos', function(response) {
	if (response.status == 1) {
		connected = true;
		username = response.user;
	}
	else
		connected = false;
	check_url();
});

var menu_links = [a_gallery, a_create, a_account, a_logout];
for(i in menu_links)
	menu_links[i].addEventListener('click', function() {
		var name = this.id.replace("a_", "");
		handler[name]();
	});

lever.addEventListener('click', function() {
	notif_mail.value = (notif_mail.value == 1 ? 0 : 1);
});

var prop_filters = document.getElementsByClassName('masks');
for (i=0; i < prop_filters.length; i++) {
	prop_filters[i].addEventListener('click', function() {
		calc.push(this.cloneNode());
		preview.appendChild(calc[calc.length - 1]);
		calc[calc.length - 1].style.position = "absolute";
		calc[calc.length - 1].style.cursor = "move";
		calc[calc.length - 1].className = "";
		dragElement(calc[calc.length - 1]);
		btn_capture.removeAttribute("disabled");
	});
}
