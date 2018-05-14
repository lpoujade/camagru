promise = navigator.mediaDevices.getUserMedia({
	audio: false,
	video: { width: 1280, height: 720 }
});

promise = navigator.mediaDevices.getUserMedia({audio: false, video: true})
	.then(function(mediaStream) {
		console.log('cam ok');
		cam.srcObject = mediaStream;
		cam.onloadedmetadata = function(e) {
			cam.play();
		};
		cam.imgCapture = new ImageCapture(mediaStream.getVideoTracks()[0]);
		/*
		stream = mediaStream;
		let mediaStreamTrack = mediaStream.getVideoTracks()[0];
		imageCapture = new ImageCapture(mediaStreamTrack);
		console.log(imageCapture);
		*/
	})
	.catch(function(err) {
		//cam.remove();
		var alternative = document.createElement('h2');
		alternative.className += 'center-align';
		alternative.innerHTML = 'No cam';
		preview.appendChild(alternative);
		console.log(err.name + ": " + err.message);
	});
