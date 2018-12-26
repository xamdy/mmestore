<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Article;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
	
    //Controller的构造方法
    public function __construct()
    {
        //调用中间件
//      $this->middleware('test');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {  
        echo 22;die;
//        $userinfo =   Article::find(40);
//        Redis::set('user_key',$userinfo);
//        if(Redis::exists('user_key')){
//            $values = Redis::get('user_key');
//        }else{
//            $values = Article::find(50);//此处为了测试你可以将id=1200改为另一个id
//        }
//        p($values);

        //laravel框架使用redis
//        Redis::set('username', 'lijing');
//        echo Redis::get('username');

        //一般情况下使用redis
//        Redis::set('username','lijing');
//        $result = Redis::get('username');
//        p($result);die;

        //冒泡排序
//        $b=array('4','3','8','9','2','1','0');
//        $len=count($b);
//        for($k=0;$k<=$len;$k++)
//        {
//            for($j=$len-1;$j>$k;$j--){
//                if($b[$j]<$b[$j-1]){
//                    $temp = $b[$j];
//                    $b[$j] = $b[$j-1];
//                    $b[$j-1] = $temp;
//                }
//            }
//        }
//        dump($b);die;
//        p($b);die;
//        $log_info = DB::table('log as l')
//            ->select('l.*', 'a.name')
//            ->leftJoin('admin as a', 'a.id', '=', 'l.admin_id')
//            ->orderBy('l.time', 'desc')
//            // 暂取10条
//            ->take(10)
//            ->get()
//            ->toArray();
//        dump($log_info);die;
//        $log_info = DB::table('vehicle')->get()->toArray();
//        p($log_info);
//      return view("home.index.index");
//        $model=new Article(); //实例化model
//        $data = $model -> select(); //调用model层中方法
//        p($data);die;
//        $data = Article::first();

        // $log_info = DB::table('log as l')
        //     ->select('l.*', 'a.name')
        //     ->leftJoin('admin as a', 'a.id', '=', 'l.admin_id')
        //     ->orderBy('l.time', 'desc')
        //     // 暂取10条
        //     ->take(10)
        //     ->get()
        //     ->toArray();
        // return view("home.index.index", ['log_info' => $log_info]);
       $model= new Article(); //实例化model
       $data = $model -> select(); //调用model层中方法
//        p($data);die;
//        var_dump($data);die;

       // $data = Article::first();
       // p($data);die;
        //
//        $result = DB::table('vehicle')->first();
        // p($data);
        return view("home.index.index", ['data' => $data]);       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response  支付宝
     */
    public function create( Request $request )
    {

    }

    /**
     * @param Request $request
     * @param Article $article
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector 添加数据
     */
    public function notify(Request $request,Article $article){
        $data = [
        'name' => $request->name,
        ];
        //①
//		$result = $article->create($data);
        //②
        $result = $article->insertGetId($data);
//        p($result);die;
		if($result){   // ①$result ->id
			return redirect('/home/index/index');
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        echo 1;die;
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Article $article)
    {
		$content = $request ->input('content');
		$id = $request ->input('id');
		$result = $article->edit($id,$content);
		if($result){
			return redirect('/home/index/index');
		}else{
			echo '修改失败';
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,$name)
    {
        return view("home.index.update",['name'=>$name,'id'=>$id]);
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article,$id)
    {
       $data = $article ->del($id); //调用model层中方法
       if($data){
        return redirect('/home/index/index');
       }else{
        echo "删除失败";
       }
    }
}
 