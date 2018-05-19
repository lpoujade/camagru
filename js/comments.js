
prev_dul = null;

function comment_show(e) {
	var id = e.parentNode.parentNode.parentNode.id.split('_').pop();
	if (prev_dul)
		prev_dul.style.display = "none";
	e.nextElementSibling.style.display = "";
	prev_dul = e.nextElementSibling;

	ul = prev_dul.getElementsByClassName('ul_comment')[0];
	if (ul.childElementCount >= 1)
		return ;
	api_get('/comments/'+id, function(r) {
		var e = document.getElementById('d_img_' + id);
		e.addComment(r);
	});
}

function comment_it() {
	var id = this.id.split('_').pop();
	var com = this.firstElementChild.value;
	this.firstElementChild.value = "";
	console.log(this);
	post_form('/comment',
		{
			'creation_id': id,
			'content': com
		}, function(resp) {
			if (resp.status === true) {
				var e = document.getElementById('d_img_' + id);
				e.addComment([{'username': username, 'content': com}]);
			}
		});

}

function like_it(e) {
	var id = e.parentNode.parentNode.parentNode.id.split('_').pop();
	post_form('/like',
		{
			'creation_id': id
		}, function(resp) {
			if (resp.status === true) {
				var e = document.getElementById('d_img_' + id);
				e.newLike();
			}
		});
}
