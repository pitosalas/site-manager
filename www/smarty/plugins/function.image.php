<?php
// BlogBridge Library
// Copyright (c) 2006 Salas Associates, Inc.  All Rights Reserved.
//
// Use, modification or copying prohibited unless appropriately licensed
// under an express agreement with Salas Associates, Inc.
//
// Contact: R. Pito Salas
// Mail To: support@blogbridge.com
//
// $Id: function.image.php,v 1.1 2007/01/09 10:32:38 alg Exp $
//

/**
 * Returns image URL.
 * @param person - person object, or
 *        pic    - picture file name.
 */
function smarty_function_image($params, &$smarty)
{
    $url = IMAGES_URL;
    
    if (isset($params['person']))
    {
        $person = $params['person'];
        $url = IMAGES_PEOPLE_URL . '/' . $person->id . '.img';
    } else if (isset($params['pic']))
    {
        $url = _image($params['pic']);
    }
    
    if (isset($params['suffix'])) $url .= $params['suffix'];
    
    return $url;
}

function _image($pic)
{
    return IMAGES_URL . '/' . $pic;
}

function smarty_function_css_image($params, &$smarty)
{
	return _css_image($params['pic'], isset($params['title']) ? $params['title'] : '');
}

function _css_image($pic, $title = '')
{
	return '<img border="0" class="' . $pic . '" src="' . _image('spacer.gif') . '" title="' . $title . '">';
}

?>
