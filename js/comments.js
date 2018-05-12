
prev_dul = null;

function comment_show(e) {
	var id = e.parentNode.parentNode.parentNode.id.split('_').pop();
	if (prev_dul)
		prev_dul.style.display = "none";
	e.nextElementSibling.style.display = "";
	prev_dul = e.nextElementSibling;

	ul = prev_dul.getElementsByClassName('ul_comment')[0];
	if (ul.childElementCount >= 2)
		return ;
	api_get('/comments/'+id, function(r) {
		for (i in r) {
			var nli = document.createElement('li');
			nli.className = "collection-item";
			nli.innerHTML = r[i].username +": "+r[i].content;
			ul.appendChild(nli);
		}
	});
}

function comment_it(e) {
	var id = e.parentNode.parentNode.parentNode.parentNode.parentNode.id.split('_').pop();
	post_form('/comment',
		{
			'creation_id': id,
			'content': e.previousElementSibling.value
		}, null);

}
