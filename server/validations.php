<?php
function isText($var)
{
    return preg_match('/^[a-zA-Z áéíóúÁÉÍÓÚ]+$/', $var);
}

function isCode($var)
{
    return preg_match('/^[PROD]{4}[0-9]{5}+$/', $var);
}
