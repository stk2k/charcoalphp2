<?php

function smarty_modifier_mb_truncate($string, $length = 80, $etc = '…')
{
  if ($length == 0)
    return '';
  if (mb_strlen($string,"UTF-8") > $length) {
    $string = mb_substr($string, 0, $length,"UTF-8");
    return $string.$etc;
  } else {
    return $string;
  }
}

?>