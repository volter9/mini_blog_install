<?php

/**
 * Action initialize
 */
function action_init () {
    unset($_SESSION['errors']);
    
    load_language();
}

/**
 * GET method of database action
 * 
 * @param array $input
 * @param array $errors
 */
function action_get ($input, array $errors = array()) {
    $database = session('database');
    
    if (!$input) {
        // Default values
        $input = array(
            'host'     => 'localhost',
            'username' => 'root'
        );
    }
    
    $input = array_merge($input, $database ? $database : array());
    
    view('layout', array(
        'errors'      => $errors,
        'input'       => $input,
        'title'       => lang('database.title'),
        'fields'      => lang('database.form'),
        'description' => lang('database.description'),
        'url'         => url('?route=database'),
        'view'        => 'views/form',
        'prefix'      => 'database.fields',
        'step'        => 2
    ));
}

/**
 * POST method of database action
 * 
 * @param array $input
 */
function action_post ($input) {
    $keys  = array('database', 'host', 'username', 'password');
    $input = array_extract($input, $keys);
    
    try {
        array_pop($keys);
        
        required_fields($input, $keys, 'database.errors.empty');
        
        check_database(create_connection($input), $input['database']);
    }
    catch (Exception $e) {
        return action_get($input, array(lang($e->getMessage())));
    }
    
    session('database', $input);
    
    redirect('?route=admin');
}