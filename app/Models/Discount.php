<?php 

/**
 *  Modelo da tabela de desconto (discounts)
 *  
 *  Em caso de dúvida, ver documentação Eloquent
 *  https://github.com/illuminate/database
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model 
{
    protected $fillable = [
        'value',
        'invoice_id'
    ];
    
    public $timestamps = false;
}