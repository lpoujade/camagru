
prev_ul = null;

function comment_show(e) {
	if (prev_ul)
		prev_ul.style.display = "none";
	e.nextElementSibling.style.display = "";
	ul = e;
	prev_ul = e.nextElementSibling;

	api_get('/comment/3', function(response) {
		for (i in response) {
			var nli = document.createElement('li');
			nli.innerHTML = response[i];
			ul.appendChild(nli);
		}
	});

}

function comment_it(e) {
	post_form('/comment',
		{
			'creation_id': 1,
			'text': 'blabla'
		});

}
