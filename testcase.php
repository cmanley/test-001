<?php
# See https://github.com/smarty-php/smarty/issues/549
$use_fix = false;

# Set encoding to an 8 bit encoding and make sure to save this file in that encoding.
mb_internal_encoding('Windows-1252');
ini_set('default_charset', mb_internal_encoding());
define('SMARTY_RESOURCE_CHAR_SET', mb_internal_encoding());	# this should be a constructor option IMHO so that templates of various encodings can be used in 1 process.


require_once(__DIR__ . '/smarty-config.php');


/**
* This replaces the Smarty function smarty_mb_str_replace in plugins/shared.mb_str_replace.php which is broken in Smarty 3.1.36 at least.
* @see https://github.com/smarty-php/smarty/issues/549
*
* @package    Smarty
* @subpackage PluginsShared
*/
if ($use_fix) {
	 #&& !function_exists('smarty_mb_str_replace') && version_compare(Smarty::SMARTY_VERSION, '3.1.36', '<=')
	#error_log('Replacing smarty_mb_str_replace');
	/**
	 * Multibyte string replace
	 *
	 * @param string|string[] $search  the string to be searched
	 * @param string|string[] $replace the replacement string
	 * @param string		  $subject the source string
	 * @param int			 &$count  number of matches found
	 *
	 * @return string replaced string
	 * @author Rodney Rehm
	 */
	function smarty_mb_str_replace($search, $replace, $subject, &$count = 0)
	{
		#error_log(__FUNCTION__ . ' encoding=' . mb_internal_encoding() . ": search='$search' replace='$replace' subject='$subject'");
		if (!is_array($search) && is_array($replace)) {
			return false;
		}
		if (is_array($subject)) {
			# call mb_replace for each single string in $subject
			foreach ($subject as &$string) {
				$string = smarty_mb_str_replace($search, $replace, $string, $c);
				$count += $c;
			}
		} elseif (is_array($search)) {
			if (!is_array($replace)) {
				foreach ($search as &$string) {
					$subject = smarty_mb_str_replace($string, $replace, $subject, $c);
					$count += $c;
				}
			} else {
				$n = max(count($search), count($replace));
				while ($n--) {
					$subject = smarty_mb_str_replace(current($search), current($replace), $subject, $c);
					$count += $c;
					next($search);
					next($replace);
				}
			}
		}
		else {	 # This block contains the replacement fix
			$want_encoding = 'UTF-8';
			$must_transcode = function_exists('mb_convert_encoding') && strcasecmp(Smarty::$_CHARSET, $want_encoding);
			#error_log(__FUNCTION__ . " must_transcode=$must_transcode");
			if ($must_transcode) {
				$search  = mb_convert_encoding($search , $want_encoding, Smarty::$_CHARSET);
				$replace = mb_convert_encoding($replace, $want_encoding, Smarty::$_CHARSET);
				$subject = mb_convert_encoding($subject, $want_encoding, Smarty::$_CHARSET);
				#error_log(__FUNCTION__ . " encoding=UTF-8: search='$search' replace='$replace' subject='$subject'");
			}
			$subject = preg_replace('/' . preg_quote($search, '/') . '/u', $replace, $subject, -1, $count);	# Note the /u !
			if ($must_transcode) {
				$subject = mb_convert_encoding($subject, Smarty::$_CHARSET, $want_encoding);
			}
		}
		return $subject;
	}
}


$smarty->assign('use_fix', $use_fix);
$smarty->assign('encoding', mb_internal_encoding());
$smarty->assign('name', 'Grünewald');	# a German name in 8 bit encoding
$smarty->display(__FILE__);


__HALT_COMPILER();
?>================ Start of template ====================
PHP: {$smarty.const.PHP_VERSION}
Smarty: {$smarty.version}
Using {if $use_fix }FIXED{else}ORIGINAL{/if} smarty_mb_str_replace.

The text below may be displayed incorrectly if your terminal is not configured to use {$encoding} encoding, but that's beside the point of this test.
$name: {$name}
{$new_name=$name|replace:'wald':'stürm'}
$name using replace modifier: {$new_name}

{if !$new_name}Test FAILED{/if}

