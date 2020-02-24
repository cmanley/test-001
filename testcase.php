<?php

require_once __DIR__ . '/smarty-config.php';

$template = $smarty->createTemplate(__FILE__);
$template->setCacheLifetime(1);

if($template->isCached() ){
	die('Template is cached');
} else {
	$template->display();
}

__HALT_COMPILER();
?>PHP: {$smarty.const.PHP_VERSION}
Smarty: {$smarty.version}

{$smarty.now}
