<?php
//include a few core things
include_once $_SERVER['DOCUMENT_ROOT'] . "/core/ClassHandling/ResourceLocator.php";
$locator = new ResourceLocator();
include_once $_SERVER['DOCUMENT_ROOT'] . "/core/Render/Renderer.php";
include_once $locator->find("GlobalFunctions", "other");
include_once $locator->find("AbstractController");
include_once $locator->find("AbstractAuthenticator");
include_once $locator->find("Page");
include_once $locator->find("Snippet");
include_once $locator->find("TextSnippet");

//nclude user thingser
//set an error handler for finding user defined classes
set_error_handler(function()
{
    global $locator;
    echo "Unable to find Authenticator please make it at: " . $locator->find("Authenticator");
    exit();
});
restore_error_handler();
include_once $locator->find("Authenticator");
set_error_handler(function()
{
    global $locator;
    echo "Unable to find Settings please make it at: " . $locator->find("Settings");
    exit();
});
include_once $locator->find("Settings");
restore_error_handler();

session_start();

$auth = new Authenticator();
$auth->doAuthentication();

//get the request uri
$uri = $_SERVER['REQUEST_URI'];

//remove any get variables that are part of the uri
$parts = explode("?", $uri);
$uri = $parts[0];

$controller = "index";
$function   = "index";
$args       = array();

//break up the request into parts
$parts = explode("/", $uri);
$parts = array_slice($parts, 1);
if(count($parts) != 0 && $parts[0] != "")
{
    $controller = $parts[0];
    if(count($parts) > 1 && $parts[1] != "")
        $function = $parts[1];
    if(count($parts) > 2 && $parts[2] != "")
        $args = array_slice($parts, 2);
}

//find the controller
$controller = makeControllerName($controller);
set_error_handler(function()
{
    global $controller;
    echo "Unable to find controller: \"" . $controller . "\"";
    exit();
});
include_once $locator->find($controller);
restore_error_handler();

//instanciate the controller
$cont = eval(" return new " . $controller . "();");

//make sure it has the function
if(!method_exists($cont, $function))
{
    echo "Controller: \"" . $controller . "\" has no function: " . $function . "()";
    return;
}

//add any objects need by the controller
$cont->Auth = $auth;

$result = eval('return $cont->' . $function . '($args);');

$debug = null;
if(DEBUG)
{
    //generate some debug info
    $debug_args = "\narray\n(\n";
    foreach($args as $arg)
        $debug_args .= "\t" . $arg . "\n";
    $debug_args .= ")";
    $debug = new TextSnippet('
        <div class="debug" style="color:black;background-color:white;">
            <h3>Debug Info</h3>
            Controller: ' . $controller . '<br/>
            Function: ' . $function . '<br/>
            Args: <pre>' . $debug_args . '</pre>
            Uri: ' . $uri . '<br/>
        </div>'
    );
    $debug->setDontFilter(true);
}

//if result is a page, pass it to the page renderer
if($result != null || is_array($result))
{
    //see if the conection is https
    $is_https = false;
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
        $is_https = true;
    
    //see if the result is a redirect request
    if(is_array($result))
    {
        $new_url = "";
        if($is_https)
            $new_url = "https://";
        else
            $new_url = "http://";
        $new_url .= $_SERVER['HTTP_HOST'] . '/';
        if(isset($result['controller']))
            $new_url .= $result['controller'];
        else if(isset($result['function']))
            $new_url .= $controller;
        if(isset($result['function']))
            $new_url .= '/' . $result['function'];
        
        header('Location: ' . $new_url);
        exit();
    }
    //check if the page should be https
    if(!$is_https && USING_SSL)
    {
        if($auth->isLoggedIn() || $result->isForceSSL())
        {
            $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header('Location: https://' . $url);
            exit();
        }
    }
    $ren = new Renderer();
    $ren->gen($result, $debug);
}

//helper functions

function makeControllerName($name)
{
    $out = "";
    $first = substr($name, 0, 1);
    $out .= strtoupper($first);
    $out .= substr($name, 1) . "Controller";
    return $out;
}

?>
