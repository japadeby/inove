<?php 

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../../system/database.php';

use App\Models\Invoice;

class Recurrent
{
    public function createRecurrent()
    {
        // busca todas as contas recorrentes
        $invoices = Invoice::where([
            'recurrent' => 1,
            'invoice_id' => null
        ])
        ->get();
        // instancia o dia de hoje
        $today = new \DateTime('now');
        
        // percorre todas as contas
        foreach($invoices as $invoice) {
            // pega o vencimento da conta, instancia e adiciona o intervalo de recorrência
            $due = \DateTime::createFromFormat('Y-m-d', $invoice->due_date);
            $due->add(new \DateInterval("P".$invoice->recurrence_interval."M"));
            // armazena a data e mês de vencimento da nova conta
            $due_date = $due->format('d/m/Y');
            $due_month = $due->format('m');
            // subtrai dez dias do vencimento (uma espécie de tempo para pagamento)
            $due->sub(new \DateInterval("P10D"));
            
            // se o dia de hoje for o mesmo dia acima, cadastra a recorrência
            if ($today->format('Y-m-d') == $due->format('Y-m-d')) {
                // previne cadastrar recorrência duplicada (erro de sistema, cron, etc)
                if(Invoice::where(['invoice_id' => $invoice->id])->whereMonth('due_date', $due_month)->count()) {
                    continue;
                }
                // cria a recorrência
                Invoice::create([
                    'value' => $invoice->value > 0 ? $invoice->value - $invoice->discounts()->sum('value') : $invoice->value + $invoice->discounts()->sum('value'),
                    'invoice_id' => $invoice->id,
                    'due_date' => $due_date,
                    'recurrent' => 0,
                    'paid' => 0
                ]);
            }
        }
        // resposta http
        http_response_code(200);
    }
}

$recurrent = new Recurrent();
$recurrent->createRecurrent();

