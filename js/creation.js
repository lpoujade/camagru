
calc = [];

function handleFile(files) {
	var file = files[0];

	if (!file.type.startsWith('image/png')){
		notif({'status': false, 'reason': 'Bad file type, only PNG files are accepted'});
		return;
	}

	/* show file */
	if (typeof filters != 'undefined') {
		var img = document.createElement("img");
		img.file = file;
		while (preview.firstElementChild)
			preview.removeChild(preview.firstElementChild);
		preview.appendChild(img);

		var reader = new FileReader();
		reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
		reader.readAsDataURL(file);
	}
}

function dragElement(elmnt) {
	var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
	elmnt.onmousedown = dragMouseDown;

	function dragMouseDown(e) {
		e = e || window.event;
		pos3 = e.clientX;
		pos4 = e.clientY;
		document.onmouseup = closeDragElement;
		document.onmousemove = elementDrag;
	}

	function elementDrag(e) {
		e = e || window.event;
		pos1 = pos3 - e.clientX;
		pos2 = pos4 - e.clientY;
		pos3 = e.clientX;
		pos4 = e.clientY;
		elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
		elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
	}

	function closeDragElement() {
		document.onmouseup = null;
		document.onmousemove = null;
	}
}

btn_clearfilters.addEventListener('click', function() {
	for (c in calc) {
		calc[c].remove();
	}
	calc = [];
	btn_capture.setAttribute('disabled', true);
});

creation_mask.addEventListener('change', function() {
	handleFile(creation_mask.files);
});

btn_capture.addEventListener('click', take_photo);
