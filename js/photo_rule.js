$(function(){
  $('form').submit(function(){
    if(document.querySelector("#photo").files.length > 4){
      alert("画像の枚数は4枚までです。");
      return false;
    }
  });
});
