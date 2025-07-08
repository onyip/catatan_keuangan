<?php
//persingkat pemanggilan input
if (!function_exists('_input')) {
    function _input()
    {
        return \Config\Services::request();
    }
}
