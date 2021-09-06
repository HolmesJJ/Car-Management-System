    // 如果只设置一个或少量全局配置也可这样：
	var date1 = '<?php echo date("Y-m-d");?>';
    $.dialog.setting.extendDrag = true;

    $(document).ready(function()
    {
        $('#changeproductinshop').click(function()
        {
            $.dialog({id:"changeproduct",content:$('#changeproduct').html()});
            $('.ui_close').hide();
        });
		$('#baoyangdaohangt').click(function()
		{
			$('#baoyangdaohang').show();
			$('#baoyangfuwu').hide();
			$('#weixiufuwu').hide();
			$('#fuwuzhan').hide();
			$(this).attr('class','yxz');
			$('#weixiufuwut').attr('class','wxz');
			$('#weixiufuwut #weixiu2').show();
			$('#weixiufuwut #weixiu_2').show();
			$('#weixiufuwut #weixiu1').hide();
			$('#weixiufuwut #weixiu_1').hide();
			$('#baoyangfuwut').attr('class','wxz');
			$('#baoyangdaohangt #baoyang2').hide();
			$('#baoyangdaohangt #baoyang_2').hide();
			$('#baoyangdaohangt #baoyang1').show();
			$('#baoyangdaohangt #baoyang_1').show();
			$('#baoyangfuwut #jianyi1').hide();
			$('#baoyangfuwut #jianyi_1').hide();
			$('#baoyangfuwut #jianyi2').show();
			$('#baoyangfuwut #jianyi_2').show();
			$('.wxz').css('background','#4eb233');
			$('.yxz').css('background','#fff');
		});
		$('#baoyangfuwut').click(function()
		{
			$(this).attr('class','yxz');
			$('#baoyangdaohangt').attr('class','wxz');
			$('#weixiufuwut').attr('class','wxz');
			$('#baoyangfuwu').show();
			$('#baoyangdaohang').hide();
			$('#weixiufuwu').hide();
			$('#weixiufuwut #weixiu2').show();
			$('#weixiufuwut #weixiu1').hide();
			$('#weixiufuwut #weixiu_2').show();
			$('#weixiufuwut #weixiu_1').hide();
			$('#fuwuzhan').hide();
			$('#baoyangdaohangt #baoyang1').hide();
			$('#baoyangdaohangt #baoyang2').show();
			$('#baoyangdaohangt #baoyang_1').hide();
			$('#baoyangdaohangt #baoyang_2').show();
			$('#baoyangfuwut #jianyi2').hide();
			$('#baoyangfuwut #jianyi1').show();
			$('#baoyangfuwut #jianyi_2').hide();
			$('#baoyangfuwut #jianyi_1').show();
			$('.wxz').css('background','#4eb233');
			$('.yxz').css('background','#fff');
		});
		
		$('#weixiufuwut').click(function()
		{
			$('#weixiufuwu').show();
			$('#baoyangfuwu').hide();
			$('#baoyangdaohang').hide();
			$('#fuwuzhan').hide();
			$(this).attr('class','yxz');
			$('#weixiufuwut #weixiu1').show();
			$('#weixiufuwut #weixiu2').hide();
			$('#weixiufuwut #weixiu_1').show();
			$('#weixiufuwut #weixiu_2').hide();
			$('#baoyangdaohangt').attr('class','wxz');
			$('#baoyangdaohangt #baoyang1').hide();
			$('#baoyangdaohangt #baoyang2').show();
			$('#baoyangdaohangt #baoyang_1').hide();
			$('#baoyangdaohangt #baoyang_2').show();
			$('#baoyangfuwut').attr('class','wxz');
			$('#baoyangfuwut #jianyi2').show();
			$('#baoyangfuwut #jianyi1').hide();
			$('#baoyangfuwut #jianyi_2').show();
			$('#baoyangfuwut #jianyi_1').hide();
			$('.wxz').css('background','#4eb233');
			$('.yxz').css('background','#fff');
		});
		
		$("#onecar_buytime").attr('size','20');
		$("#onecar_buytime").click(function(){ tdhide();});
		$('.month_nav').find('.prev').click(function(){ tdhide();});
		$('.month_nav').find('.next').click(function(){ tdhide();});
		$('.year_nav').find('.prev').click(function(){ tdhide();});
		$('.year_nav').find('.next').click(function(){ tdhide();});
		$('.originalprice').click(function()
		{
			$('.cartype').css('border-color','#FE5417');
			$('.cartype').css('box-shadow','0 0 5px #FE5417');
			$('.cartype').css('border-width','1px');
			$('.cartype').css('border-style','solid');
		});
    });
