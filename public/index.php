<?php

/***********************************
 * Toda requisição do sistema é direcionada para este arquivo (ver .htaccess nesta pasta)
 * 
 * Sua função é iniciar, por ordem:
 * 
 *  - Sessão
 *  - Autoload do Composer
 *  - Arquivo de configurações do sistema
 *  - Database
 *  - Rotas
 * 
 */

isset($_SESSION) || session_start();

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../app/config.php';

require_once __DIR__ . '/../system/database.php';

require_once __DIR__ . '/../system/routes.php';

