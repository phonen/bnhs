<?php
/**
 * 基本配置
 * author hhz 2017.10.12
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class BasicConfigController extends AdminbaseController{
	
	protected $options_model;
	
	public function _initialize() {
		parent::_initialize();
		$this->options_model = D("Common/Options");
	}

	//页面展示
	public function index(){
        $option=$this->options_model->where("option_name='customer_options'")->find();
        if($option){
            $this->assign(json_decode($option['option_value'],true));
            $this->assign("option_id",$option['option_id']);
        }
	    $this->display();
    }

    // 网站信息设置提交
    public function site_post()
    {
        if (IS_POST) {
            if (isset($_POST['option_id'])) {
                $data['option_id'] = I('post.option_id', 0, 'intval');
            }
            $options = I('post.options/a');
            //公众号配置
            $configs["G_APPID"] = $options['G_APPID'];
            $configs["G_APPSECRET"] = $options['G_APPSECRET'];
            $configs["G_MCHID"] = $options['G_MCHID'];
            $configs["G_KEY"] = $options['G_KEY'];
            $configs["F_MCHID"] = $options['F_MCHID'];
            $configs["F_KEY"] = $options['F_KEY'];
            //小程序
            $configs["C_APPID"] = $options['C_APPID'];
            $configs["C_APPSECRET"] = $options['C_APPSECRET'];
            $configs["C_JUMP"] = $options['C_JUMP'];
            $configs["HB_COMMISION"] = $options['commision'];
            $configs["HB_ADV_COMMISION"] = $options['advcommision'];
            $configs["AMOUNT_MIN"] = $options['amount_min'];
            $configs["RECEIVE_AMOUNT_MIN"] = $options['receive_amount_min'];
            $configs["MIN_WITHDRAWALS"] = $options['min_withdrawals'];
            $configs["MAX_WITHDRAWAL_TIME"] = $options['max_withdrawal_time'];
            $news_tpl = explode(',',$options['new_tpl']);
            foreach ($news_tpl as $v){
                $val = explode(':',$v);
                $configs[trim($val[0])] =  trim($val[1]);
            }
//            var_dump($options);die;

            sp_set_dynamic_config($configs);//sae use same function
            
            if (isset($options['imgurl']) && strpos($options['imgurl'], C('TMPL_PARSE_STRING.__UPLOAD__')) == false) {
            	$options['imgurl'] = C('TMPL_PARSE_STRING.__UPLOAD__').$options['imgurl'];
            }

            $data['option_name'] = "customer_options";
            $data['option_value'] = json_encode($options);
            if ($this->options_model->where("option_name='customer_options'")->find()) {
                $result = $this->options_model->where("option_name='customer_options'")->save($data);
            } else {
                $result = $this->options_model->add($data);
            }

            if ($result !== false) {
                $this->success("保存成功！");
            } else {
                $this->error("保存失败！");
            }
        }
    }

}