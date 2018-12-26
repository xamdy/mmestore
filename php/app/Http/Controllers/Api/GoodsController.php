<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class GoodsController extends Controller
{
    /**
     *  // 商品详情接口
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function goodsDetails( Request $request) {
        // 接受数据
        $data = $request->all();
        // 判断参数是否为空
        if(empty($data['user_id']) || empty($data['c_id']) || empty($data['languagess'])) {
            return publicReturn(
                '2',
                '参数不能为空'
            );
        }

        // 根据用户id来查询出该用户是中文还是英文
        $isUser = object_array($this->common->datasFind(
            'users',
            array(
                'user_id' => $data['user_id']
            ),
            'languages'
        ));

        // 判断是否有条形码参数
        if($data['barcode'] != 'undefined') {
            // 根据商品条形码来查找该商品
            $goodsDetail = $this->common->datasFind(
                'goods',
                array(
                    'barcode' => $data['barcode']
                ),
                'goods_id'
            );
            if($goodsDetail) {

                $goods = $this->goods->findGoods(
                    array(
                        's.goods_id' => $goodsDetail->goods_id,
                        's.languages' => $data['languagess'],
                        'g.is_del' => 1,
                    ),
                    array(
                        'g.goods_id',
                        'g.original_price',
                        'g.present_price',
                        'g.main_img',
                        'g.shuffling_figure',
                        's.goods_name',
                        's.goods_introduction',
                        's.goods_description',
                    )
                );

                if(empty($goods)) {
                    return publicReturn(
                        '2',
                        '该商品已经下架'
                    );
                }
                if(substr($goods['shuffling_figure'],0,1)==',') {
                    $img = substr($goods['shuffling_figure'], 1);
                    $goods['shuffling_figure'] = explode(',', $img);
                }else{
                    $goods['shuffling_figure'] = explode(',',$goods['shuffling_figure']);
                }
                // 判断是否加过购物车
                $where = array(
                    'user_id' => $data['user_id'],
                    'c_id' => $data['c_id'],
                    'goods_id' => $goodsDetail->goods_id,
                );
                $isCar = $this->common->datasFind(
                    'shop_car',
                    $where,
                    'id'
                );
                if($isCar) {
                    $goods['status'] = 1;
                }else {
                    $goods['status'] = 2;
                }

                // 判断货柜里商品是否已售罄
                $where1 = array(
                    'c_id' => $data['c_id'],
                    'goods_id' => $goodsDetail->goods_id,
                    'inventory_status' => 1,
                );
                $isContainer = $this->common->datasFind(
                    'container_goods',
                    $where1,
                    'id'
                );
                if($isContainer) {
                    // 没售罄
                    $goods['inventory_status'] = 1;
                }else {
                    // 售罄
                    $goods['inventory_status'] = 2;
                }
                // 判断是否为空
                if(empty($goods)) {
                    return publicReturn(
                        '2',
                        '没有找到该商品'
                    );
                }
                return publicReturn(
                    '1',
                    '成功',
                    $goods
                );


            }else {
                return publicReturn(
                    '2',
                    '没有找到该商品'
                );
            }

        } else {
            // 判断商品id是否为空
            if(empty($data['goods_id'])) {
                return publicReturn(
                    '2',
                    '商品id不能为空'
                );
            }
            // 判断是中文还是英文
            // 查找商品详情的信息
            $goods = $this->goods->findGoods(
                array(
                    's.goods_id' => $data['goods_id'],
                    's.languages' => $data['languagess'],
                    'g.is_del' => 1,
                ),
                array(
                    'g.goods_id',
                    'g.original_price',
                    'g.present_price',
                    'g.main_img',
                    'g.shuffling_figure',
                    's.goods_name',
                    's.goods_introduction',
                    's.goods_description',
                )
            );
            if(empty($goods)) {
                return publicReturn(
                    '2',
                    '该商品已经下架'
                );
            }
            if(substr($goods['shuffling_figure'],0,1)==',') {
                $img = substr($goods['shuffling_figure'], 1);
                $goods['shuffling_figure'] = explode(',', $img);
            }else{
                $goods['shuffling_figure'] = explode(',',$goods['shuffling_figure']);
            }

            // 判断是否加过购物车
            $where = array(
                'user_id' => $data['user_id'],
                'c_id' => $data['c_id'],
                'goods_id' => $data['goods_id'],
            );
            $isCar = $this->common->datasFind(
                'shop_car',
                $where,
                'id'
            );
            if($isCar) {
                $goods['status'] = 1;
            }else {
                $goods['status'] = 2;
            }

            // 判断货柜里商品是否已售罄
            $where1 = array(
                'c_id' => $data['c_id'],
                'goods_id' => $data['goods_id'],
                'inventory_status' => 1,
            );
            $isContainer = $this->common->datasFind(
                'container_goods',
                $where1,
                'id'
            );
            if($isContainer) {
                // 没售罄
                $goods['inventory_status'] = 1;
            }else {
                // 售罄
                $goods['inventory_status'] = 2;
            }
            // 判断是否为空
            if(empty($goods)) {
                return publicReturn(
                    '2',
                    '没有找到该商品'
                );
            }
            return publicReturn(
                '1',
                '成功',
                $goods
            );
        }

    }

}
