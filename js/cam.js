
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
		}
		console.log("start_cam(): " + err.name + ": " + err.message);
	});
}

function stop_webcam() {
	cam.srcObject.getTracks()[0].stop()
}

function take_photo() {
	var canv = document.createElement('canvas');

	canv.height = cam.videoHeight;
	canv.width = cam.videoWidth;

	ctx = canv.getContext('2d');
	ctx.drawImage(cam, 0,0, canv.width, canv.height);
	var calcs = [];
	for (i in calc) {
		calcs.push({'image': calc[i].src,
			'ofTop': calc[i].offsetTop - cam.offsetTop,
			'ofLeft': calc[i].offsetLeft - cam.offsetLeft,
			'width': calc[i].width, 'height': calc[i].height});
	}
	console.log(calcs);
	post_form('/creation', {'photo': canv.toDataURL("image/png"), 'calcs': JSON.stringify(calcs)}, function() {console.log('image posted');});
}
