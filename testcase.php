<?php

require_once __DIR__ . '/smarty-config.php';

$smarty->setLeftDelimiter("<{");
$smarty->setRightDelimiter("}>");

$smarty->display(__FILE__);

__HALT_COMPILER();
?>PHP: <{$smarty.const.PHP_VERSION}>
Smarty: <{$smarty.version}>
