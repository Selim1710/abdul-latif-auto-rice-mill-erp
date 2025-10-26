<?php

namespace Modules\LaborHead\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LaborHead\Entities\LabourType;
use Modules\LaborHead\Http\Requests\LabourTypeRequest;

class LabourTypeController extends BaseController
{
    public function __construct(LabourType $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (permission('labour-type-access')) {
            $setTitle = __('Labour Type');
            $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
            $deletable = self::DELETABLE;
            // return 'ok';
            return view('laborhead::labour-type.index', compact('deletable'));
        } else {
            return $this->access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if ($request->ajax()) {
            if (permission('labour-type-access')) {

                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }

                $this->set_datatable_default_properties($request); //set datatable default properties
                $list   = $this->model->getDatatableList(); //get table data
                $data   = [];
                $no     = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';
                    if (permission('labour-type-edit')) {
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">' . $this->actionButton('Edit') . '</a>';
                    }

                    if (permission('labour-type-delete')) {
                        if ($value->deletable == 2) {
                            $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">' . $this->actionButton('Delete') . '</a>';
                        }
                    }

                    $row    = [];
                    $row[]  = $no;
                    $row[]  = $value->name;
                    $row[]  = permission('labour-type-edit') ? change_status($value->id, $value->status, $value->name) : STATUS_LABEL[$value->status];
                    $row[]  = action_button($action); //custom helper function for action button
                    $data[] = $row;
                }
                return $this->datatable_draw(
                    $request->input('draw'),
                    $this->model->count_all(),
                    $this->model->count_filtered(),
                    $data
                );
            }
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function store_or_update_data(LabourTypeRequest $request)
    {
        if ($request->ajax()) {
            if (permission('labour-type-add')) {
                $collection   = collect($request->validated());
                $collection   = $this->track_data($collection, $request->update_id);
                $result       = $this->model->updateOrCreate(['id' => $request->update_id], $collection->all());
                $output       = $this->store_message($result, $request->update_id);
            } else {
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            if (permission('labour-type-edit')) {
                $data   = $this->model->findOrFail($request->id);
                $output = $this->data_message($data); //if data found then it will return data otherwise return error message
            } else {
                $output = $this->unauthorized();
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (permission('labour-type-delete')) {
                $result   = $this->model->find($request->id)->delete();
                $output   = $this->delete_message($result);
            } else {
                $output   = $this->unauthorized();
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function change_status(Request $request)
    {
        // return $request;
        $result = $this->model->find($request->id)->update([
            'status' => $request->status
        ]);

        $output = $result ? ['status' => 'success', 'message' => 'Status Has Been Changed Successfully'] : ['status' => 'error', 'message' => 'Failed To Change Status'];
        return response()->json($output);
    }
}
