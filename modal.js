$(function(){
	autosize($('textarea'));

	$('.fb-close').click(function(){
		parent.fwjQ.fancybox.close();
	});

	$('.btn-save').click(function(){
		$('#fweMainForm').submit();
	});

	$(document).on('keydown', null, 'Ctrl+s', function() {
		$('#fweMainForm').submit();
		return false;
	});

	$('#fweMainForm').submit(function(){
		var t = $(this);
		var data = t.serialize();
		t.html('');
		$.post('/fwe/core.php?a=set', data, function(data){
			$('#fweMainForm').html(data);
			autosize($('textarea'));
		});
		return false;
	});

	$('textarea').click(function (e) {
		var t = this;
		if(e.shiftKey) {
			setTimeout(function () { t.select(); }, 50);
		}
	})

	var title = $('#title');
	var description = $('#description');
	var keywords = $('#keywords');
	var h1 = $('#h1');
	var meta = window.parent.FWE_getMeta();

	if (!title.val()) {
		title.val(meta.title);
	}
	if (!description.val()) {
		description.val(meta.description);
	}
	if (!keywords.val()) {
		keywords.val(meta.keywords);
	}
	if (!h1.val()) {
		h1.val(meta.h1);
	}


});
