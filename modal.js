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

	$('textarea').click(function () {
		var t = this;
		setTimeout(function () { t.select(); }, 50);
	})

});