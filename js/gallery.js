
cards = [];
c_img_0.id = 'c_img_';
form_sendcomment_0.id = 'form_sendcomment_';
void_card = document.getElementById('d_img_0');
void_card.remove();
void_card.id = 'd_img_';
void_card.style.display = "";
void_card.addLike = function(likes_count) {
	var a = this.getElementsByClassName('a_likes')[0];
	this.likes_count = likes_count;
	a.innerHTML += ' ('+likes_count+')';
};
void_card.newLike = function() {
	var a = this.getElementsByClassName('a_likes')[0];
	this.likes_count += 1;
	a.innerHTML = 'like (' + this.likes_count + ')';
};
void_card.addComment = function(r) {
	var ul = this.getElementsByClassName('ul_comment')[0];
	for (i in r) {
		var nli = document.createElement('li');
		nli.className = "collection-item";
		nli.innerHTML = r[i].username +": "+r[i].content;
		ul.appendChild(nli);
	}
};

function gallery_addimgs(imgs) {
	if (changes) {
		for ( d in cards ) {
			cards[d].remove();
		}
		changes = false;
		d_gallery.offset = 5;
		btn_moreimgs.removeAttribute("disabled");
		btn_moreimgs.innerHTML = "More images";
	}
	var i = 0;
	for (i in imgs) {
		var div = void_card.cloneNode(true);
		Object.assign(div, void_card);
		div.id += imgs[i].id;
		div.getElementsByTagName('form')[0].id += imgs[i].id;
		div.getElementsByTagName('form')[0].addEventListener('submit', comment_it);
		div.getElementsByTagName('img')[0].src = "/datas/"+imgs[i].id+".png";
		div.addLike(parseInt(imgs[i].likes_count));
		d_gallery.appendChild(div);
		cards.push(div);
	}
	if (i < 4) {
		btn_moreimgs.innerHTML = "no more images";
		btn_moreimgs.setAttribute("disabled", true);
	}
}

btn_moreimgs.addEventListener('click', function() {
	if (d_gallery.offset === undefined)
		d_gallery.offset = 5;
	api_get('/gallery/' + d_gallery.offset, gallery_addimgs);
	d_gallery.offset += 5;
});
