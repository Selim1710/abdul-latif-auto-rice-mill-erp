<?php

namespace Modules\Stock\Http\Controllers;

use Exception;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Category\Entities\Category;
use Modules\Setting\Entities\Warehouse;
use Modules\Stock\Entities\StockTransfer;
use Modules\Stock\Entities\StockTransferWarehouseProduct;
use Illuminate\Support\Str;
use Modules\Stock\Entities\WarehouseProduct;
use Modules\Stock\Http\Requests\StockTransferRequestForm;

class StockTransferController extends BaseController
{
    private const st = 'STOCK-TRANSFER';
    public function __construct(StockTransfer $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('stock-transfer-access')) {
            $setTitle = __('file.Stock Transfer');
            $this->setPageData($setTitle, $setTitle, 'fas fa-boxes', [['name' => $setTitle]]);
            return view('stock::stockTransfer.index');
        } else {
            return $this->access_blocked();
        }
    }
    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('stock-transfer-access')) {
            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                if (permission('stock-transfer-view')) {
                    $action .= ' <a class="dropdown-item" href="' . route("stock.transfer.show", $value->id) . '">' . $this->actionButton('View') . '</a>';
                }
                if (permission('stock-transfer-change-status') && $value->status == 2) {
                    $action .= ' <a class="dropdown-item change_status"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '" data-status="' . $value->status . '">' . $this->actionButton('Change Status') . '</a>';
                }
                if (permission('stock-transfer-edit') && $value->status == 2) {
                    $action .= ' <a class="dropdown-item" href="' . route("stock.transfer.edit", $value->id) . '">' . $this->actionButton('Edit') . '</a>';
                }
                if (permission('stock-transfer-delete') && $value->status == 2) {
                    $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->invoice_no . '">' . $this->actionButton('Delete') . '</a>';
                }
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->invoice_no;
                $row[]  = $value->transfer_date;
                $row[]  = $value->transferWarehouse->name;
                $row[]  = $value->receiveWarehouse->name;
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
    public function create()
    {
        if (permission('stock-transfer-add')) {
            $setTitle = __('file.Stock Transfer');
            $this->setPageData($setTitle, $setTitle, 'fas fa-boxes', [['name' => $setTitle]]);
            $data = [
                'invoiceNo'  => self::st . '-' . round(microtime(true) * 1000),
                'categories' => Category::all(),
                'warehouses' => Warehouse::where('status', 1)->get(),
            ];
            return view('stock::stockTransfer.create', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function store(StockTransferRequestForm $request)
    {
        // return $request;
        if ($request->ajax() && permission('stock-transfer-add')) {
            DB::beginTransaction();
            try {
                $warehouseProduct   = [];
                $collection         = collect($request->all())->except('_token', 'transfer')->merge(['created_by' => auth()->user()->name]);
                $stockTransfer      = StockTransfer::create($collection->all());
                if ($request->has('transfer')) {
                    foreach ($request->transfer as $value) {
                        if (!empty($value['product_id']) && !empty($value['scale']) && !empty($value['qty'])) {
                            $warehouseProduct[] = [
                                'product_id'        => $value['product_id'],
                                'scale'             => $value['scale'],
                                'qty'               => $value['qty'],
                                'purchase_id'               => $value['purchase_id'] ?? '',
                                'party_id'               => $value['party_id'] ?? '',
                                'purchase_price'               => $value['purchase_price'] ?? '',
                            ];
                        }
                    }
                }
                $stockTransfer->stockTransferWarehouseProduct()->attach($warehouseProduct);
                $output = ['status' => 'success', 'message' => 'Stock Transfer Successfully'];
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $output = ['status' => 'error', 'message' => $e->getMessage()];
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function show($id)
    {
        if (permission('stock-transfer-view')) {
            $setTitle = __('file.Transfer Details');
            $this->setPageData($setTitle, $setTitle, 'fas fa-boxes', [['name' => $setTitle]]);
            $data = [
                'details' => $this->model->with('transferWarehouse', 'receiveWarehouse', 'stockTransferWarehouseProductList', 'stockTransferWarehouseProductList.product', 'stockTransferWarehouseProductList.product.unit')->findOrFail($id)
            ];
            return view('stock::stockTransfer.details', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function edit($id)
    {
        if (permission('stock-transfer-view')) {
            $setTitle   = __('file.Transfer Edit');
            $this->setPageData($setTitle, $setTitle, 'fas fa-boxes', [['name' => $setTitle]]);
            $data = [
                'edit'       => $this->model->with('transferWarehouse', 'receiveWarehouse', 'stockTransferWarehouseProductList', 'stockTransferWarehouseProductList.product', 'stockTransferWarehouseProductList.product.unit')->findOrFail($id),
                'warehouses' => Warehouse::all()
            ];
            return view('stock::stockTransfer.edit', $data);
        } else {
            return $this->access_blocked();
        }
    }
    public function update(StockTransferRequestForm $request)
    {
        if ($request->ajax() && permission('stock-transfer-edit')) {
            DB::beginTransaction();
            try {
                $warehouseProduct   = [];
                $stockTransfer      = $this->model->findOrFail($request->update_id);
                $collection         = collect($request->all())->except('_token', 'update_id', 'transfer');
                if ($request->has('transfer')) {
                    foreach ($request->transfer as $value) {
                        if (!empty($value['product_id']) && !empty($value['scale']) && !empty($value['qty'])) {
                            $warehouseProduct[Str::random(5)] = [
                                'product_id'        => $value['product_id'],
                                'scale'             => $value['scale'],
                                'qty'               => $value['qty'],
                                'purchase_id'               => $value['purchase_id'] ?? '',
                                'party_id'               => $value['party_id'] ?? '',
                                'purchase_price'               => $value['purchase_price'] ?? '',
                            ];
                        }
                    }
                }
                $stockTransfer->update($collection->all());
                $stockTransfer->stockTransferWarehouseProduct()->sync($warehouseProduct);
                $output = ['status' => 'success', 'message' => 'Data Update Successfully'];
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $output = ['status' => 'error', 'message' => $e->getMessage()];
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function changeStatus(Request $request)
    {
        if ($request->ajax() && permission('stock-transfer-change-status')) {
            DB::beginTransaction();
            try {
                $data = $this->model->with('stockTransferWarehouseProductList')->find($request->id);
                $data->update(['status' => 1]);
                if (isset($data->stockTransferWarehouseProductList)) {
                    foreach ($data->stockTransferWarehouseProductList as $value) {
                        $transferWarehouseProduct = WarehouseProduct::firstWhere([
                            'warehouse_id'     => $data->transfer_warehouse_id,
                            'product_id'       => $value->product_id,
                            'party_id'       => $value->party_id,
                            'purchase_id'       => $value->purchase_id,

                        ]);
                        if (($value->qty > $transferWarehouseProduct->qty) || ($value->scale > $transferWarehouseProduct->scale)) {
                            return response()->json(['status' => 'error', 'message' => 'Transfer Quantity Not Be Greater Then Stock Quantity']);
                        }
                        $transferWarehouseProduct->update([
                            'scale'            => $transferWarehouseProduct->scale - $value->scale,
                            'qty'              => $transferWarehouseProduct->qty   - $value->qty
                        ]);
                        $receiveWarehouseProduct = WarehouseProduct::firstOrNew(
                            [
                                'warehouse_id'  => $data->receive_warehouse_id,
                                'product_id'    => $value->product_id,
                                'party_id'       => $value->party_id,
                                'purchase_id'       => $value->purchase_id,
                            ],
                            [
                                'scale'         => $value->scale,
                                'qty'           => $value->qty
                            ]
                        );
                        if (!empty($receiveWarehouseProduct)) {
                            $receiveWarehouseProduct->update([
                                'scale'         => $receiveWarehouseProduct->scale + $value->scale,
                                'qty'           => $receiveWarehouseProduct->qty   + $value->qty
                            ]);
                        }
                        $receiveWarehouseProduct->save();
                    }
                }
                $output = ['status' => 'success', 'message' => 'Data Status Change Successfully'];
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $output = ['status' => 'error', 'message' => $e->getMessage()];
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request)
    {
        if ($request->ajax() && permission('stock-transfer-delete')) {
            DB::beginTransaction();
            try {
                $delete = $this->model->find($request->id);
                $delete->stockTransferWarehouseProductList()->delete();
                $delete->delete();
                $output = $this->delete_message($delete);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $output = ['status' => 'error', 'message' => $e->getMessage()];
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function warehouseProduct($warehouse_id)
    {
        $data              = [];
        $warehouseProducts = WarehouseProduct::with('warehouse', 'product', 'product.unit', 'purchase', 'party')->where([['warehouse_id', '=', $warehouse_id], ['qty', '!=', 0]])->get();
        foreach ($warehouseProducts as $value) {
            $data[] = [
                'purchase_price'     => $value->purchase_price ?? '',
                'party_id'     => $value->party_id ?? '',
                'party_name'     => $value->party->name ?? '',
                'purchase_id'     => $value->purchase_id ?? '',
                'invoice_no'     => $value->purchase->invoice_no ?? '',
                'productId'     => $value->product_id,
                'productName'   => $value->product->product_name,
                'unitName'      => $value->product->unit->unit_name . '(' . $value->product->unit->unit_code . ')',
                'scale'         => $value->scale,
                'qty'           => $value->qty
            ];
        }
        return response()->json($data);
    }
}
