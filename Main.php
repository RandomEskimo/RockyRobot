<?php
//include a few core things
include_once $_SERVER['DOCUMENT_ROOT'] . "/core/ClassHandling/ResourceLocator.php";
$locator = new ResourceLocator();
include_once $_SERVER['DOCUMENT_ROOT'] . "/core/Render/Renderer.php";
$locator->inc("GlobalFunctions", "other");
$locator->inc("AbstractController");
$locator->inc("AbstractAuthenticator");
$locator->inc("Page");
$locator->inc("Snippet");
$locator->inc("DebugSnippet");
$locator->inc("TextSnippet");
$locator->inc("RequestHandler");

$file; //global for error reporting of included files

//this is the current version
define('__VERSION__', '0.1alpha (Brad)');

//nclude user things
//set an error handler for finding user defined classes
set_error_handler(function()
{
    global $locator;
    echo "Unable to find Authenticator please make it at: " . $locator->find("Authenticator");
    exit();
});
include_once $locator->find("Authenticator");
restore_error_handler();

set_error_handler(function()
{
    global $locator;
    echo "Unable to find Settings please make it at: " . $locator->find("Settings");
    exit();
});
include_once $locator->find("Settings");
restore_error_handler();

set_error_handler(function()
{
    global $locator;
    echo "Unable to find ErrorController please make it at: " . $locator->find("ErrorController");
    exit();
});
include_once $locator->find("ErrorController");
restore_error_handler();

session_start();

$auth = new Authenticator();
$auth->doAuthentication();

//get the request uri
$uri = $_SERVER['REQUEST_URI'];

//remove any get variables that are part of the uri
$parts = explode("?", $uri);
$uri = $parts[0];

$rh = new RequestHandler($uri);
$result = $rh->handleRequest();

if(DEBUG)
{
    //generate some debug info
    $debug_args = "\narray\n(\n";
    foreach($rh->getArgs() as $arg)
        $debug_args .= "\t" . $arg . "\n";
    $debug_args .= ")";
    DebugSnippet::addInfo('RockyRobot version', __VERSION__);
    DebugSnippet::addInfo('url info',
            'Controller: ' . $rh->getController() . '<br/>
            Function: ' . $rh->getFunction() . '<br/>
            Args: <pre>' . $debug_args . '</pre>
            Uri: ' . $uri . '<br/>'
    );
    DebugSnippet::addInfo('result', $result);
    DebugSnippet::addInfo('GET', $_GET);
    DebugSnippet::addInfo('POST', $_POST);
    DebugSnippet::addInfo('SESSION', $_SESSION);
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
        if(isset($result['url']))
        {
            $new_url = $result['url'];
        }
        if(isset($result['referer']) && $result['referer'] == true && 
            isset($_SESSION['referer']) && $_SESSION['referer'] != '')
        {
            $new_url = $_SESSION['referer'];
        }
        else
        {
            if($is_https)
                $new_url = "https://";
            else
                $new_url = "http://";
            $new_url .= $_SERVER['HTTP_HOST'] . '/';
            if(isset($result['controller']))
                $new_url .= $result['controller'];
            else if(isset($result['function']))
                $new_url .= $rh->getRawController();
            if(isset($result['function']))
            {
                $new_url .= '/' . $result['function'];
                if(isset($result['args']))
                {
                    foreach($result['args'] as $arg)
                        $new_url .= '/' . $arg;
                }
            }
        }
        if(isset($result['flash']))
        {
            $_SESSION['flash_message'] = $result['flash'];//this will be picked up by the next Page to be displayed
        }                                                 //where it can then be displayed to the user
        
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
    $ren->gen($result);
    if(isset($_SERVER['HTTP_REFERER']))
        $_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
    else
        $_SESSION['referer'] = '';
}

?>
