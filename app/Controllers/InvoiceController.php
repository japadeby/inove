<?php
namespace App\Controllers;

use App\Models\Invoice;
use System\View;
use App\Models\Discount;

class InvoiceController
{
    /**
     * GET para / - A listagem de contas
     * @return mixed A view renderizada
     */
    public function getIndex()
    {
        $invoices = Invoice::orderBy('id', 'desc')->get();
        
        View::render('invoice_list.php', compact('invoices'));        
    }
    
    /**
     * GET para /edit - Edição de conta
     * @param integer $id O ID da conta
     * @return A view renderizada
     */
    public function getEdit($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            View::render('invoice_form.php', compact('invoice'));
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $_SESSION['error'] = $_ENV['error_messages']['not_found'];
            header("Location: /invoices/create");
        } catch (\Exception $e) {
            $_SESSION['error'] = sprintf($_ENV['error_messages']['general'], $e->getMessage());
            header("Location: /invoices/create");
        }        
    }
    
    /**
     * POST para /edit - Update de conta
     * @param integer $id O ID da conta
     * @return mixed Redireciona para a listagem em caso de sucesso, ou para o form em caso de falha
     */
    public function postEdit($id)
    {
        try {
            
            if(!$this->validate($_POST)) {
                $this->getEdit($id);
                return false;
            }
            
            $invoice = Invoice::findOrFail($id);
            
            $invoice->value = $_POST['type'] * $_POST['value'];
            $invoice->due_date = $_POST['due_date'];
            $invoice->paid = isset($_POST['paid']) ? 1 : 0;
            $invoice->recurrent = isset($_POST['recurrent']) ? 1 : 0;
            $invoice->pay_date = isset($_POST['paid']) && !empty($_POST['pay_date']) ? $_POST['pay_date'] : null;
            $invoice->recurrence_interval = isset($_POST['recurrent']) && !empty($_POST['recurrence_interval']) ? $_POST['recurrence_interval'] : null;
            
            $invoice->save();
            
            $_SESSION['success'] = $_ENV['success_message'];
            header("Location: /invoices");
            
        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $_SESSION['error'] = $_ENV['error_messages']['not_found'];
            header("Location: /invoices/create");
        } catch (\Exception $e) {
            $_SESSION['error'] = sprintf($_ENV['error_messages']['general'], $e->getMessage());
            header("Location: /invoices/create");
        }
    }
    
    /**
     * GET para /create - Form de criação de conta
     * @return mixed A view renderizada
     */
    public function getCreate()
    {
        View::render('invoice_form.php');
    }
    
    /**
     * POST para / - Criação de conta
     * @return mixed Redireciona para listagem em caso de sucesso, ou mostra o form em caso de falha
     */
    public function postIndex()
    {
        if(!$this->validate($_POST)) {
            $this->getCreate();
            return false;
        }
        try {
            Invoice::create([
                'value' => $_POST['type'] * $_POST['value'], 
                'due_date' => $_POST['due_date'], 
                'paid' => isset($_POST['paid']) ? 1 : 0,    
                'recurrent' => isset($_POST['recurrent']) ? 1 : 0,
                'pay_date' => isset($_POST['paid']) && !empty($_POST['pay_date']) ? $_POST['pay_date'] : null,
                'recurrence_interval' => isset($_POST['recurrent']) && !empty($_POST['recurrence_interval']) ? $_POST['recurrence_interval'] : null,
            ]);
            $_SESSION['success'] = $_ENV['success_message'];
            header("Location: /invoices");
        } catch (\Exception $e) {
            $_SESSION['error'] = sprintf($_ENV['error_messages']['general'], $e->getMessage());
            $this->getCreate();
        }
    }
    
    /**
     * POST para /delete - Excluir uma conta do DB
     * @param integer $id O ID da conta
     * @return mixed Redireciona para a listagem
     */
    public function postDelete($id)
    {
        try {
            Invoice::destroy($id);
            $_SESSION['success'] = $_ENV['success_message'];
        } catch (\Exception $e) {
            $_SESSION['error'] = sprintf($_ENV['error_messages']['general'], $e->getMessage());
        }
        header("Location: /invoices");
    }
    
    /**
     * GET para /recurrent - Cria recorrencia de conta 
     * @return void
     */
    public function getRecurrent()
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
    
    /**
     * Valida os dados da requisição 
     * @param array $data
     * @return boolean True em caso de sucesso, False em caso de falha
     */
    private function validate($data)
    {
        if (isset($_SESSION['error'])) {
            unset($_SESSION['error']);
        }
        
        if (!array_key_exists('value', $data) || empty($data['value'])) {
            $_SESSION['error'] = sprintf($_ENV['error_messages']['required'], 'valor');
            return false;
        }
        if (!floatval($data['value']) || floatval($data['value']) > $_ENV['max_invoice_value']) {
            $_SESSION['error'] = sprintf($_ENV['error_messages']['invalid'], 'valor');
            return false;
        }
        if (!array_key_exists('type', $data) || ($data['type'] != '-1' && $data['type'] != '1')) {
            $_SESSION['error'] = sprintf($_ENV['error_messages']['required'], 'tipo');
            return false;
        }
        if (!array_key_exists('due_date', $data) || !date_create_from_format('d/m/Y', $data['due_date'])) {
            $_SESSION['error'] = sprintf($_ENV['error_messages']['required'], 'vencimento');
            return false;
        }
        if (array_key_exists('pay_date', $data) && !empty($data['pay_date']) && !date_create_from_format('d/m/Y', $data['pay_date'])) {
            $_SESSION['error'] = sprintf($_ENV['error_messages']['invalid'], 'data pagamento');
            return false;
        }
        if (array_key_exists('recurrent', $data) && !array_key_exists('recurrence_interval', $data)) {
            $_SESSION['error'] = sprintf($_ENV['error_messages']['required'], 'intervalo');
            return false;
        }
        if (array_key_exists('recurrence_interval', $data) && !empty($data['recurrence_interval']) && !is_numeric(intval($data['recurrence_interval']))) {
            $_SESSION['error'] = sprintf($_ENV['error_messages']['invalid'], 'intervalo');
            return false;
        }
        return true;
    }
}