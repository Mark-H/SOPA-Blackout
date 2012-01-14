<?php
$plugins = array();

/* create the plugin object */
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id',1);
$plugins[0]->set('name','SOPA Blackout');
$plugins[0]->set('description','Will blackout your site on the 18th of January to show you do not approve the SOPA and PIPA bills.');
$plugins[0]->set('plugincode', getSnippetContent($sources['plugins'] . 'sopablackout.include.php'));
$plugins[0]->set('category', 0);
$properties = array(
    array(
        'name' => 'start',
        'desc' => 'Start date in format d/m/Y H:S',
        'type' => 'textfield',
        'value' => '01/18/2012 08:00:00',
    ),
    array(
        'name' => 'until',
        'desc' => 'Ending date in format d/m/Y H:S',
        'type' => 'textfield',
        'value' => '01/18/2012 20:00:00',
    ),
);
$plugins[0]->setProperties($properties);

$events = include 'events.sopablackout.php';
if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' Plugin Events for SOPA Blackout.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find plugin events for SOPA Blackout!');
}
unset($events);

return $plugins;

?>
