<?php

if (!isset($modx)) {
    require_once dirname(dirname(dirname(__FILE__))) . '/revolution/config.core.php';
    require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
    $modx= new modX();
    $modx->initialize('web');
    $modx->setLogLevel(modX::LOG_LEVEL_ERROR);
}

$path = $modx->getOption('sopablackout.core_path',null,$modx->getOption('core_path').'components/sopablackout/');
if (isset($_GET['resetskip'])) unset($_SESSION['hasbeencensored']);

/* When we want to black out */
$from = strtotime($modx->getOption('start',$scriptProperties,'01/13/2012 08:00:00'));
$until = strtotime($modx->getOption('until',$scriptProperties,'01/14/2012 20:00:00'));
$skip = false;
if (isset($_SESSION['hasbeencensored']) && ((time() - 10800) < $_SESSION['hasbeencensored'])) $skip = true;

/* If we're between the from & until let's do some stuff */
if ((time() > $from) && (time() < $until) && !$skip && ($modx->context->get('key') != 'mgr')) {
    /* First set the right headers */
    $secondsleft = $until - time() + 600;
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: '.$secondsleft);
    
    /* Then load the template */
    $tpl = $modx->getOption('template',$scriptProperties,'simple-message');
    
    $chunk = $modx->newObject('modChunk');
    $chunk->setContent(file_get_contents($path . 'elements/templates/' . $tpl.'.tpl'));
    $result = $chunk->process(array(
        'timeleft' => $secondsleft,
        'timeleftformatted' => strftime('%H:%M',$secondsleft),
        'request' => $_SERVER['REQUEST_URI'],
    ));
    
    $_SESSION['hasbeencensored'] = time();
    die($result);
}
return '';
