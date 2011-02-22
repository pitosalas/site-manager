<?php

/**
 * Joins elements of array ('array') together with separator ('separator').
 */ 
function smarty_function_implode($params, &$smarty)
{
  $sep = ', ';
  if (isset($params['separator'])) $sep = $params['separator']; 
  
  $array = array();
  if (isset($params['array'])) $array = $params['array'];
  
  if (isset($params['quotes']) && $params['quotes'] == 'true')
  {
    for ($i = 0; $i < count($array); $i++) $array[$i] = '"' . $array[$i] . '"';
  }
  
  return implode($sep, $array);
}
?>
