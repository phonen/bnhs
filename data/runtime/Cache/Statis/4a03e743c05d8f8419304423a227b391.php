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
			<li class="active"><a href="<?php echo U('User/index');?>">公众号用户分析</a></li>
			<li><a href="<?php echo U('User/envelop_user');?>">红包用户分析</a></li>
		</ul>
		<!--用户总统计-->
		<div class="well text-center" style="overflow: hidden; background: #f4f4f4;">
			<div style="float: left; width:24%; padding: 40px 0; border-right:1px solid #fcfcfc;">新关注人数 <b><font class="new_user">0</font></b></div>
			<div style="float: left; width:24%; padding: 40px 0; border-right:1px solid #fcfcfc; border-left:1px solid #e5e5e5">取消关注人数 <b><font class="cancel_user">0</font></b></div>
			<div style="float: left; width:24%; padding: 40px 0; border-right:1px solid #fcfcfc; border-left:1px solid #e5e5e5">净增关注人数 <b><font class="increase_user">0</font></b></div>
			<div style="float: left; width:24%; padding: 40px 0; border-left:1px solid #e5e5e5">累积关注人数 <b><font class="cumulate_user">0</font></b></div>
		</div>

		<div class="ctr-user-type">
			<li class="on" attr-name="newUserArr">新关注</li>
			<li attr-name="cancelUserArr">取消关注</li>
			<li attr-name="increaseUserArr">净增关注</li>
			<li attr-name="cumulateUserArr">用户总数量</li>
		</div>

		<p>趋势图 - 时间：
			<input type="text" name="begin_date" class="js-datetime" value="<?php echo ((isset($formget["start_time"]) && ($formget["start_time"] !== ""))?($formget["start_time"]):''); ?>" style="width: 120px; margin-top:10px;" autocomplete="off">-
			<input type="text" class="js-datetime" name="end_date" value="<?php echo ((isset($formget["end_time"]) && ($formget["end_time"] !== ""))?($formget["end_time"]):''); ?>" style="width: 120px; margin-top:10px;" autocomplete="off">
			<input type="submit" class="btn btn-primary" name="submit" value="确定">
		</p>
		<div id="line" style="width:100%;height:492px;"></div>

		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th>日期</th>
					<th>新关注</th>
					<th>取消关注</th>
					<th>净增关注</th>
					<th>用户总数量</th>
				</tr>
			</thead>
			<tbody class="user-info-con"></tbody>
		</table>
	</div>


	<style>
		.ctr-user-type{
			/*border: 1px solid#e8e8e8;*/
			text-align: center;
			overflow: hidden;
			height: 35px;
			padding-left:5px;
		}
		.ctr-user-type li {
			border-radius: 2px;
			list-style: none;
			float:left;
			padding:0 20px;
			height: 30px;
			line-height: 30px;
			border-top:1px solid#e8e8e8;
			border-bottom:1px solid#e8e8e8;
			border-right:1px solid#e8e8e8;
			margin:0 -5px;
		}
		.ctr-user-type>li.on {
			background: #dbe5e7;
			color:#1abc9c;
		}
		.ctr-user-type>li:first-child {
			border-left:1px solid #e8e8e8;
		}
	</style>
	<script src="/public/js/common.js"></script>
	<script src="/public/js/echarts/echarts.min.js"></script>
	<script src="/public/js/layer/layer.js"></script>

