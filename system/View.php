<?php

/**
 * Classe estática responsável pela renderização das Views
 * 
 */

namespace System;

class View
{
    /**
     * Renderiza a view
     * Adiciona o conteúdo entre o header e o footer do layout
     * 
     * @param string $data O nome do arquivo da view
     * @param array $params O array de parâmetros passados para a view
     * @return void
     */
    public static function render($data, $params = [])
    {
        extract($params);
        
        include(__DIR__ . '/../app/Views/layout/header.php');  
        include(__DIR__ . '/../app/Views/' . $data); 
        include(__DIR__ . '/../app/Views/layout/footer.php');  
    }
}