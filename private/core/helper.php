<?php

// remain value after submit
function get_var($key)
{
    if(isset($_POST[$key])) {
        return $_POST[$key];
    }
    return '';
}

// escape html
function esc($var)
{
    return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
}