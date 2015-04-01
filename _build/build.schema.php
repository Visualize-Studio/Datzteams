<?php
/**
 * Build Schema script
 *
 * @package datzteams
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

require_once dirname(__FILE__).'/build.config.php';
include_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx= new modX();
$modx->initialize('mgr');
$modx->loadClass('transport.modPackageBuilder','',false, true);
echo '<pre>'; /* used for nice formatting of log messages */
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget('ECHO');

$root = dirname(dirname(__FILE__)).'/';
$sources = array(
    'root' => $root,
    'core' => $root.'core/components/datzteams/',
    'model' => $root.'core/components/datzteams/model/',
    'schema' => $root.'core/components/datzteams/model/schema/',
    'schema_file' => $root.'core/components/datzteams/model/schema/datzteams.mysql.schema.xml',
    'assets' => $root.'assets/components/datzteams/',
);
$manager= $modx->getManager();
$generator= $manager->getGenerator();

if (!is_dir($sources['model'])) {
    $modx->log(modX::LOG_LEVEL_ERROR,'Model directory not found!');
    die();
}
if (!file_exists($sources['schema_file'])) {
    $modx->log(modX::LOG_LEVEL_ERROR,'Schema file not found!');
    die();
}
$generator->parseSchema($sources['schema_file'],$sources['model']);
$modx->addPackage('datzteams', $sources['model']); // add package to make all models available
$manager->createObjectContainer('datzTeamsTeams');
$manager->createObjectContainer('datzTeamsGames');
$manager->createObjectContainer('datzTeamsPlatforms');
$manager->createObjectContainer('datzTeamsPlayers');
$manager->createObjectContainer('datzTeamsMatch');
$manager->createObjectContainer('datzTeamsMatchPlayers');
$manager->createObjectContainer('datzTeamsMatchMaps');
$manager->createObjectContainer('datzTeamsGameMaps');
$manager->createObjectContainer('datzTeamsGameMode');

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

echo "\nExecution time: {$totalTime}\n";

exit ();
