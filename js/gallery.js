
cards = [];
c_img_0.id = 'c_img_';
void_card = document.getElementById('d_img_0');
void_card.remove();
void_card.id = 'd_img_';
void_card.style.display = "";
void_card.addLike = function(likes_count) {
	var a = this.getElementsByClassName('a_likes')[0];
	a.innerHTML += ' ('+likes_count+')';
};
void_card.datas = {'likes_count': 0, 'likes': {}};

function gallery_addimgs(imgs) {
	if (changes) {
		for ( d in cards ) {
			cards[d].remove();
		}
		changes = false;
		d_gallery.offset = 5;
	}
	for (i in imgs) {
		var div = void_card.cloneNode(true);
		Object.assign(div, void_card);
		div.id += imgs[i].id;
		div.getElementsByTagName('img')[0].src = "/datas/"+imgs[i].id+".png";
		div.addLike(imgs[i].likes_count);
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
