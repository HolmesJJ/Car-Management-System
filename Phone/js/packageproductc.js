//the applicable service package related

function showhid(id){
 	document.getElementById(id).style.display ='block';
}
function showhid2(id){
 	document.getElementById(id).style.display ='none';
}
function showhid3(){
	document.getElementById("filtrate-content").style.display = 'none';
	document.getElementById("filtrate-content-1").style.display = 'block';
}
function showhid4(){
	document.getElementById("filtrate-content-1").style.display = 'none';
	document.getElementById("filtrate-content").style.display = 'block';
}
function show_border(id){
	document.getElementById(id).style.color="#fff";
	document.getElementById(id).style.background="#FE5417";
}
function show_out(id){
	document.getElementById(id).style.color="#FE5417";
	document.getElementById(id).style.background="#FFFFFF";
}
function show_border_map(id){
	document.getElementById(id).style.color="#fff";
	document.getElementById(id).style.background="#FE5417";
    document.getElementById("map").style.display = 'block';
    document.getElementById("map").style.backgroundImage="url('images/aside/shop_map/shop"+id+".jpg')";
}
function show_out_all(id){
	document.getElementById(id).style.color="#FE5417";
	document.getElementById(id).style.background="#FFFFFF";
    document.getElementById("map").style.display="none";
}
function show_hide(id1,id2){
	document.getElementById(id1).style.display = 'none';
	document.getElementById(id2).style.display = 'block';
}
$(document).ready(function() {
	$(".log-title span:first").addClass("log-title-select").show(); 
	$(".log-title span").click(function() {
		$(".log-title span").removeClass("log-title-select"); 
		$(this).addClass("log-title-select"); 
		return false;
	});
});
$(document).ready(function() {
	$(".main-menu ul li:first").addClass("main-menu-select").show(); 
	$(".main-menu ul li").click(function() {
		$(".main-menu ul li").removeClass("main-menu-select"); 
		$(this).addClass("main-menu-select"); 
		return false;
	});
});
$(document).ready(function(){
	$(".maintain-suggest").click(function(){
		$(".maintain-suggest").removeClass("maintain-suggest-select");
		$(this).addClass("maintain-suggest-select");
		return false;
	});
});
$(document).ready(function(){
	$(".filtrate-banner ul li.first").addClass("filtrate-select").show();
	$(".filtrate-banner ul li").click(function(){
		$(".filtrate-banner ul li").removeClass("filtrate-select");
		$(this).addClass("filtrate-select");
	});
});
$(document).ready(function(){
	$(".filtrate_right li a.first").addClass("other-select").show();
	$(".filtrate_right li a").click(function(){
		$(".filtrate_right li a").removeClass("other-select");
		$(this).addClass("other-select");
		return false;
	});
});
$(document).ready(function(){
	$(".filtrate_right_1 li a.first").addClass("other-select").show();
	$(".filtrate_right_1 li a").click(function(){
		$(".filtrate_right_1 li a").removeClass("other-select");
		$(this).addClass("other-select");
		return false;
	});
});

//???????????????????????????????????????????????????????????????????????????????????????
function click_hide(id,cont) {
	if($("#"+id).val() == cont) {
	   $("#"+id).val('');
	}
}
function onblur_show(id,cont) {
	if($("#"+id).val() == '') {
		$("#"+id).val(cont);
	}
}

function main_show(id,title) {
	//$("html,body").animate({scrollTop:$("#"+title).offset().top},1000);
	if($("#"+id).is(":hidden")) {
		$("#gt_"+id).html('&lt;&lt;');
		$("#"+id).slideDown("slow");
	} else {
		$("#gt_"+id).html('&gt;&gt;');
		$("#"+id).slideUp("slow");
	}
}

$(document).ready(function() {
	//??????????????????????????????
	$("#onecar_buytime").width(180);
	//??????????????????????????????????????????
	$("#check_maintain").click(function () {
		if($("#j_mile").val() == '') {
			$("#check_maintain_err").html('???????????????????????????');
			return false;
		}
		if(isNaN($("#j_mile").val())) {
			$("#check_maintain_err").html('????????????????????????0?????????!');
			return false;
		}
		if($("#onecar_buytime").val() == '') {
			$("#check_maintain_err").html('??????????????????????????????');
			return false;
		}
	});
});

//???????????????????????????????????????????????????
function toJump(url) {
	 window.location.href = url;
}