/*
navigator.getUserMedia = ( navigator.getUserMedia ||
	navigator.webkitGetUserMedia ||
	navigator.mediaDevices.getUserMedia ||
	navigator.msGetUserMedia);
promise = navigator.mediaDevices.getUserMedia({
	audio: false,
	video: { width: 1280, height: 720 }
});

if (navigator.mediaDevices.getUserMedia) {
console.log('asking for cam ...');
navigator.mediaDevices.getUserMedia({ video: true, audio: false },
	function(localMediaStream) {
		console.log('cam ok');
		cam.src = window.URL.createObjectURL(localMediaStream);
		webcamStream = localMediaStream;
	},
	function(err) {
		console.log('cam failed');
		console.log("The following error occured: " + err);
	}
);
} else
	console.log("getUserMedia not supported");
	*/

promise = navigator.mediaDevices.getUserMedia({audio: false, video: true})
	.then(function(mediaStream) {
		cam.srcObject = mediaStream;
		cam.onloadedmetadata = function(e) {
			cam.play();
		};
	})
	.catch(function(err) {
		//cam.remove();
		var alternative = document.createElement('h2');
		alternative.className += 'center-align';
		alternative.innerHTML = 'No cam';
		preview.appendChild(alternative);
		console.log(err.name + ": " + err.message);
	});

function take_photo() {
	var canv = document.getElementById('canvas_photo'); //document.createElement('canvas');

	ctx = canv.getContext('2d');
	ctx.drawImage(cam, 0,0, canv.width, canv.height);
	page_content.append(canv);
}
