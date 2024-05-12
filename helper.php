<?php

/**
 * Get the base path
 * 
 * @param string $path
 * @return string
 */
function basePath($path = ''){
    return __DIR__ . '/' . $path;
}

/**
 * Load a view
 * 
 * @param string $name
 * @return void
 */
function loadView($name,$data = []){
    $viewPath =  basePath("App/views/{$name}.view.php");
    if(file_exists($viewPath)){
        extract($data);
        require $viewPath;
    }else{
        echo "view '{$name} not found'";
    }
}

/**
 * Load a partials
 * 
 * @param string $name
 * @return void
 */
function loadPartial($name,$date = [] ){
    $partialPass =  basePath("App/views/partials/{$name}.php");
    if(file_exists($partialPass)){
        extract($date);
        require $partialPass;
    }else{
        echo "Partial '{$name} not found'";
    }
}

/**
 * Inspeect value
 * 
 * @param mixed $value
 * @return void
 */
function inspect($value){
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

/**
 * Inspeect value and die
 * 
 * @param mixed $value
 * @return void
 */
function inspectAndDie($value){
    echo '<pre>';
    die(var_dump($value));
    echo '</pre>';
}

/**
 * Format salary
 * 
 * @param string $salary
 * @return string Formatted Salary
 */
function formatSalary($salary){
    return '$'.number_format(floatval($salary));
}

/**
 * Sanatize Data
 * 
 * @param string $dirty
 * @return string
 */
function sanitize($dirty) {
    return filter_var(trim($dirty),FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * Redirect to a given url
 * 
 * @param string $url
 * @return void
 */
function redirect($url){
    header("Location: {$url}");
    exit;
}