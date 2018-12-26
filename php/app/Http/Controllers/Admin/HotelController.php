<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Hotel;
use Illuminate\Support\Facades\Crypt;
class HotelController extends CommonController
{
    /**
     * 酒店列表
     * @author j
     * @DateTime 2018-07-13T10:15:09+0800
     * @param    Hotel                    $Hotel [description]
     * @return   [type]                          [description]
     */
    public function index(Request $request, Hotel $Hotel)
    {	
    	$where['id'] = $request->id;
    	$where['tel'] = $request->tel;
    	$where['jltel'] = $request->jltel;
    	$where['orderby'] = $request->orderby;
    	$hotelList = $Hotel->hotelList($where);
    	$assign = compact('hotelList');
    	return view('admin.hotel.index', $assign);
    }
    /**
     * 添加酒店页面
     * @author j
     * @DateTime 2018-07-13T10:14:30+0800
     */
    public function add()
    {
    	return view('admin.hotel.add');
    }
    /**
     * 添加酒店
     * @author j
     * @DateTime 2018-07-13T10:14:51+0800
     * @param    Request                  $request [description]
     * @param    Hotel                    $hotel   [description]
     * @return   [type]                            [description]
     */
    public function store(Request $request,Hotel $hotel)
	{
	    $data = $request->except('_token');
        $result = $hotel->add($data);
	  	if($result){
	  		return success("添加成功", 'admin/hotel/index');
	  	}else{
	  		return error('添加失败','admin/hotel/index');
	  	}
	}
	public function queryy()
	{
		return json_encode(true);
	}
	/**
	 * 酒店详情
	 * @author j
	 * @DateTime 2018-07-13T15:45:30+0800
	 * @param    [type]                   $id    [description]
	 * @param    Hotel                    $hotel [description]
	 * @return   [type]                          [description]
	 */
	public function details($id,Hotel $hotel)
	{
		$result = $hotel->findHotel($id);
		$assign = compact('result');
		return view('admin.hotel.details',$assign);
	}
	/**
	 * 酒店编辑页
	 * @author j
	 * @DateTime 2018-07-13T15:58:07+0800
	 * @param    [type]                   $id    [description]
	 * @param    Hotel                    $hotel [description]
	 * @return   [type]                          [description]
	 */
	public function edit($id,Hotel $hotel)
	{	
		$result = $hotel->findHotel($id);
		$assign = compact('result');
		return view('admin.hotel.edit',$assign);
	}
	/**
	 * 酒店编辑
	 * @author j
	 * @DateTime 2018-08-03T16:37:54+0800
	 * @param    Request                  $request    [description]
	 * @param    Hotel                    $hotelModel [description]
	 * @return   [type]                               [description]
	 */
	public function update(Request $request,Hotel $hotelModel)
	{
		$request->except('_token');

		$result = $hotelModel->updates($request);
		if($result){
			return success("编辑成功", 'admin/hotel/index');
		}else{
			return error('添加失败','admin/hotel/index');

		}
	}
	/**
	 * ajax删除酒店经理账号方法
	 * @author j
	 * @DateTime 2018-08-03T16:25:20+0800
	 * @param    Request                  $request [description]
	 * @return   [type]                            [description]
	 */
	public function delmag(Request $request,Hotel $hotelModel)
	{	
		$id = $request->id;
		$result = $hotelModel->delmag($id);
		return json_encode($result);
	}
}
