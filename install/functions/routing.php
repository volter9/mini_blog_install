<?php

/**
 * Routing
 * 
 * @param string $action
 * @param array $parameters
 */
function route ($action, array $parameters) {
    $file = sprintf('%s/install/actions/%s.php', basepath(), $action);
    
    if (!file_exists($file)) {
        throw new Exception("File '$file' doesn't exists!");
    }
    
    require $file;
    
    $action = sprintf('action_%s', method());
    $init = 'action_init';
    
    function_exists($init) and $init($parameters);
    function_exists($action) and $action($parameters);
}

/**
 * Get request method
 * 
 * @return string
 */
function method () {
    return strtolower(array_get($_SERVER, 'REQUEST_METHOD', 'GET'));
}

/**
 * Get base URL
 * 
 * @param string $base
 * @param string $root
 * @return string
 */
function baseurl ($base = null, $root = null) {
    $base = trim($base ? $base : mb_basepath(), '/');
    $root = trim($root ? $root : document_root(), '/');
    $lenght = strlen($root);

    return $base === $root ? '' : trim(substr($base, $lenght), '/');
}

/**
 * Get path to relative to base URL
 * 
 * @param string $file
 * @return string
 */
function url ($file = '') {
    $url = sprintf('%s/%s', baseurl(), $file);
    $url = ltrim($url, '/');
    
    return "/$url";
}

/**
 * Redirect to URL
 * 
 * @param string $url
 */
function redirect ($url = '') {
    $url = url($url);
    
    header("Location: $url") and exit;
}