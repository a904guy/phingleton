<?php

// Singleton Interface

function o($obj) // Object Overloader, Singleton Factory
{
    global $_CODE;
    
    $vars = func_get_args();
    
    $obj = array_shift($vars);
    
    $tmp = func_get_args();
    
    $key_data = array_pop($tmp);
    
    $key = crc32(@json_encode($key_data).$obj);
    
    $vars = func_clean_args($vars);
    
    if(array_key_exists($key,$_CODE['obj'])) return $_CODE['obj'][$key];
    
    if(class_exists($obj)) {
        $class = new ReflectionClass($obj);
        
        if($class->hasMethod('__construct')) // php5.3 hack until we move onto 5.4 and newInstanceWithoutConstructor is available
        {
            $_CODE['obj'][$key] = $class->newInstanceArgs($vars);
        }else{
            $_CODE['obj'][$key] = $class->newInstance();
        }
        return $_CODE['obj'][$key];
    }
    
    return null; // For attempted access to chrooted objects happen
}

function func_clean_args($arr)
{
    $return = array();
    foreach($arr as $k) {foreach($k as $v) { $return[] = $v; }}
    return $return;
}

class singleton // Create singleton functions for each global object. Bit of a hack, but a sweet sweet juicy hack.
{
    function __construct()
    {
        $path = getPath('obj/');
        foreach(explode("\n",trim(`find {$path}* -type f"`)) as $file)
        {
            $name = basename(str_replace(array('.class.php',$path),'',$file));
            if(!function_exists($name)) eval("function {$name}() { \$vars = array_values(func_get_args()); return (count(\$vars) > 0) ? o(__FUNCTION__,\$vars) : o(__FUNCTION__); }");
        }
    }
}
