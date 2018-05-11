
function onform_sent(resp) {
	console.log(resp);
}

creation_mask.addEventListener('change', function() {
	post_form('/creation', {'file': creation_mask.files[0]}, onform_sent);
});
