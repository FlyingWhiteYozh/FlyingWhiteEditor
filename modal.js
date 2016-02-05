$(function(){
	autosize($('textarea'));
	
	$('.fb-close').click(function(){
		parent.fwjQ.fancybox.close();
	});

	$('.btn-save').click(function(){
		$('#fweMainForm').submit();
	});

	$('#fweMainForm').submit(function(){
		var data = $(this).serialize();
		$(this).html('');
		$.post('/fwe/core.php?a=set', data, function(data){
			$('#fweMainForm').html(data);
			autosize($('textarea'));
		});
		return false;
	});
});