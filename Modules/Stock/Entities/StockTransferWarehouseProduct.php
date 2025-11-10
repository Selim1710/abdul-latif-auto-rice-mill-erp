<?php

namespace Modules\Stock\Entities;

use App\Models\BaseModel;
use Modules\Party\Entities\Party;
use Modules\Product\Entities\Product;
use Modules\Purchase\Entities\Purchase;

class StockTransferWarehouseProduct extends BaseModel
{
    protected $fillable = ['stock_transfer_id', 'product_id', 'scale', 'qty', 'purchase_id', 'party_id', 'purchase_price'];
    protected $table    = 'stock_transfer_warehouse_products';
    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function availableQty(int $party_id, int $purchase_id, int $warehouseId, int $productId)
    {
        return WarehouseProduct::firstWhere(['party_id' => $party_id, 'purchase_id' => $purchase_id, 'warehouse_id' => $warehouseId, 'product_id' => $productId]);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id', 'id');
    }
}
