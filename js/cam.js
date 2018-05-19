
function start_cam() {
	promise = navigator.mediaDevices.getUserMedia({audio: false, video: true})
		.then(function(mediaStream) {
			cam.srcObject = mediaStream;
			cam.onloadedmetadata = function(e) {
				cam.play();
			};
		})
	.catch(function(err) {
		cam.remove();
		if (!document.getElementById('fakewebcam')) {
			var alternative = document.createElement('h3');
			alternative.id = "fakewebcam";
			alternative.className += 'center-align';
			alternative.innerHTML = 'No cam, instead you can select a file';
			preview.appendChild(alternative);
			creation_mask.style.display = "";
		}
	});
}

function stop_webcam() {
	cam.srcObject.getTracks()[0].stop()
}

function take_photo() {
	if (document.getElementById('cam')) {
		var canv = document.createElement('canvas');

		canv.height = cam.videoHeight;
		canv.width = cam.videoWidth;

		ctx = canv.getContext('2d');
		ctx.drawImage(cam, 0,0, canv.width, canv.height);
		var photo = canv.toDataURL("image/png");
		offsetTop = cam.offsetTop;
		offsetLeft = cam.offsetLeft;
	}
	else {
		photo = preview.firstElementChild.src;
		offsetTop = photo.offsetTop;
		offsetLeft = photo.offsetLeft;
	}
	var calcs = [];
	for (i in calc) {
		calcs.push({'image': calc[i].src.split('/').pop(),
			'ofTop': calc[i].offsetTop - offsetTop,
			'ofLeft': calc[i].offsetLeft - offsetLeft,
			'width': calc[i].width, 'height': calc[i].height});
	}
	post_form('/creation', {'photo': photo, 'calcs': JSON.stringify(calcs)}, function(r) {
		if (r.status === true) {
			var ac_div = document.getElementById('d_userimg_0');
			var div = ac_div.cloneNode(true);
			div.style.display = "";
			div.id = 'd_userimg_' + r.image.id;
			div.getElementsByTagName('img')[0].id += r.image.id;
			div.getElementsByTagName('img')[0].src = "/datas/"+r.image.id+".png";
			div.getElementsByClassName('btn')[0].addEventListener('click', function() {
				var elem = this.parentNode.parentNode;
				api_get('/creation/delete/' + elem.id.split('_').pop());
				var elem_gallery = document.getElementById('d_img_' + elem.id.split('_').pop());
				if (elem_gallery)
					elem_gallery.remove()
						elem.remove();
			});
			d_userimg.insertBefore(div, d_userimg.firstElementChild.nextElementSibling);
		}
	});
}
