<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApprovalProcess extends Model
{
    use HasFactory;

    /**
     * Available modules that can have approval workflows.
     * The array keys are stored in the database while the labels
     * are used in dropdowns.
     *
     * @var array<string,string>
     */
    public const MODULES = [
        'requisitions' => 'Requisitions',
        'job_orders' => 'Job Orders',
    ];

    protected $fillable = [
        'module',
        'department',
    ];

    public function stages(): HasMany
    {
        return $this->hasMany(ApprovalStage::class);
    }
}
