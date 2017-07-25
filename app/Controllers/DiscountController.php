<?php
namespace App\Controllers;

use App\Models\Discount;

class DiscountController
{
    /**
     * POST para novo desconto, aceita apenas requisição Ajax
     * @param integer $invoice_id O ID da conta referente
     * @return string  Status da requisição e mensagem de retorno em formato json, além do código Http
     */
    public function postIndex($invoice_id)
    {
        $this->validateRequest();
        $validate = $this->validate($_POST);
        if(!$validate['status']) {
            http_response_code(400);
            print json_encode($validate);
            return false;
        }
        try {
            $discount = Discount::create([
                'value' => $_POST['value'], 
                'invoice_id' => $invoice_id,
            ]);
            http_response_code(201);
            print json_encode(['status' => true, 'msg' => $_ENV['success_message'], 'discount' => $discount]);
        } catch (\Exception $e) {
            http_response_code(500);
            print json_encode(['status' => false, 'msg' => $e->getMessage()]);
        }
    }
    
    /**
     * POST para edição de desconto, aceita apenas requisição Ajax
     * @param integer $discount_id O ID do desconto
     * @return string  Status da requisição e mensagem de retorno em formato json, além do código Http
     */
    public function postEdit($discount_id)
    {
        $this->validateRequest();
        $validate = $this->validate($_POST);
        if(!$validate['status']) {
            http_response_code(400);
            print json_encode($validate);
            return false;
        }
        try {
            $discount = Discount::findOrFail($discount_id);
            $discount->value = $_POST['value'];
            $discount->save();
            http_response_code(201);
            print json_encode(['status' => true, 'msg' => $_ENV['success_message'], 'discount' => $discount]);
        } catch (\Exception $e) {
            http_response_code(500);
            print json_encode(['status' => false, 'msg' => $e->getMessage()]);
        }
    }
    
    /**
     * POST para exclusão de desconto, aceita apenas requisição Ajax
     * @param integer $id O ID do desconto
     * @return string Status da requisição e mensagem de retorno em formato json, além do código Http
     */
    public function postDelete($id)
    {
        $this->validateRequest();
        try {
            Discount::destroy($id);
            http_response_code(200);
            print json_encode(['status' => true, 'msg' => $_ENV['success_message']]);
        } catch (\Exception $e) {
            http_response_code(500);
            print json_encode(['status' => false, 'msg' => $e->getMessage()]);
        }
    }
    
    /**
     * Valida os dados da requisição
     * @param array $data
     * @return array  Status da requisição (boleano) e, caso false, mensagem de retorno
     */
    private function validate($data)
    {
        if (!array_key_exists('value', $data) || empty($data['value'])) {
            return ['status' => false, 'msg' => sprintf($_ENV['error_messages']['required'], 'valor')];
        }
        if (!floatval($data['value'])) {
            return ['status' => false, 'msg' => sprintf($_ENV['error_messages']['invalid'], 'valor')];
        }
        return ['status' => true];
    }
    
    /**
     * Valida se é uma requição ajax
     * @return boolean|mixed True em caso de sucesso ou 404 em caso de falha
     */
    private function validateRequest()
    {
        if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            http_response_code(404);
            include __DIR__ . '/../Views/errors/404.html';
            exit();
        }
        return true;
    }
}