<?php

namespace Modules\LaborHead\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\LaborHead\Entities\LaborBillRate;
use Modules\LaborHead\Entities\LaborBillRateDetail;
use Modules\LaborHead\Entities\LaborHead;
use Modules\LaborHead\Http\Requests\LaborBillRateFormRequest;
use Modules\Setting\Entities\Warehouse;

class LaborBillRateController extends BaseController
{
    public function __construct(LaborBillRate $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (permission('labor-bill-rate-access')) {
            $setTitle = __('file.Labor Bill Rate');
            $this->setPageData($setTitle, $setTitle, 'fas fa-th-list', [['name' => $setTitle]]);
            $data['warehouses'] = Warehouse::where('status', 1)->get();
            // return $data['warehouses'];

            $data['labor_heads'] = LaborHead::where('status', 1)->get();
            return view('laborhead::laborBillRate.index', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function create()
    {
        if (permission('labor-bill-add')) {
            $this->setPageData('Labor Bill', 'Labor Bill', 'far fa-money-bill-alt', [['name' => 'Labor'], ['name' => 'Bill']]);
            $data = [
                'laborHeads' => LaborHead::get(),
                'warehouses' => Warehouse::where('status', 1)->get(),
            ];
            return view('laborhead::laborBillRate.create_edit', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('labor-bill-rate-access')) {
            if (!empty($request->name)) {
                $this->model->setName($request->name);
            }
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if (permission('labor-bill-rate-edit')) {
                    $action .= ' <a class="dropdown-item" href="' . route('labor.bill.rate.edit', $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('labor-bill-rate-delete')) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->labour_head->name ?? '';
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function storeOrUpdate(LaborBillRateFormRequest $request)
    {

        if (($request->ajax() && permission('labor-bill-rate-add')) || ($request->ajax() && permission('labor-bill-rate-edit'))) {
            $collection   = collect($request->all());
            $result       = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());
            if (!empty($request->warehouse)) {
                LaborBillRateDetail::where(['labor_bill_rate_id' => $result->id,])->delete();
                foreach ($request->warehouse as $warehouse) {
                    LaborBillRateDetail::create([
                        'labor_bill_rate_id' => $result->id,
                        'warehouse_id' => $warehouse['warehouse_id'],
                        'rate' => $warehouse['rate']
                    ]);
                }
            }
            $output       = $this->store_message($result, $request->update_id);
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function edit($id)
    {
        if (permission('labor-bill-add')) {
            $this->setPageData('Labor Bill', 'Labor Bill', 'far fa-money-bill-alt', [['name' => 'Labor'], ['name' => 'Bill']]);
            $data = [
                'labor_bill_rate' => $this->model->find($id),
                'laborHeads' => LaborHead::get(),
                'warehouses' => Warehouse::where('status', 1)->get(),
            ];
            return view('laborhead::laborBillRate.create_edit', $data);
        } else {
            return $this->access_blocked();
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax() && permission('labor-bill-rate-delete')) {
            $result   = $this->model->find($request->id)->delete();
            LaborBillRateDetail::where(['labor_bill_rate_id' => $request->id])->delete();
            $output   = $this->delete_message($result);
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
}
