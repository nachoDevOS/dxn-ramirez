<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RegistersUserEvents;

class IncomeTransaction extends Model
{
    use HasFactory, RegistersUserEvents, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'transaction_id',
        'income_id',
        // 'cashier_id',
        'paymentType',
        'amount',
        'observation',

        'registerUser_id',
        'registerRole',
        'deleted_at',
        'deleteUser_id',
        'deleteRole',
        'deleteObservation',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function income()
    {
        return $this->belongsTo(Income::class, 'income_id');
    }
    public function register()
    {
        return $this->belongsTo(User::class, 'registerUser_id')->withTrashed();
    }
}
