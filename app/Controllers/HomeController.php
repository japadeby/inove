<?php
namespace App\Controllers;

use App\Models\Invoice;
use System\View;

class HomeController
{
    /**
     * Controller da homepage
     * @return mixed A view renderizada
     */
    public function index()
    {
        $dataStart = date('Y-m-d');
        $dataEnd = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') + 30, date('Y')));
        
        $invoices = Invoice::where([
            'paid' => 0,
            ['due_date', '>=', $dataStart],
            ['due_date', '<=', $dataEnd]
        ])
        ->orderBy('due_date')
        ->get();
        $topay = 0;
        $toget = 0;
        
        foreach($invoices as $invoice) {
            if ($invoice->value > 0) {
                $toget += $invoice->value - $invoice->discounts()->sum('value');
            } else {
                $topay += abs($invoice->value) - $invoice->discounts()->sum('value');                
            }
        }
        
        View::render('index.php', compact('topay', 'toget', 'invoices', 'dataStart', 'dataEnd'));        
    }
}