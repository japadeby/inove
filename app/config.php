<?php

/**
 * Arquivo de configuração do sistema
 */


// banco de dados (ver documentação Eloquent: https://github.com/illuminate/database)
$_ENV['database'] = [
    'driver' => 'mysql',
    'username' => 'root',
    'password' => 'netofire',
    'host' => 'localhost',
    'database' => 'inove',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
];

// regra de negócio
$_ENV['max_invoice_value'] = 881.9; 

// mensagens de erro
$_ENV['error_messages'] = [
    'required' => 'O campo %s é obrigatório',  
    'invalid' => 'O campo %s tem um valor inválido', 
    
    'general' => 'Ocorreu um erro e o processo não foi finalizado: %s',
    'not_found' => 'Registro não encontrado'
];

// mensagens de sucesso
$_ENV['success_message'] = 'Processo concluído com sucesso!';