<script>
	//趋势图变量初始化
	var userInfoObject = {},
		$userNameInfo = {
            newUserArr:'新关注',
            cancelUserArr:'取消关注',
            increaseUserArr:'净增关注',
            cumulateUserArr:'用户总数量',
		},
		$info={
        //折线图
        line:function (typeName) {
            var line = echarts.init(document.getElementById('line'));
            line.setOption({
                color:["#32d2c9"],
                title: {
                    x: 'left',
                    text: '',
                    textStyle: {
                        fontSize: '16',
                        color: '#e8e8e8',
                        fontWeight: 'none'
                    }
                },
                tooltip: {
                    trigger: 'axis'
                },
                toolbox: {
                    show: true,
                    feature: {
                        dataZoom: {
                            yAxisIndex: 'none'
                        },
                        dataView: {readOnly: false},
                        magicType: {type: ['line', 'bar']}
                    }
                },
                xAxis:  {
                    type: 'category',
                    boundaryGap: false,
                    data: userInfoObject.dateArr,
                    axisLabel: {
                        interval:0
                    }
                },
                yAxis: {
                    type: 'value'
                },
                series: [
                    {
                        name:$userNameInfo[typeName],
                        type:'line',
                        data:userInfoObject[typeName],
//                        data:[0,5000,9000,16000,20000,28000,33000],
                        markLine: {data: [{type: 'average', name: '平均值'}]}
                    }
                ]
            }) ;
        },
		//异步请求方法
		get_userinfo:function(url,data,callback){
            //获取用户数据
            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: data,
                success: function (data) {
                    callback(data);
                }
            });
		},
		//设置用户数据到页面
		set_userinfo:function(res){
			var new_user=0,cancel_user=0,increase_user=0,cumulate_user=0;
			if(res.code!==20000){
			    var msg=res.msg || res.info
				layer.alert(msg, {
                    skin: 'layui-layer-molv' //样式类名
                    ,closeBtn: 0
                    ,anim: 4 //动画类型
                });
			}
            if(res.list){
                userInfoObject={
                    dateArr:[],
                    newUserArr:[],
                    cancelUserArr:[],
                    increaseUserArr:[],
                    cumulateUserArr:[],
                }
                var html_user = ''
				$.each(res.list,function(i,val){
                    val.new_user = val.new_user||0;
                    val.cancel_user = val.cancel_user||0;
				var increase = 0;
                    increase = val.new_user - val.cancel_user;
				    //日期赋值
                    userInfoObject.dateArr.push(val.ref_date);
                    //新关注
                    userInfoObject.newUserArr.push(val.new_user);
                    //取消关注
                    userInfoObject.cancelUserArr.push(val.cancel_user);
                    //净增关注
                    userInfoObject.increaseUserArr.push(increase);
                    //累积关注人数
                    userInfoObject.cumulateUserArr.push(val.cumulate_user);

					new_user += val.new_user;
					cancel_user += val.cancel_user;
					increase_user += increase;
					cumulate_user = val.cumulate_user;
					//表格列表组装
					html_user +=
						'<tr>' +
							'<td>'+val.ref_date+'</td>' +
							'<td>'+val.new_user+'</td>' +
							'<td>'+val.cancel_user+'</td>' +
							'<td>'+increase+'</td>' +
							'<td>'+val.cumulate_user+'</td>' +
						'</tr>';
					});
                //总体统计赋值
                $('.new_user').text(new_user);
                $('.cancel_user').text(cancel_user);
                $('.increase_user').text(increase_user);
                $('.cumulate_user').text(cumulate_user);
                //表格列表赋值
                $('.user-info-con').html(html_user);
			}
            $info.line('newUserArr');
		},
		//初始化开始、结束时间
		get_time:function(data){
            data= data * 24 || 24
			var myDate = new Date();
			myDate.setTime(myDate.getTime()-data*60*60*1000);
			s = myDate.getFullYear()+"-" + (myDate.getMonth()+1) + "-" + myDate.getDate();
			return  s;
		}
	}
	//初始化调取
	var star_time = $info.get_time(7);
	var end_time = $info.get_time();
		$('input[name="begin_date"]').val(star_time);
		$('input[name="end_date"]').val(end_time);
	var url="<?php echo U('User/get_usersummary');?>",
		data = {begin_date: star_time,end_date: end_time};
		$info.get_userinfo(url,data,$info.set_userinfo);

		//确定查询时间
		$('input[name="submit"]').on('click',function(){
            data = {begin_date: $('input[name="begin_date"]').val(),end_date: $('input[name="end_date"]').val()};
            $info.get_userinfo(url,data,$info.set_userinfo);
		})

		//图标显示数据切换
		$('.ctr-user-type li').on('click',function(){
		    $this = $(this);
            $this.addClass('on').siblings().removeClass('on');
            $info.line( $this.attr('attr-name'));
		})
</script>
</body>
</html>