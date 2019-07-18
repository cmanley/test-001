<?php

require_once __DIR__ . '/smarty-config.php';

$smarty->assign("a", new class
{
  function b()
  {
    return ["c"];
  }
});

$smarty->display(__FILE__);

__HALT_COMPILER();
?>{$d=$a->b()}{$d[0]}
{* Parsing error $a->b()[0] *}

PHP: {$smarty.const.PHP_VERSION}
Smarty: {$smarty.version}
