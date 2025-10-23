<?php

namespace Modules\Asset\Http\Controllers;

use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Asset\Entities\Asset;
use Illuminate\Support\Facades\DB;
use Modules\Asset\Entities\AssetType;
use App\Http\Controllers\BaseController;
use Modules\Asset\Http\Requests\AssetRequest;

class AssetController extends BaseController
{
    use UploadAble;
    public function __construct(Asset $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if(permission('asset-access')){
            $setTitle = __('file.Manage Asset');
            $this->setPageData($setTitle,$setTitle,'fas fa-desktop',[['name' => $setTitle]]);
            $data = [
                'types' => AssetType::allAssetTypes(),
            ];
            return view('asset::index',$data);
        }else{
            return $this->access_blocked();
        }
    }
    public function get_datatable_data(Request $request)
    {
        if($request->ajax()){
            if(permission('asset-access')){
                if (!empty($request->tag)) {
                    $this->model->setTag($request->tag);
                }
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }
                if (!empty($request->asset_type_id)) {
                    $this->model->setAssetTypeID($request->asset_type_id);
                }
                if (!empty($request->asset_status)) {
                    $this->model->setAssetStatus($request->asset_status);
                }
                $this->set_datatable_default_properties($request);//set datatable default properties
                $list = $this->model->getDatatableList();//get table data
                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';
                    if(permission('asset-edit')){
                        $action .= ' <a class="dropdown-item edit_data"" data-id="' . $value->id . '">'.$this->actionButton('Edit').'</a>';
                    }
                    if(permission('asset-view')){
                        $action .= ' <a class="dropdown-item view_data" data-id="' . $value->id . '">'.$this->actionButton('View').'</a>';
                    }
                    if(permission('asset-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">'.$this->actionButton('Delete').'</a>';
                    }
                    $row = [];
                    if(permission('asset-bulk-delete')){
                        $row[] = row_checkbox($value->id);//custom helper function to show the table each row checkbox
                    }
                    $row[] = $no;
                    $row[] = $this->table_image(ASSET_PHOTO_PATH,$value->photo,$value->name);
                    $row[] = $value->tag;
                    $row[] = $value->name;
                    $row[] = $value->asset_type->name;
                    $row[] = $value->purchase_date ? date('j F, Y',strtotime($value->purchase_date)) : '';
                    $row[] = number_format($value->cost,2);
                    $row[] = $value->warranty;
                    $row[] = $value->user;
                    $row[] = $value->location;
                    $row[] = ASSET_STATUS[$value->asset_status];
                    $row[] = permission('asset-edit') ? change_status($value->id,$value->status, $value->name) : STATUS_LABEL[$value->status];
                    $row[] = action_button($action);//custom helper function for action button
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
                $this->model->count_filtered(), $data);
            }
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function store_or_update_data(AssetRequest $request)
    {
        if($request->ajax()){
            if(permission('asset-add') || permission('asset-edit')){
                DB::beginTransaction();
                try {
                    $collection        = collect($request->validated());
                    $warranty          = $request->warranty ? $request->warranty : 0;
                    $photo = !empty($request->old_photo) ? $request->old_photo : null;
                    if($request->hasFile('photo')){
                        $photo      = $this->upload_file($request->file('photo'),ASSET_PHOTO_PATH);
                    }
                    $collection        = $collection->merge(compact('warranty','photo'));
                    $collection        = $this->track_data($collection,$request->update_id);
                    $result     = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());

                    if($result)
                    {
                        if($request->hasFile('photo')){
                            if(!empty($request->old_photo)){
                                $this->delete_file($request->old_photo, ASSET_PHOTO_PATH);
                            }
                        }
                    }else{
                        if($request->hasFile('photo')){
                            if(!empty($photo)){
                                $this->delete_file($photo, ASSET_PHOTO_PATH);
                            }
                        }
                    }
                    $output     = $this->store_message($result, $request->update_id);
                    DB::commit();
                }catch (\Throwable $th) {
                   DB::rollback();
                   $output = ['status' => 'error','message' => $th->getMessage()];
                }
            }else{
                $output     = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function edit(Request $request)
    {

        if($request->ajax()){
            if(permission('asset-edit')){
                $data   = $this->model->findOrFail($request->id);
                $output = $this->data_message($data); //if data found then it will return data otherwise return error message
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }
    public function show(Request $request)
    {
        if($request->ajax()){
            if(permission('asset-view')){
                $asset   = $this->model->with('asset_type')->findOrFail($request->id);
                return view('asset::view-data',compact('asset'))->render();
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }
    }


    public function delete(Request $request)
    {
        if($request->ajax()){
            if(permission('asset-delete')){
                $asset  = $this->model->find($request->id);
                $old_photo = $asset ? $asset->photo : '';
                $result    = $asset->delete();
                if($result && $old_photo != ''){
                    $this->delete_file($old_photo, ASSET_PHOTO_PATH);
                }
                $output   = $this->delete_message($result);
            }else{
                $output   = $this->unauthorized();

            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            if(permission('asset-bulk-delete')){
                $assets = $this->model->toBase()->select('photo')->whereIn('id',$request->ids)->get();
                $result = $this->model->destroy($request->ids);
                if($result){
                    if(!empty($assets)){
                        foreach ($assets as $asset) {
                            if($asset->photo != null)
                            {
                                $this->delete_file($asset->photo,ASSET_PHOTO_PATH);
                            }
                        }
                    }
                }
                $output   = $this->bulk_delete_message($result);
            }else{
                $output   = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

}
