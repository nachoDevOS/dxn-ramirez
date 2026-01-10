<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class Income extends Model
{
    use HasFactory, RegistersUserEvents, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'supplier_id',
        'typeIncome',
        'amount',
        'date',
        'file',
        'observation',
        'status',

        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withTrashed();
    }

    public function incomeDetails()
    {
        return $this->hasMany(IncomeDetail::class, 'income_id');
    }
    public function register()
    {
        return $this->belongsTo(User::class, 'registerUser_id')->withTrashed();
    }
    public function incomeTransactions()
    {
        return $this->hasMany(IncomeTransaction::class, 'income_id');
    }

    public function getTotalStockAttribute()
    {
        return $this->incomeDetails->sum('stock');
    }
}
