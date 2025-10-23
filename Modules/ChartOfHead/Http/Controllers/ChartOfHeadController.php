<?php

namespace Modules\ChartOfHead\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ChartOfHead\Entities\ChartOfHead;

class ChartOfHeadController extends BaseController {
    public function __construct(ChartOfHead $model){
        $this->model = $model;
    }
    public function index(){
        if(permission('chart-access')){
            $setTitle = __('file.Chart Of Head');
            $this->setPageData($setTitle,$setTitle,'fas fa-th-list',[['name' => $setTitle]]);
            return view('chartofhead::chart.index');
        }else{
            return $this->access_blocked();
        }
    }
    public function accountList($paymentMethod){
        if($paymentMethod == 1){
            $accounts  = ChartOfHead::where(['id' => 24])->get();
        }else if($paymentMethod == 2){
            $accounts  = ChartOfHead::whereNotNull('bank_id')->where('classification','=',5)->get();
        }else if($paymentMethod == 3){
            $accounts  = ChartOfHead::whereNotNull('mobile_bank_id')->where('classification','=',6)->get();
        }
        return response()->json($accounts);
    }
}
