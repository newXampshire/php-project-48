<?php

function warningToException($errno, $errstr, $errfile, $errline)
{
    throw new Exception($errstr . " on line " . $errline . " in file " . $errfile);
}

set_error_handler("warningToException", E_WARNING);
