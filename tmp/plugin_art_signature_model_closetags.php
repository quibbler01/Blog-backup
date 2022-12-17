<?php
function CloseTags($html)
{
    $html = preg_replace('/<[^>]*$/', '', $html);
    preg_match_all('#<([a-z1-6]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
    $opentags = $result[1];
    preg_match_all('#</([a-z1-6]+)>#iU', $html, $result);
    $closetags  = $result[1];
    $len_opened = count($opentags);
    if (count($closetags) == $len_opened) {
        return $html;
    }
    $opentags = array_reverse($opentags);
    $sc = array('br', 'input', 'img', 'hr', 'meta', 'link');
    for ($i = 0; $i < $len_opened; $i++) {
        $ot = strtolower($opentags[$i]);
        if (!in_array($opentags[$i], $closetags) && !in_array($ot, $sc)) {
            $html .= '</' . $opentags[$i] . '>';
        } else {
            unset($closetags[array_search($opentags[$i], $closetags)]);
        }
    }
    return $html;
}