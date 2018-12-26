<?php

namespace App\Http\Controllers\Api;

use App\Models\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ShopController extends Controller
{
    /**
     * 购物车列表
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 接受数据
        $post = $request->all();
        if($post['user_id']&&$post['c_id']){ 
            //根据user_id查询语种
            $user = new Common();
            $where_user['user_id'] = $post['user_id'];
            $languages = $request->languagess ? $request->languagess : 1;
            $filed = 'a.id,a.goods_id,c.goods_name,b.original_price,b.present_price';

            $cart = DB::select('select '.$filed.'  
                                from mm_shop_car as a
                                left join mm_goods as b on a.goods_id = b.goods_id 
                                left join mm_goods_side as c on b.goods_id = c.goods_id 
                                where (a.user_id = '.$post['user_id'].' and a.c_id = '.$post['c_id'].' and b.status = 1 and b.is_del = 1 and c.languages = '.$languages.')');

            if($cart){
                return publicReturn('1','查询成功',$cart);
            }else{
                return publicReturn('3','暂无数据','');
            }
        }else{
            return publicReturn('2','缺少参数','');
        }

      //  dump($cart);

    }

    /**
     * 添加购物车
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // 接受数据
        $post = $request->all();
        if($post['user_id']&&$post['c_id']&&$post['goods_id']){

            $data['user_id'] = $post['user_id'];
            $data['c_id'] = $post['c_id']; //货柜ID
            $data['goods_id'] = $post['goods_id'];
            $data['creat_time'] = time();

            // 判断购物车该用户是否已经添加过该商品
            $where = array(
                'user_id' => $post['user_id'],
                'c_id' => $post['c_id'],
                'goods_id' => $post['goods_id'],
            );
            $isGoods = $this->common->datasFind(
                'shop_car',
                $where,
                'id'
            );
            if($isGoods) {

                return publicReturn('3','已在购物车','');

            }else {

                //插入数据库
                $res =  DB::table('shop_car')->insertGetId($data);
                if($res){
                    return publicReturn('1','添加成功','');
                }else{
                    return publicReturn('2','服务器异常','');
                }
            }

        }else{
            return publicReturn('2','缺少参数','');
        }
    }

    /**
     * 清空购物车
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clean(Request $request)
    {
        // 接受数据
        $post = $request->all();
        if($post['user_id']&&$post['c_id']){
            $where['user_id'] = $post['user_id'];
            $where['c_id'] = $post['c_id'];
            $res =  DB::table('shop_car')->where($where)->delete();
            if($res){
                return publicReturn('1','成功','');
            }else{
                return publicReturn('2','服务器异常','');
            }
        }else{
            return publicReturn('2','缺少参数','');
        }

    }

    /**
     * 删除购物车商品
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // 接受数据
        $c_id = $request->c_id;
        $user_id = $request->user_id;
        $goods_id = $request->goods_id;

        if($c_id && $user_id && $goods_id){
            $where['c_id'] = $c_id;
            $where['user_id'] = $user_id;
            $where['goods_id'] = $goods_id;
            $res =  DB::table('shop_car')->where($where)->delete();
            if($res == 1){
                return publicReturn('1','删除成功','');
            }else{
                return publicReturn('2','删除失败','');
            }

        }else{
            return publicReturn('2','缺少参数','');
        }
    }
}
