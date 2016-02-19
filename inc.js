fwjQ(function($){
  var id = $('meta[name="_id"]').attr('content');
  if(id) $('body').append('<button id="fweMainModalButton" type="button">Edit ' + id + '</button>');
  /*$(window).resize(function() {
        var h = ;
        var w = $(window).width();
        $("#fweMainModal").height(h).width(w);
    }).resize();*/
  $('#fweMainModalButton').click(function(){
    $.fancybox({href:'/fwe/core.php?a=get&id=' + id, type:'iframe', height:$(window).height(), width:950, fitToView: false, autoSize: false});
    // $(window).resize();
  });
});
//core.php?a=get&id=' + id + '