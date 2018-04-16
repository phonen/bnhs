<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
	<![endif]-->
	<script type="text/javascript">
	//全局变量
	var GV = {
	    ROOT: "/",
	    WEB_ROOT: "/",
	    JS_ROOT: "public/js/",
	    APP:'<?php echo (MODULE_NAME); ?>'/*当前应用名*/
	};
	</script>
    <script src="/public/js/jquery.js"></script>
    <script src="/public/js/layer/layer.js"></script>
    <script src="/public/js/wind.js"></script>
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
    <script>
    	$(function(){
    		$("[data-toggle='tooltip']").tooltip();
    	});
    </script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
</head>
<body>
<div class="wrap js-check-wrap">
	<ul class="nav nav-tabs">
		<li class="active"><a href="<?php echo U('Capital/pay');?>">领取红包明细</a></li>
		<!--<li><a href="<?php echo U('Capital/pay');?>">已支付</a></li>-->
		<!--<li><a href="<?php echo U('Capital/pay');?>">未支付</a></li>-->
	</ul>
	<form class="well form-search" method="post" action="<?php echo U('Capital/pay');?>">
		时间：
		<input type="text" name="start_time" class="js-datetime" value="<?php echo I('request.start_time/s','');?>" style="width: 120px;" autocomplete="off">-
		<input type="text" class="js-datetime" name="end_time" value="<?php echo I('request.end_time/s','');?>" style="width: 120px;" autocomplete="off"> &nbsp; &nbsp;
		<input type="submit" class="btn btn-primary" value="搜索" />
		<a class="btn btn-danger" href="<?php echo U('Capital/pay');?>">清空</a>
	</form>
	<table class="table table-hover table-bordered">
		<thead>
		<tr>
			<th width="50">ID</th>
			<th>发红包用户昵称</th>
			<th>所领取的红包</th>
			<th>昵称</th>
			<th>头像</th>
			<th>领取金额</th>
			<th>领取个数</th>
			<th>语音</th>
			<th>语音识别答案</th>
			<th>领取时间</th>
		</tr>
		</thead>
		<tbody>
		<?php if(is_array($info)): foreach($info as $key=>$vo): ?><tr>
				<td><?php echo ($vo["id"]); ?></td>
				<td><?php echo ($vo["send_user_name"]); ?></td>
				<td><?php echo ($vo["quest"]); ?></td>
				<td><?php echo ($vo["nick_name"]); ?></td>
				<td><img src="<?php echo ($vo["head_img"]); ?>" width="50" height="50"/></td>
				<td>￥<?php echo ($vo["receive_amount"]); ?></td>
				<td><?php echo ($vo["receive_num"]); ?></td>
				<td><?php echo ($vo["voice_url"]); ?></td>
				<td><?php echo ($vo["receive_answer"]); ?></td>
				<td><?php echo ($vo["add_time"]); ?></td>
			</tr><?php endforeach; endif; ?>
		</tbody>
	</table>
	<div class="pagination"><?php echo ($page); ?></div>
</div>
<script src="/public/js/common.js"></script>
<script>
    $('.img-show').on('click',function(){
        var img_src = $(this).attr('attr-img');
        console.log(img_src);
        //页面层-自定义
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            skin: 'yourclass',
            content: '<img src="'+img_src+'"/>'
        });
    })

</script>
</body>
</html>