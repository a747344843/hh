<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\Cookie;

class Index extends Controller
{
	public function _initialize()
    {
        //初始化，以后设计用
    }
    //热门列表
    public function mesList(){
        $c_id=Request::instance()->get('c_id');
        $c_id = isset($c_id) ? $c_id + 0 : 0;
        if($c_id){
            $catlist=Db::table('article')->where(['c_id'=>$c_id])->limit(6)->select();

        }else{
            $catlist=Db::table('article')->limit(6)->select();
        }
        //展示标题信息
        $cat=Db::table('category_article')->limit(5)->select();
        $this -> assign('cat',$cat);
        $this -> assign('catlist',$catlist);
        //根据标题信息查询详细内容
        return $this->fetch('meslist');
    }

    //热门内容
    public function mesShow(){
        return view('messhow');
    }
    //案例列表
    public function skeleton()
    {
        $banner = Db::table('online_report')->join("onlinetest","online_report.type_id = onlinetest.register_id")->select();
//         var_dump($banner);die;
        $this->assign('banner',$banner);
        return $this->fetch('skeleton');
    }
    //案例详情
    public function details(Request $request)
    {
        $id = $request->get("id");
        $where=array("online_report.o_id"=>$id);
        $show=Db::table('online_report')->join("onlinetest","online_report.type_id = onlinetest.register_id")->where($where)->select();
//        var_dump($show);die;
        $arr=array();
        foreach($show as $key=>$val)
        {
            $arr['weight']   = json_decode($val['weight'],true);
            $arr["chest"] = json_decode($val["chest"],true);
            $arr["gpbone"] = json_decode($val["gpbone"],true);
            $arr["chnbone"] = json_decode($val["chnbone"],true);
            $arr["ch05bone"] = json_decode($val["ch05bone"],true);
            $arr["tw3c"] = json_decode($val["tw3c"],true);
            $arr["tw3r"] = json_decode($val["tw3r"],true);
            $arr["height"] = json_decode($val["height"],true);
        }
//       var_dump($arr);die;
        $this->assign('data',$show[0]);
        $this->assign('arr',$arr);
        return $this->fetch('details');
    }
    //专家团队介绍
    public function teamShow(){
        return $this->fetch('teamShow');
    }
    //骨龄列表展示
    public function bone(){
        return $this->fetch("boneAge");
    }
    //首页
    public function index()
    {
        $arr=Db::table('introduce')->field('id,img_path,title')->select();
        $cat=Db::table('article')->join("category_article","category_article.c_id = article.c_id")->order('article_id desc')->limit(10)->select();
        $banner=Db::table('banner')->field('b_id,banner_path')->select();
        $this->assign('arr',$arr);
        $this->assign('cat',$cat);
        $this->assign('banner',$banner);
        return $this->fetch('index');
    }

    public function indexshow(Request $request){
         $id=Request::instance()->get('id');
         $where=array("id"=>$id);
         $show=Db::table('introduce')->where($where)->field('content')->select();
         $this->assign('show',$show);
         return $this->fetch('whatBone');         
    }

    public function articlelist(Request $request){
         $c_id=Request::instance()->get('c_id');
         $where=array("c_id"=>$c_id);
         $show=Db::table('article')->where($where)->select();
         $this->assign('show',$show);
         return $this->fetch('articlelist'); 
         // print_r($show);       
    }

    public function bannerlist(){
         $id=Request::instance()->get('b_id');
         $where=array("b_id"=>$id);
         $show=Db::table('banner')->where($where)->field('contents')->select();
         $this->assign('show',$show);
         return $this->fetch('bannerlist');         
    }

    public function nowTest()
    {
        return view('nowTest');
    }
    public function onlineTest()
    {
        return view('onlineTest');
    }
    // public function sportAction(){
    //  return view('sportAction');
    // }
    public function nutrientBalance(){
    	return view('nutrientBalance');
    }
    public function predictedHeight(){
    	//暂无页面，临时跳转到index
    	return view('index');
    }
    public function userCenter(){
        $id=Cookie::get('uid');
        if(empty($id)){
            $this->assign('name','false');
            $this->assign('out','false');//这里为前台是否显示退出标示
        }else{
            $user=Db::table('gl_users')->where('id',$id)->find();
            Cookie::set('username', $user['name']);
            $this->assign('name',$user['name']);
            $this->assign('out','true');//这里为前台是否显示退出标示
        }
        return view('userCenter');
    }
    
/**
     * 什么是骨龄测试
     */
    public function whatBone()
    {
       return view('whatBone'); 
    }
    /**
     * 什么年龄做骨龄测试
     */
    public function whatAge()
    {
       return view('whatAge'); 
    }/**
     * 为什么做骨龄测试
     */
    public function whyBone()
    {
       return view('whyBone'); 
    }/**
     * 怎么做骨龄测试
     */
    public function howBone()
    {
       return view('howBone'); 
    }

}
