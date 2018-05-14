function api_get(url, callback) {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', url);
	xhr.addEventListener('load', function() {
		if (callback != null && xhr.response)
			callback(JSON.parse(xhr.response));
	});
	xhr.send();
}

function show_elem(elem) {
	/* hide other sections */
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
		show_elem('s_create');
		if (connected === false)
			handler['account']();
		else {
			if (d_userimg.childElementCount >= 3)
				return ;
			api_get('/gallery/mines', function(response) {
				for (i in response) {
					var ac_div = document.getElementById('d_userimg_0');
					var div = ac_div.cloneNode(true);
					div.style.display = "";
					div.id = 'd_userimg_' + response[i].id;
					item_id = response[i].id;
					div.getElementsByTagName('span')[0].innerHTML = response[i].image;
					div.getElementsByClassName('btn')[0].addEventListener('click', function() {
						var elem = this.parentNode.parentNode.parentNode;
						api_get('/creation/delete/' + elem.id.split('_').pop());
						var elem_gallery = document.getElementById('d_img_' + elem.id.split('_').pop());
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
			if (response.status == 1) {
				d_account.style.display = "";
				d_logform.style.display = "none";
				mod_mail.value = response.mail;
				mod_username.value = response.user;
				span_username.innerHTML = response.user + " " + response.mail;
				connected = true;
			} else {
				d_account.style.display = "none";
				d_logform.style.display = "";
				connected = false;
			}
		});

	}
};

var menu_links = [a_gallery, a_create, a_account, a_logout];
for(i in menu_links)
	menu_links[i].addEventListener('click', function() {
		var name = this.href.split('#')[1];
		handler[name]();
	});

changes = false;
api_get('/log', function(response) {
	if (response.status == 1)
		connected = true;
	else
		connected = false;
});

