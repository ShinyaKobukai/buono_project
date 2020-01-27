$(function(){
	$("#search_form").hide();
	// $("#black-layer").hide();

  $('#search_button').click(function(){
  	//クリックしたときの状態でifが分かれる

  	if( $(this).hasClass("on") ){

  		//search
  		$(this).removeClass("on");
  		$("#search_form").hide();
      // $("#black-layer").show();
    } else {
      
      //close
      $(this).addClass("on");
      $("#search_form").show();
  		// $("#black-layer").hide();
  	}
 });
});