<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CheckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 考勤设置添加
     */
    public function create(Request $request)
    {
//        $token = $request->except('_token');
        $result = $request->all();
        if(empty($result['name']) || empty($result['oid']) || empty($result['uid']) || empty($result['check_time']) || empty($result['time_frame']) || empty($result['time_frames']) || empty($result['wifi_name']) || empty($result['wifi_ip']) || empty($result['address'])){
            $data['code'] = 1;
            $data['msg'] = '数据不能为空';
            echo json_encode($data);exit();
        }
        $check_time = $result['check_time'];
        $time = explode('—',$check_time);
        $start_time = intval($time[0]);
        $end_time = intval($time[1]);
        if($end_time < $start_time){
            $data['code'] = 2;
            $data['msg'] = '结束时间不能小于开始时间';
            echo json_encode($data);exit();
        }
        $insert_id = DB::table('check_set')
            ->insertGetId( $result );
        if($insert_id){
            $data['code'] = 1;
            $data['msg'] = '成功';
            echo json_encode($data);exit();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * 考勤设置展示
     */
    public function index(Request $request)
    {
        //
        $id = $request->input('cid'); //用户id
        $result = DB::table('check_set as c')
            ->select('c.name','c.address','c.check_time')
            -> where( 'c.cid','=',$id )
            // -> orderBy('c.sort','desc')
            -> get();
//        p($result);die;
        return view('home.check.show',['result'=>$result]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *修改信息
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        //
        $result = DB::table('check_set')
            -> where( 'id','=',$id )
            -> get();
        p($result);die;
//        return view("home.check.update",['result'=>$result,'id'=>$id]);
    }

    /**
     * Update the specified resource in storage.
     *修改信息
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $result = $request->all();
        $res = DB::table('check_set')
            -> where('id',$result['cid'])
            -> update( $result );
        if($res) {
            echo '成功';
        }else {
            echo '失败';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * 删除信息
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id'); //要删除的id
        //查询是否有这条数据
        $result = DB::table('check_set')
            -> where( 'id','=',$id )
            -> get();
        if($result){
            $data = DB::table('check_set')->delete($id);
            if($data){
                echo '成功';
            }else{
                echo '失败';
            }
        }else{
            echo '没有这条数据';
        }
    }
}
