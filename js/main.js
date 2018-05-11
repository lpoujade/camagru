function api_get(url, callback) {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', url);
	xhr.addEventListener('load', function() {
	console.log('received from ' + url);
		if (callback)
			callback(JSON.parse(xhr.response));
	});
	console.log('api_get ' + url);
	xhr.send();
}

function show_elem(elem) {
	/* hide other sections */
	var sections = document.getElementsByTagName('section');
	var i = 0;
	while (i < sections.length) {
		sections[i].style.display = "none";
		i++;
	}
	var e = document.getElementById(elem);
	if (e)
		e.style.display = "";
}

connected = false;
handler = {'gallery': function() {
	show_elem('s_gallery');
	if (s_gallery.childElementCount > 1)
		return ;
	api_get("/gallery", function(response) {
		for (i in response) {
			var ac_div = document.getElementById('d_img_0');
			var div = ac_div.cloneNode(true);
			div.style.display = "";
			div.id = 'd_img_' + response[i].id;
			div.getElementsByTagName('span')[0].innerHTML = response[i].image;
			s_gallery.appendChild(div);
		}
	});
},
	'create': function() {
		show_elem('s_create');
		if (!connected)
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
						api_get('/creation/delete/' + item_id, window.handler['gallery/mines']);
					});
					d_userimg.appendChild(div);
				}
			});
		}
	},
	'logout': function() {
		api_get('/flush_session', window.handler['account']);
	},
	'account': function() {
		show_elem('s_account');
		api_get('/log/infos', function(response) {
			console.log(response);
			if (response.status == 1) {
				d_account.style.display = "";
				d_logform.style.display = "none";
				inp_mail.value = response.mail;
				inp_username.value = response.user;
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

function get_page_content() {
	var name = this.href.split('#')[1];
	handler[name]();
}

var menu_links = [a_gallery, a_create, a_account, a_logout];
for(i in menu_links)
	menu_links[i].addEventListener('click', get_page_content);
