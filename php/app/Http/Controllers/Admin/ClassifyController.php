<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Goods;
use App\Models\Common;

class ClassifyController extends CommonController
{
    /**
     *  lj
     *  商品列表
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index(Category $category) {
        try {
            $res = $category->datasPage();
            $count = $category->count(array('pid'=>0));
            return view('admin.classify.index',array('res'=>$res,'count'=>$count));
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
    }
    /**  
    *Ìí¼ÓÒ³Ãæ
	*@param  
	*@return
    */
    public function add() {
	    try {
			return view('admin.classify.add');
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
    }
    /**  
    *±à¼­Ò³Ãæ
	*@param  
	*@return
    */
    public function edit($id,Category $category) {
	    try {
	    	$where['c.id'] = $id;
	    	$res = $category->datasFind($where);
			return view('admin.classify.edit',array('res'=>$res));
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
    }
     /**  
    *´¦Àí±à¼­Ò³Ãæ
	*@param  
	*@return
    */
    public function editpost(Common $common,Request $Request,Category $category) {
	    try {
	    	$datas = $Request->all();
            if($datas['img'] == 'a') {
                // 查看数据库
                $findImg = $common->datasFind(
                    'category',
                    array(
                        'id' => $datas['c_id']
                    ),
                    'img'
                );
                $mainImg = $findImg->img;
            }else {
                $main = imgbase($datas['img']);
                $mainImg = $main['msg'];
            }

	    	$arr = array(
	    			'name'=>trim($datas['name']),
	    			'English_name'=>trim($datas['English_name']),
	    			'img'=>$mainImg,
	    			'sorts'=>trim($datas['num']),
	    			'admin_id'=>session('ADMIN_ID'),
	    		);
			$ret = $category->updatas(['id'=>$datas['c_id']],$arr);
        	if($ret){
                $return['code'] = 1;
                $return['msg'] = '修改成功';
                return json_encode($return);
        	}else{
                $return['code'] = 2;
                $return['msg'] = '修改失败';
                return json_encode($return);
        	}
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
    }
    /**  
    *´¦ÀíÌí¼ÓÒ³Ãæ
	*@param  
	*@return
    */
    public function addpost(Request $Request,Category $category) {
	    try {
	    	$datas = $Request->all();
            $img = $Request->file('img');
            // 查找分类名称是否重复
            $cateName = object_array($category->whereData(
                array(
                    'name'=>$datas['name']
                ),
                'name'
            )
                ->toArray());
            if(!empty($cateName)) {
                $str = '请勿填写相同分类的名称';
                $encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
                $str_encode = mb_convert_encoding($str, 'UTF-8', $encode);
                return error($str_encode,'admin/classify/add');
            }
            if($img){
                $path = '/uploads/'.date('Y-m-d');   //保存路径
                $url = upload($img,$path);  //调用上传类方法  获取到地址
            }else{
                $url = '';
            }
	    	$arr = array(
	    			'name'=>trim($datas['name']),
	    			'English_name'=>trim($datas['English_name']),
	    			'sorts'=>trim($datas['num']),
	    			'img'=>$url,
	    			'admin_id'=>session('ADMIN_ID'),
	    			'add_time'=>time(),
	    		);
			$ret = $category->add($arr);
        	if($ret){
        		$str = '添加成功';
        		$encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        		$str_encode = mb_convert_encoding($str, 'UTF-8', $encode);
        		return success($str_encode,'admin/classify/index');
        	}else{
        		$str = '添加失败';
        		$encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        		$str_encode = mb_convert_encoding($str, 'UTF-8', $encode);
        		return error($str_encode,'admin/classify/add');
        	}
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
    }

    /**
     * lj
     *  分类删除
     * @param $id
     * @param Category $category
     * @param Goods $goods
     * @return $this
     */
    public function deletes($id,Category $category,Goods $goods) {
    	try {
	    	$id = intval($id);
	    	$where['id'] = $id;
            if($id){    //查看改分类下是否有商品
                $gRes = $goods->datasFind(['cat_id'=>$id]);
                if($gRes){
                    return error('该分类下有商品不能删除！','admin/classify/index');
                }
            }
			$ret = $category->deletes($where);
        	if($ret){
        		$str = '删除成功';
        		$encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        		$str_encode = mb_convert_encoding($str, 'UTF-8', $encode);
        		return success($str_encode,'admin/classify/index');
        	}else{
        		$str = '删除失败';
        		$encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
        		$str_encode = mb_convert_encoding($str, 'UTF-8', $encode);
        		return error($str_encode,'admin/classify/index');
        	}
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
    }


    /**
     * lj
     *  分类商品列表
     * @param Category $category
     */
    public function classifyGoods($id,Common $common,Category $category) {
        try {
            // 查找分类名称
            $cateName = $common->datasFind(
                'category',
                array(
                    'id' => $id
                ),
                array(
                    'name',
                    'id'
                )
            );

            // 查找分类下的商品列表
            $where['cat_id'] = $id;
            $res = $category->findCateGoods($where);
            return view('admin.classify.categoods',array('res'=>$res,'cate_name'=>$cateName));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     * lj
     *  移除分类下的该商品
     * @param $id
     * @param Category $category
     * @param Goods $goods
     * @return $this
     */
    public function remove($id,Common $common) {
        try {
            $id = intval($id);
            if($id){
                // 查看分类id
                $cateId = $common->datasFind(
                    'goods',
                    array(
                        'goods_id' => $id,
                    ),
                    'cat_id'
                );
                  $res = $common->UpdateCommon(
                      'goods',
                      array(
                          'goods_id' => $id
                      ),
                      array(
                          'cat_id' => 0
                      )
                  );
                if($res){
                    $str = '移除成功';
                    $encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
                    $str_encode = mb_convert_encoding($str, 'UTF-8', $encode);
                    return success($str_encode,'admin/classify/classifyGoods'.'/'.$cateId->cat_id);
                }else{
                    $str = '移除失败';
                    $encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
                    $str_encode = mb_convert_encoding($str, 'UTF-8', $encode);
                    return error($str_encode,'admin/classify/classifyGoods'.'/'.$cateId->cat_id);
                }
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * lj
     * 查询商品条形码是否存在
     * @param Request $Request
     * @param Container $container
     * @return string
     */
    public function checkGoods(Request $Request,Category $category){
        $data = $Request->all();
        $where= explode('/',$data['num']);
        //查询商品
        $list = $category->datasIn($where);
        //组长数据
        if(empty($list)){
            $res['code'] = 1; //未找到数据
        }else{
            $test = 0;
            $msg ='';
            foreach ($where as $k =>$v ){
                if(empty($list[$v])){
                    $test = 1;
                    $msg .=$v.',';
                }
            }
            if($test == 1){
                $res['code'] = 2;  //部分数据不存在
                $res['msg'] = $msg;
            }else{
                $res['code'] = 3;  //数据存在
                $res['msg'] = $list;
            }
        }
        return json_encode($res);
    }

    /**
     * lj
     * 修改商品分类
     * @param Request $request
     */
    public function updateGoodsCate(Request $request,Category $category) {
        $token = $request -> except('_token');
        // 接受值
        $data = $request->all();
        $barcode= explode('/',$data['num']);
        $ids = implode(',',$barcode);

        // 先查询该商品是否在该分类下
        $findGoods = $category->findCate($barcode,$data['cate_id']);
        $test = 0;
        $msg ='';
        foreach ($barcode as $k =>$v ){
            if(!empty($findGoods[$v])){
                $test = 1;
                $msg .=$v.',';
            }
        }
        if($test == 1){
            $return['code'] = 3;
            $return['msg'] = $msg.'商品是在该分类下';
        }else {
            // 第一种方法
//        $res = DB::table('goods')
//            ->whereIn('barcode', $barcode)
//            ->update(['cat_id' => $data['cate_id']]);

            // 第二种方法
            // 拼接sql语句
            $updateSql = "UPDATE " . 'mm_goods' . " SET "."cat_id".'='.$data['cate_id']. " WHERE `" . 'barcode' . "` IN (" . $ids . ")";
            $res = DB::update($updateSql);

            if($res != 0) {
                $return['code'] = 1;
                $return['msg'] = '成功';
            }else {
                $return['code'] = 2;
                $return['msg'] = '失败';
            }
        }
        return json_encode($return);
    }



}
