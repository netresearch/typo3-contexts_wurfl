<?php
// DO NOT CHANGE THIS FILE! It is automatically generated by extdeveval::buildAutoloadRegistry.
// This file was generated on 2013-02-25 21:20

$extensionPath        = t3lib_extMgm::extPath('contexts_wurfl');
$extensionClassesPath = $extensionPath . 'Classes/';

return array(
    'tx_contextswurfl_backend'
        => $extensionClassesPath . 'Backend.php',

    'tx_contextswurfl_context_type_wurfl'
        => $extensionClassesPath . 'Context/Type/Wurfl.php',

    'tx_contextswurfl_api_wurfl'
        => $extensionClassesPath . 'Api/Wurfl.php',

    'tx_contextswurfl_api_wurfl_databaseconnector'
        => $extensionClassesPath . 'Api/Wurfl/DatabaseConnector.php',

    'tx_contextswurfl_api_model_import'
        => $extensionClassesPath . 'Api/Model/Import.php',

    'tx_contextswurfl_backend_modules_admin_module'
        => $extensionClassesPath . 'Backend/Modules/Admin/Module.php',

    'tx_contextswurfl_service_importtask'
        => $extensionClassesPath . 'Service/ImportTask.php',

    'tx_contextswurfl_service_importcli'
        => $extensionClassesPath . 'Service/ImportCli.php',
);
?>
