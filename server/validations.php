<?php
function isText($var)
{
    return preg_match('/^[a-zA-Z áéíóúÁÉÍÓÚ]+$/', $var);
}

function isCode($var)
{
    return preg_match('/^[P][R][O][D][0-9]{5}+$/', $var);
}
