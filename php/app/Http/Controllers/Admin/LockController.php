<?php
namespace App\Http\Controllers\Admin;
use App\Models\AdminMenu;
use Illuminate\Http\Request;
use App\Models\LockLog;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LockController extends CommonController
{
    public $lock, $hotel;

    public function __construct()
    {
        $this->lock = new LockLog();
        $this->hotel = new Hotel();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 开锁数据列表
     */
    public function LockList(Request $request)
    {
        $con_id = $request->get('container_id');
        $room_id = $request->get('room_id');
        $hotel_id = $request->get('hotel_id');
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        $data = $this->lock->DataMore($room_id, $con_id, $hotel_id, $start_time, $end_time);
        $hotel = $this->lock->table('hotel', array('id', 'name'));
        $con = $this->lock->table('container', array('id', 'container_number'));
        $room = $this->lock->table('room', array('id', 'room_number'));
        if (isset($con_id)) {
            $data->container_id = $con_id;
        } else {
            $data->container_id = '';
        }
        if (isset($room_id)) {
            $data->room_id = $room_id;
        } else {
            $data->room_id = '';
        }
        if (isset($hotel_id)) {
            $data->hotel_id = $hotel_id;
        } else {
            $data->hotel_id = '';
        }
        if (isset($start_time)) {
            $data->start_time = $start_time;
        } else {
            $data->start_time = '';
        }
        if (isset($end_time)) {
            $data->end_time = $end_time;
        } else {
            $data->end_time = '';
        }
        return view('admin.lock.list', array('data' => $data, 'hotel' => $hotel, 'con' => $con, 'room' => $room));
    }

    /**
     * @param Request $request
     * @return string
     * 查找体验店所对应的房间酒店编号
     */
    public function serach(Request $request)
    {
        $id = $request->get('id');
        $hotel_room = $this->lock->findWhere('container', array(
            'room_id', 'hotel_id'), array('id' => $id));
        $data['room'] = $this->lock->findWhere('room', array('id', 'room_number'), array('id' => $hotel_room->room_id));
        $data['hotel'] = $this->lock->findWhere('hotel', array('id', 'name'), array('id' => $hotel_room->hotel_id));
        return json_encode($data);
    }

    /**
     * @param $id
     * 开锁数据详情
     */
    public function details($id){
        $data=$this->lock->findDetail($id);
        return view('admin.lock.detail',array('data'=>$data));
    }


    public function repair(){
        $data=$this->lock->LockError();
        var_dump($data);

    }

}