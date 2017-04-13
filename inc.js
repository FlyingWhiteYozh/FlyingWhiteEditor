fwjQ(function($){
  var id = {};//$('meta[name="_id"]').attr('content');
  var i = 0;
  $('meta[name="_id"]').each(function(){
    id['id[' + i + ']'] = this.content;
    i++;
  });
  //console.log(id);
  if(Object.keys(id).length) $('body').append('<button id="fweMainModalButton" type="button">Edit ' + $.map(id,function(v){return v;}).join(';') + '</button>');

  $('#fweMainModalButton').click(function(){
    $.fancybox({href:'/fwe/core.php?a=get&' + $.param(id), type:'iframe', height:$(window).height(), width:950, fitToView: false, autoSize: false});
  });
});

function FWE_getMeta()
{
  return {
    title: fwjQ('title').html(),
    description: fwjQ('meta[name="description"]').attr('content'),
    keywords: fwjQ('meta[name="keywords"]').attr('content'),
    h1: fwjQ('h1').html(),
  }
}
