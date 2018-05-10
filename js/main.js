function api_get(url, callback) {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', url);
	xhr.addEventListener('load', function() {
		callback(xhr.response);
	});
	xhr.send();
}

var handler = {'gallery': function() {
	console.log('in gallery');
	api_get("/gallery", function(response) {
		var d = JSON.parse(response);
		for (i in d) {
			console.log(d[i]);
			var div = document.createElement('div');
			div.innerHTML = d[i].name;
			s_gallery.appendChild(div);
		}
	});
},
	'create': function() {
		console.log('in create');
	},
	'account': function() {
		console.log('in account');
		var co = api_get('/log', function(response) {
			if (response == "ok") {
				d_account.style.display = "";
				d_logform.style.display = "none";
			} else {
				d_account.style.display = "none";
				d_logform.style.display = "";
			}
		});

	}
};

function get_page_content() {
	/* hide other sections */
	var sections = document.getElementsByTagName('section');
	var i = 0;
	while (i < sections.length) {
		sections[i].style.display = "none";
		i++;
	}
	var name = this.href.split('#')[1];
	var e = document.getElementById('s_' + name);
	e.style.display = "";
	handler[name]();
}

var menu_links = [a_gallery, a_create, a_account];
for(i in menu_links)
	menu_links[i].addEventListener('click', get_page_content);
