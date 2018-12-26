<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Route::get('/', function () {
//    return view('welcome');
//});

// 后台首页
// Route::get('/','IndexController@index'); 
Route::get('/','Admin\LoginsController@index');
Route::get('/admin','Admin\LoginsController@index');
// 商家端
Route::get('/business', 'Business\LoginController@index')->middleware('business.login');
// 登录页面
Route::get('/login','Auth\LoginController@login');
// 验证登录
Route::post('/checkLogin','Auth\LoginController@checkLogin');
// 退出登录
Route::get('/loginOut','Auth\LoginController@loginOut');



// 商家端
Route::group(['namespace' => 'Business', 'prefix' => 'business', 'middleware' => 'business.auth'], function () {
    // 首页
    Route::prefix('index')->group(function () {
        Route::get('index', 'IndexController@index');
        Route::get('main', 'IndexController@main');
        Route::post('orderCharts', 'IndexController@orderCharts');              // 订单量
        Route::post('priceCharts', 'IndexController@priceCharts');              // 销售流水
    });
    // 首页
    Route::prefix('order')->group(function (){
        Route::any('index', 'OrderController@index');
        Route::get('orderInfo/{id}', 'OrderController@orderInfo');
        Route::get('export/{where}', 'OrderController@export');
    });

});


// 商家端登录页面

Route::group(['prefix' => 'business', 'namespace' => 'Business'], function () {
    Route::group(['prefix' => 'login'], function () {
        // 登录页面
        Route::get('index', 'LoginController@index')->middleware('business.login');
        // 登录方法
        Route::post('doLogin', 'LoginController@doLogin');
        // 退出
        Route::get('logout', 'LoginController@logout');
    });
});


// Api接口
Route::prefix('api')->namespace('Api')->group(function (){

    // 首页
    Route::prefix('Index')->group(function () {
        Route::post('index', 'IndexController@index');
        Route::post('category', 'IndexController@category');
        Route::post('lock', 'IndexController@lock');    // 开锁接口
    });

    Route::prefix('Lock')->group(function () {
        Route::get('token', 'LockController@token');
    });
    //消息token
    Route::prefix('Token')->group(function () {
        Route::get('message', 'TokenController@message');
    });
    //轮播图
    Route::prefix('Img')->group(function () {
        Route::post('img', 'ImgController@index');//轮播图接口
    });
    // 购物车
    Route::prefix('Shop')->group(function () {
        Route::post('index', 'ShopController@index');           // 购物车列表
        Route::post('create', 'ShopController@create');         // 购物车商品添加
        Route::post('clean', 'ShopController@clean');           // 购物车清空
        Route::post('delete', 'ShopController@delete');         // 购物车商品删除

    });

    // 微信支付
    Route::prefix('Wxpay')->group(function () {
        Route::any('orderPay', 'WxpayController@orderPay');                 // 微信支付
        Route::any('notify', 'WxpayController@notify');                     // 回调地址
        Route::post('isPaySuccess', 'WxpayController@isPaySuccess');      // 判断是否支付成功
    });

    // 商品控制器
    Route::prefix('Goods')->group(function () {
        Route::post('goodsDetails', 'GoodsController@goodsDetails');     // 商品详情接口
    });

    // 用户个人中心
    Route::prefix('User')->group(function () {
        Route::post('index', 'UserController@index');                                 // 个人信息接口
        Route::post('telAbout', 'UserController@telAbout');                // 联系客服和关于梦马接口
    });

    // 优惠卷管理
    Route::prefix('Coupon')->group(function () {
        Route::post('show', 'CouponController@show');
        Route::post('index', 'CouponController@index');
        Route::post('updateCoupon', 'CouponController@updateCoupon');
        Route::get('upCoupon', 'CouponController@UpCoupon');
        Route::post('add', 'CouponController@addCoupon');
    });

    // 登录
    Route::prefix('Login')->group(function () {
        Route::get('index/{user_id}', 'LoginController@index');          //  登录
        Route::post('code', 'LoginController@code');                     //  获取验证码
        Route::post('isCode', 'LoginController@isCode');                //  判断验证码登录
        Route::any('wxLogin', 'LoginController@wxLogin');               // 微信授权登录
        Route::any('token', 'LoginController@token');               // asscess_token
    });

    // 订单
    Route::prefix('Order')->group(function () {
        Route::post('index', 'OrderController@index');                                         // 订单列表
        Route::post('orderDetails', 'OrderController@orderDetails');                        // 订单详情
        Route::post('delOrder', 'OrderController@delOrder');                                 //删除订单
        Route::post('creatOrder', 'OrderController@creatOrder');                            // 生成订单
        Route::post('recoveryOrder', 'OrderController@recoveryOrder'); 
        Route::post('confirmOrder', 'OrderController@confirmOrder');                        // 确认订单
        Route::get('orderTask', 'OrderController@orderTask');                              // 定时任务 1天取消订单
    });

});

//后台
Route::prefix('admin')->namespace('Admin')->group(function (){
    //管理资
      Route::prefix('rbac')->group(function () {
        Route::get('index', 'RbacController@index');
        Route::get('roleadd', 'RbacController@roleadd');
        Route::post('authspost', 'RbacController@authspost');
        Route::post('addpost', 'RbacController@addpost');
        Route::post('editpost', 'RbacController@editpost');
        Route::get('delete/{id}', 'RbacController@delete');
        Route::get('upload', 'RbacController@upload');
        Route::get('edit/{id}', 'RbacController@edit');
        Route::get('auths/{id}', 'RbacController@auths');

    });
       //管理员
      Route::prefix('user')->group(function () {
        Route::get('index', 'UserController@index');
        Route::get('add', 'UserController@add');
        Route::post('store', 'UserController@store');
        Route::post('addpost', 'UserController@addpost');
        Route::post('editpost', 'UserController@editpost');
        Route::get('delete/{id}', 'UserController@delete');
        Route::get('edit/{id}', 'UserController@edit');
        Route::get('black/{id}', 'UserController@black');

    });

    // 商品控制器
    Route::prefix('goods')->group(function () {
        Route::get('add', 'GoodsController@add');                           // 添加商品页面
        Route::post('addGoods', 'GoodsController@addGoods');               // 添加商品
        Route::get('goodsList', 'GoodsController@goodsList');             // 商品列表
        Route::post('addInventory', 'GoodsController@addInventory');     // 添加商品库存
        Route::post('upGoods', 'GoodsController@upGoods');                // 上架下架
        Route::get('details/{id}', 'GoodsController@details');           // 查看详情
        Route::post('del', 'GoodsController@del');                        // 删除
        Route::get('edit/{id}', 'GoodsController@edit');                // 编辑
        Route::post('editPost', 'GoodsController@editPost');           // 编辑提交
        Route::post('imgUpload', 'GoodsController@imgUpload');        // 图片上传
        Route::post('addLoss', 'GoodsController@addLoss');            // 添加损耗
        Route::post('delImg', 'GoodsController@delImg');              // 删除图片
        Route::post('isBarcode', 'GoodsController@isBarcode');       // 判断条形码是否是唯一的
        Route::post('upload', 'GoodsController@upload');                //图片上传
        Route::get('damagelist', 'GoodsController@damagelist');
        Route::get('Goodsxsls/{keyword}', 'GoodsController@Goodsxsls');
    });


    // 分类管理
    Route::prefix('classify')->group(function () {
        Route::get('index', 'ClassifyController@index');
        Route::get('add', 'ClassifyController@add');
        Route::post('addpost', 'ClassifyController@addpost');
        Route::get('deletes/{id}', 'ClassifyController@deletes');
        Route::get('edit/{id}', 'ClassifyController@edit');
        Route::get('classifyGoods/{id}', 'ClassifyController@classifyGoods');           // 分类商品列表
        Route::get('remove/{id}', 'ClassifyController@remove');                          // 移除分类下的该商品
        Route::post('checkGoods', 'ClassifyController@checkGoods');                     // 判断是否有该商品
        Route::post('updateGoodsCate', 'ClassifyController@updateGoodsCate');         //  修改商品分类
        Route::post('editpost', 'ClassifyController@editpost');
    });


        // 用戶管理
      Route::prefix('member')->group(function () {
        Route::any('index', 'MemberController@index');
    });

    //订单管理
    Route::prefix('order')->group(function () {
        Route::any('index', 'OrderController@index'); //订单列表
        Route::post('selectRoom', 'OrderController@selectRoom');  //二级联动房间列表
        Route::get('orderInfo/{id}', 'OrderController@orderInfo');  //订单详情
        Route::get('export/{data}', 'OrderController@export');  //订单导出
        // 下载
        Route::get('downloadfile/{file}', 'OrderController@DownloadFile')->name('download');
    });

    //货柜（体验店）管理
    Route::prefix('container')->group(function () {
        Route::any('index', 'ContainerController@index'); //体验店列表
        Route::post('selectErr', 'ContainerController@selectErr'); //报修列表

        Route::any('add', 'ContainerController@add'); //体验店添加
        Route::post('checkNumber', 'ContainerController@checkNumber'); //体验店添加->体验店编号检查
        Route::post('checkGoods', 'ContainerController@checkGoods'); //体验店添加-》添加商品检查
        Route::any('edit', 'ContainerController@edit'); //体验店编辑
        Route::post('updateStatus', 'ContainerController@updateStatus'); //体验店状态修改
        Route::get('info/{id}/{error_num}/{hotel}/{room}', 'ContainerController@info');  //体验店详情
        Route::get('excel', 'ContainerController@excel');
    });

    Route::prefix('ueditor')->group(function () {
        Route::get('index', 'UeditorController@index');
        Route::get('create', 'ArticleController@create');
        Route::post('store', 'ArticleController@store');
        Route::get('upload', 'UeditorController@upload');
        Route::get('edit/{id}/{name}', 'ArticleController@edit');

    });
    //后台登录
    Route::prefix('logins')->group(function () {
        Route::get('index', 'LoginsController@index');
        Route::post('create', 'LoginsController@create');
        Route::post('doLogin', 'LoginsController@doLogin');
        Route::get('logout', 'LoginsController@logout');
        Route::get('edit/{id}/{name}', 'LoginsController@edit');

    });
    Route::prefix('menu')->group(function () {
        Route::get('index', 'MenuController@index');
        Route::get('add/{mid}/{type}', 'MenuController@add');
        Route::get('edit/{mid}/{type}', 'MenuController@edit');
        Route::get('delete/{mid}/{type}', 'MenuController@delete');
        Route::get('lists', 'MenuController@lists');
        Route::post('doedit', 'MenuController@doedit');
        Route::post('addpost', 'MenuController@addpost');
        // Route::post('addpost', 'MenuController@addpost');
    });

    // 首页
    Route::prefix('index')->group(function () {
        Route::get('index', 'IndexController@index');
        Route::get('main', 'IndexController@main');
        Route::post('store', 'IndexController@store');
        Route::post('hotelOrderRank', 'IndexController@hotelOrderRank');   // 本月酒店订单量排行
        Route::post('hotelPriceRank', 'IndexController@hotelPriceRank');   // 本月酒店销售金额排行
        Route::post('orderRank', 'IndexController@orderRank');              // 订单量
        Route::post('priceRank', 'IndexController@priceRank');              // 销售流水
    });

    Route::prefix('hotel')->group(function () {
        Route::get('index', 'HotelController@index');
        Route::get('add', 'HotelController@add');
        Route::post('store', 'HotelController@store');
        Route::get('details/{id}', 'HotelController@details');
        Route::get('edit/{id}', 'HotelController@edit');
        Route::post('update', 'HotelController@update');
        Route::post('queryy', 'HotelController@queryy');
        Route::post('delmag', 'HotelController@delmag');//删除经理账号
    });
    //房间管理
    Route::prefix('room')->group(function () {
        Route::get('index', 'RoomController@index');
        Route::get('add', 'RoomController@add');
        Route::post('store', 'RoomController@store');
        Route::post('queryy', 'RoomController@queryy');
        Route::get('show/{id}', 'RoomController@show');
        Route::get('edit/{id}', 'RoomController@edit');
        Route::post('update/{id}', 'RoomController@update');
        Route::get('plugins', 'RoomController@plugins');
        Route::get('addGoods/{id}', 'RoomController@addGoods');
        Route::post('addgood', 'RoomController@addgood');
        Route::post('addallgood', 'RoomController@addallgood');
        Route::post('damageGoods', 'RoomController@damageGoods');
    });

    Route::prefix('excel')->group(function () {
        Route::get('export', 'ExcelController@export');
        Route::post('import', 'ExcelController@import');
        Route::get('excel', 'ExcelController@lockExcel');
        Route::post('more', 'ExcelController@MoreContainer');
    });

    // 测试硬件
    Route::prefix('ceshi')->group(function () {
        Route::get('index', 'CeshiController@index');
        Route::post('addPost', 'CeshiController@addPost');
        Route::get('lists', 'CeshiController@lists');
        Route::get('del/{id}', 'CeshiController@del');
        Route::get('opend/{id}', 'CeshiController@opend');
        Route::get('lockexcel', 'CeshiController@lockExcel');
        Route::get('close/{id}', 'CeshiController@close');
	    Route::get('restore/{id}', 'CeshiController@restore');
	    Route::get('closeLock', 'CeshiController@closeLock');
        Route::get('export/{data}', 'CeshiController@export');
    
});
    //轮播图管理
    Route::prefix('sowingmap')->group(function () {
        Route::get('Imglist', 'SowingmapController@imglist');
        Route::any('ImgAdd', 'SowingmapController@add');
        Route::get('details/{id}', 'SowingmapController@detail');
        Route::get('edit/{id}', 'SowingmapController@edit');
        Route::get('del', 'SowingmapController@del');
        Route::post('up', 'SowingmapController@up');
    });
    //开锁
    Route::prefix('lock')->group(function () {
        Route::get('list', 'LockController@LockList');
        Route::get('serach', 'LockController@serach');
        Route::get('details/{id}', 'LockController@details');
        Route::get('repair', 'LockController@repair');
    });

    Route::prefix('coupon')->group(function () {
        Route::get('list', 'CouponController@coupon_list');
        Route::any('add', 'CouponController@add');
        Route::get('details/{id}', 'CouponController@details');
        Route::get('del', 'CouponController@del');
        Route::get('edit/{id}', 'CouponController@edit');
        Route::post('editAdd', 'CouponController@editAdd');
        Route::get('checkGoods', 'CouponController@checkGoods');
    });
});

// Home 模块  前台
Route::prefix('home')->namespace('Home')->group(function () {
    Route::prefix('index')->group(function () {
        Route::get('index', 'IndexController@index');
        Route::get('create', 'IndexController@create');
        Route::post('store', 'IndexController@store');
    });

});
