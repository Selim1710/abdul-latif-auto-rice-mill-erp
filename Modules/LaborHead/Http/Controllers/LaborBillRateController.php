<?php

namespace Modules\LaborHead\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\LaborHead\Entities\LaborBillRate;
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
            return view('laborhead::laborBillRate.create', $data);
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
                    $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"  data-warehouse_id="' . ($value->warehouse_id ?? '') . '" data-name="' . $value->name . '" data-rate="' . $value->rate . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('labor-bill-rate-delete')) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->warehouse->name ?? '';
                $row[]  = $value->name;
                $row[]  = $value->rate;
                $row[]  = STATUS_LABEL[$value->status];
                $row[]  = $value->created_by;
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
            $collection   = collect($request->validated());
            $collection   = $this->track_data($collection, $request->update_id);
            $result       = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());
            $output       = $this->store_message($result, $request->update_id);
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax() && permission('labor-bill-rate-delete')) {
            $result   = $this->model->find($request->id)->delete();
            $output   = $this->delete_message($result);
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
}
