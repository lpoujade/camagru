
function onform_sent(resp) {
	console.log(resp);
}

function handleFile(files) {
	var file = files[0];

	if (!file.type.startsWith('image/')){ alert('File not valid'); exit; }

	/* show file */
	if (typeof filters != 'undefined') {
		var img = document.createElement("img");
		img.classList.add("col");
		img.classList.add("s2");
		img.file = file;
		filters.appendChild(img);

		var reader = new FileReader();
		reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
		reader.readAsDataURL(file);
	}
}

creation_mask.addEventListener('change', function() {
	handleFile(creation_mask.files);
	//post_form('/creation', {'file': creation_mask.files[0]}, onform_sent);
});
