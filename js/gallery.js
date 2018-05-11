
function gallery_addimgs(imgs) {
	for (i in imgs) {
		var ac_div = document.getElementById('d_img_0');
		var div = ac_div.cloneNode(true);
		div.style.display = "";
		div.id = 'd_img_' + imgs[i].id;
		div.getElementsByTagName('span')[0].innerHTML = imgs[i].image;
		d_gallery.appendChild(div);
	}
	if (i < 4) {
		console.log(i);
		btn_moreimgs.className += " red";
		btn_moreimgs.innerHTML = "no more images";
	}

}

btn_moreimgs.addEventListener('click', function() {
	if (d_gallery.offset === undefined)
		d_gallery.offset = 5;
	api_get('/gallery/' + d_gallery.offset, gallery_addimgs);
	d_gallery.offset += 5;
});
