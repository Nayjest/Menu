<?php
namespace Nayjest\Menu;

use Request;
use Route;

class Link
{
    public function make($route, $text, $attributes = [], $qs = [])
    {
        $qs = empty($qs) ? null : http_build_query($qs);
        if (is_array($route)) {

            if (count($route) === 1) {
                array_push($route, []);
            }
            $url = route($route[0], $route[1]) . (is_null($qs) ? '' : '?' . $qs);
            $currentRoute = Route::currentRouteName();
            $in_act_url = $currentRoute ? app('url')->route($currentRoute, $route[1], false) : '';
            if (
                ($currentRoute == $route[0])
                and
                (!$currentRoute or strpos('/' . Request::path(), $in_act_url) !== false)
            ) {

                $active = "class = 'active'";
            } else {
                $active = '';
            }
        } else {
            $url = $route . (is_null($qs) ? '' : '?' . $qs);
            $rp = Request::path();
            if (strlen($rp) > 1 and strpos('/' . $rp, $url) !== false) {
                $active = "class = 'active'";
            } else {
                $active = '';
            }
        }
        $tag_open = "<a href=\"{$url}\"";
        foreach ($attributes as $name => $value) {
            $tag_open .= "$name=\"$value\" ";
        }
        $tag_open .= '>';
        $tag_close = '</a>';
        return "\n\t\t\t<li {$active}>{$tag_open}{$text}{$tag_close}</li>";
    }
}