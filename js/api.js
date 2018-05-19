function api_get(url, callback) {
	var xhr = new XMLHttpRequest();
	xhr.open('GET', url);
	xhr.setRequestHeader('Accept', 'application/json');
	xhr.addEventListener('load', function() {
		if (callback != null)
			callback(JSON.parse(xhr.response));
	});
	xhr.send();
}

function notif(resp) {
	var popup = document.getElementById("d_notif");
	popup.style.display = "";
	if (resp.status === true) {
		popup.style.background = "green";
		popup.firstElementChild.innerHTML = 'Success ' + (resp.reason ?resp.reason: '!');
	}
	else {
		popup.firstElementChild.innerHTML = 'Error: ' + (resp.reason ? resp.reason : 'unknow');
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
	xhr.send(form);
	changes = true;
}
