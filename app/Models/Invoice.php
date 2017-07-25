<?php 

/**
 *  Modelo da tabela de contas (invoices)
 *
 *  Em caso de dúvida, ver a documentação Eloquent
 *  https://github.com/illuminate/database
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model 
{
    protected $fillable = [
        'value',
        'invoice_id',
        'due_date',
        'recurrent',
        'paid',
        'recurrence_interval',
        'pay_date',
    ];
    
    public $timestamps = false;
    
    public function discounts()
    {
        return $this->hasMany('App\Models\Discount');
    }
    
    public function invoice()
    {
        return $this->hasOne('App\Models\Invoice');
    }
    
    public function setDueDateAttribute($value)
    {
        $due_date = \DateTime::createFromFormat('d/m/Y', $value);
        $this->attributes['due_date'] = $due_date->format('Y-m-d');
    }
    
    public function setPayDateAttribute($value)
    {
        if ($value === null) {
            $this->attributes['pay_date'] = null;
        } else {
            $pay_date= \DateTime::createFromFormat('d/m/Y', $value);
            $this->attributes['pay_date'] = $pay_date->format('Y-m-d');
        }
    }
}