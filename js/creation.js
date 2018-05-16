
function onform_sent(resp) {
	console.log(resp);
}

calc = [];

function handleFile(files) {
	var file = files[0];

	if (!file.type.startsWith('image/')){ alert('File not valid'); exit; }

	/* show file */
	if (typeof filters != 'undefined') {
		var img = document.createElement("img");
		img.classList.add("col");
		img.classList.add("s2");
		img.addEventListener('click', function() {
			calc.push(this.cloneNode());
			preview.appendChild(calc[calc.length - 1]);
			calc[calc.length - 1].style.position = "absolute";
			calc[calc.length - 1].style.cursor = "move";
			dragElement(calc[calc.length - 1]);
			btn_capture.removeAttribute("disabled");
		});
		img.file = file;
		filters.appendChild(img);

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
		// get the mouse cursor position at startup:
		pos3 = e.clientX;
		pos4 = e.clientY;
		document.onmouseup = closeDragElement;
		// call a function whenever the cursor moves:
		document.onmousemove = elementDrag;
	}

	function elementDrag(e) {
		e = e || window.event;
		// calculate the new cursor position:
		pos1 = pos3 - e.clientX;
		pos2 = pos4 - e.clientY;
		pos3 = e.clientX;
		pos4 = e.clientY;
		// set the element's new position:
		elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
		elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
	}

	function closeDragElement() {
		/* stop moving when mouse button is released:*/
		document.onmouseup = null;
		document.onmousemove = null;
	}
}

creation_mask.addEventListener('change', function() {
	handleFile(creation_mask.files);
	//post_form('/creation', {'file': creation_mask.files[0]}, onform_sent);
});

btn_capture.addEventListener('click', function() {
	take_photo();
});
