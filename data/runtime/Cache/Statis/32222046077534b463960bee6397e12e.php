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
			<li><a href="<?php echo U('User/index');?>">公众号用户分析</a></li>
			<li class="active"><a href="<?php echo U('User/envelop_user');?>">红包用户分析</a></li>
		</ul>

		<!--总统计-->
		<div class="well text-center" style="overflow: hidden; background: #f4f4f4;">
			<div style="float: left; width:24%; padding: 40px 0; border-right:1px solid #fcfcfc;">注册人数 <b><font class="new_user">0</font></b></div>
			<div style="float: left; width:24%; padding: 40px 0; border-right:1px solid #fcfcfc; border-left:1px solid #e5e5e5">发红包数量 <b><font class="cancel_user">0</font></b></div>
			<div style="float: left; width:24%; padding: 40px 0; border-right:1px solid #fcfcfc; border-left:1px solid #e5e5e5">领红包数量 <b><font class="increase_user">0</font></b></div>
			<div style="float: left; width:24%; padding: 40px 0; border-left:1px solid #e5e5e5">未支付红包个数 <b><font class="cumulate_user">0</font></b></div>
		</div>

		<p> 时间：
			<input type="text" name="begin_date" class="js-datetime" value="<?php echo ((isset($formget["start_time"]) && ($formget["start_time"] !== ""))?($formget["start_time"]):''); ?>" style="width: 120px; margin-top:10px;" autocomplete="off">-
			<input type="text" class="js-datetime" name="end_date" value="<?php echo ((isset($formget["end_time"]) && ($formget["end_time"] !== ""))?($formget["end_time"]):''); ?>" style="width: 120px; margin-top:10px;" autocomplete="off">
			<input type="submit" class="btn btn-primary" name="submit" value="确定">
		</p>
		<div id="line" style="width:100%;height:492px;"></div>
	</div>

	<script src="/public/js/common.js"></script>
	<script src="/public/js/echarts/echarts.min.js"></script>
	<script src="/public/js/layer/layer.js"></script>

<script>
	//趋势图变量初始化
	var userInfoObject={},
		$info={
        //饼图
		chart:function () {
            var myChart = echarts.init(document.getElementById('line'));
            var option = {
                title: {
                    text: '红包用户分析',
                    x:'center'
                },
                tooltip: {
                    trigger: 'item',
                    show: true,
                    formatter: "{a} <br/>{b} : {c} ({d}%)>"
                    //饼图中{a}表示系列名称，{b}表示数据项名称，{c}表示数值，{d}表示百分比
                },
                legend: {
                    //图例
                    orient: 'vertical',
                    left: 'left',
                    data: ['发红包数量','领红包数量','注册人数','未支付红包个数']
                },
                series: [{
                    name: '来源',
                    type: 'pie',
                    radius: '55%',
                    data: [
                        {value:userInfoObject.enveNum,name:'发红包数量'},
                        {value:userInfoObject.receiveNum,name:'领红包数量'},
                        {value:userInfoObject.userNum,name:'注册人数'},
                        {value:userInfoObject.nopaEynve,name:'未支付红包个数'},
                    ],
                    itemStyle: {
                        //itemStyle有正常显示：normal，有鼠标hover的高亮显示：emphasis
                        emphasis:{
                            //normal显示阴影,与shadow有关的都是阴影的设置
                            shadowBlur:10,//阴影大小
                            shadowOffsetX:0,//阴影水平方向上的偏移
                            shadowColor:'rgba(0,0,0,0.5)'//阴影颜色
                        },
                        normal:{
                            label:{
                                show: true,
                                formatter: '{b} : {c} ({d}%)'
                            },
                            labelLine :{show:true}
                        }
                    }
                }]
            };
            myChart.setOption(option);

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
		//设置数据到页面
		set_userinfo:function(res){
			if(res.code!==20000){
			    var msg=res.msg || res.info
				layer.alert(msg, {
                    skin: 'layui-layer-molv' //样式类名
                    ,closeBtn: 0
                    ,anim: 4 //动画类型
                });
			    return false;
			}
			//初始化饼图值
			userInfoObject={
				userNum:res.user_num,
                enveNum:res.enve_num,
				receiveNum:res.enve_receive_num,
                nopaEynve:res.nopay_enve,
			}
            //总体统计赋值
            $('.new_user').text(res.user_num);
            $('.cancel_user').text(res.enve_num);
            $('.increase_user').text(res.enve_receive_num);
            $('.cumulate_user').text(res.nopay_enve);
            $info.chart();
		},
		//初始化开始、结束时间
		get_time:function(data){
            data= data * 24 || 24
			var myDate = new Date();
			myDate.setTime(myDate.getTime() - data*60*60*1000);
			s = myDate.getFullYear()+"-" + (myDate.getMonth()+1) + "-" + myDate.getDate();
			return  s;
		}
	}
	//初始化调取
	var star_time = $info.get_time(7)+' 00:00';
	var end_time = $info.get_time()+' 23:59';
		$('input[name="begin_date"]').val(star_time);
		$('input[name="end_date"]').val(end_time);
	var url="<?php echo U('User/envelop_user');?>",
		data = {begin_date: star_time,end_date: end_time};
		$info.get_userinfo(url,data,$info.set_userinfo);

		//确定查询时间
		$('input[name="submit"]').on('click',function(){
            data = {begin_date: $('input[name="begin_date"]').val(),end_date: $('input[name="end_date"]').val()};
            $info.get_userinfo(url,data,$info.set_userinfo);
		})
</script>
</body>
</html>