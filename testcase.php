<?php

require_once __DIR__ . '/smarty-config.php';

class SvgImage {
    public function render($params, $smarty) {
        $params += [
            'id' => null,
            'class' => null,
            'style' => null,
            'fallback' => null,
        ];
        return "\$" . __CLASS__ . " = " . var_export($params, true);
    }
}

$smarty->registerPlugin('function', 'svgImage', [new SvgImage('/img/cat/icon'), 'render'], true, [
    'name', 'id', 'class', 'style', 'fallback'
]);

$smarty->display(__FILE__);

__HALT_COMPILER();
?>{svgImage name="icon"}

{svgImage name="icon" id="id" class="none" style="float: left;" fallback="text"}

PHP: {$smarty.const.PHP_VERSION}
Smarty: {$smarty.version}
