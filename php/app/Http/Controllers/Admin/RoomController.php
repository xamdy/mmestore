<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Hotel;
use App\Models\Container;
use Illuminate\Support\Facades\DB;

class RoomController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,Room $Room)
    {   
        $where['id'] = $request->id;
        $where['room_name'] = $request->room_name;
        $roomlList = $Room->roomlList($where);
        $hotel = Hotel::select('id','name')->get();
        $assign = compact('roomlList','hotel'); 
        return view('admin.room.index',$assign);
    }

    /**
     * 添加房间页面
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Container $container)
    {
        $hotel = Hotel::select('id','name')->get();
        $con=$container->Unbound();
        return view('admin.room.add',['hotel'=>$hotel,'container'=>$con]);
        //        $assign = compact('hotel');
        //        $assign = compact('hotel');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function store(Request $request,Room $Room)
    {   
        $token = $request -> except('_token');
        // 接受值
        $data = $request->all();
//        var_dump($data);die;
        $result = $Room->addroom($data);

        if($result){
            return success("添加成功", 'admin/room/index');
        }else{
            return error('添加失败','admin/room/index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Room $Room)
    {   
        $result = $Room->findRoom($id);
        $assign = compact('result');
        return view('admin.room.show',$assign);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Room $Room)
    {
        $result = $Room->findRoom($id);
        $assign = compact('result');
        return view('admin.room.edit',$assign);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $arr = $request -> except('_token');
        // 开启事务
        $data['room_name'] = $arr['room_name'];
        DB::beginTransaction();
        $result = Room::where('id',$id)->update($data);
        if($request->container_id){
           $save['hotel_id'] = $arr['hotel_id'];
            $save['room_id'] = $id;
            $result1 = Container::where('container_number',$arr['container_id'])->update($save);
            if($result && $result1){
                DB::commit();
            return success("编辑成功", 'admin/room/index');
            }else{
                DB::rollback();
                return error('编辑失败','admin/room/index');
            }
        }
        if($result){
            DB::commit();
            return success("编辑成功", 'admin/room/index');
        }else{
            DB::rollback();
            return error('编辑失败','admin/room/index');
        }
        

        

    }

    /**
     * 补货页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addGoods($id,Room $room)
    {   
        $result = $room->selectGlist($id);
        $assign = compact('result','id');
        return view('admin/room/addGoods',$assign);
    }
    /**
     * 单品补货
     * @author j
     * @DateTime 2018-07-24T11:37:21+0800
     * @param    request                  $request [description]
     * @param    Room                     $room    [description]
     * @return   [type]                            [description]
     */
    public function addgood(request $request,Room $room)
    {
        $data = $request -> except('_token');
        $result = $room->addgood($data['id'],$data['goods_id']);       
        return json_encode($result);
        
    }
    /**
     * 批量添加库存
     * @author j
     * @DateTime 2018-07-26T16:07:42+0800
     * @param    request                  $request [description]
     * @param    Room                     $room    [description]
     * @return   [type]                            [description]
     */
    public function addallgood(request $request,Room $room)
    {
        $data = $request -> except('_token');
        $result = $room->addallgood($data['id']);
        
        return json_encode($result);
        
    }

    public function damageGoods(request $request,Room $room)
    {
        $data = $request -> except('_token');
        $result=$room->CargoDamage($data);
        return json_encode($result);
    }
    /**
     * ajax 查询体验店是否绑定
     * @author j
     * @DateTime 2018-07-18T17:58:25+0800
     * @param    Request                  $request [description]
     * @return   [type]                            [description]
     */
    public function queryy(Request $request)
    {
        $token = $request -> except('_token');
        // 接受值
        $data = $request->all();
        $container = Container::where(['is_del'=>1,'status'=>1,'room_id'=>0,'container_number'=>$data['container_id']])->value('id');
        if($container){
           return json_encode(true);
        }else{
           return json_encode(false);
        }
    }
    /**
     * 导入房间excel页面
     * @author j
     * @DateTime 2018-07-24T11:00:18+0800
     * @return   [type]                   [description]
     */
    public function plugins()
    {
        return view('admin.room.plugins');
    }
}
