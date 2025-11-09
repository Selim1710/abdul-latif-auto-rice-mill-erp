<?php

namespace Modules\Production\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\TenantProduction\Entities\TenantProduction;

class ProductionBatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'production_id',
        'tenant_production_id',
        'batch_no',
        'is_deleted',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class, 'production_id', 'id');
    }

    public function tenant_production()
    {
        return $this->belongsTo(TenantProduction::class, 'tenant_production_id', 'id');
    }   
}
