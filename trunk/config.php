<?php
//ini_set( 'include_path', ini_get( 'include_path' ) . ':/htdocs/ezc_trunk/:.' );
ini_set( 'session.gc_maxlifetime', 864000 );
require 'Base/src/ezc_bootstrap.php';

// ezcDbInstance::set( ezcDbFactory::create( 'sqlite://'. dirname( __FILE__ ) . '/share.sqlite' ) );
ezcDbInstance::set( ezcDbFactory::create( 'mysql://root:cawerks@127.0.0.1/UnTrucBien' ) );

$options = new ezcBaseAutoloadOptions( array( 'debug' => true ) );
ezcBase::setOptions( $options );
ezcBase::addClassRepository( dirname( __FILE__ ) . '/lib/share', null, 'share' );
ezcBase::addClassRepository( dirname( __FILE__ ) . '/lib/debug', null, 'debug' );

$log = ezcLog::getInstance();
$mapper = $log->getMapper();
$writer = new ezcLogUnixFileWriter( "/tmp", "thewire-web.log" );
$filter = new ezcLogFilter;
$rule = new ezcLogFilterRule( $filter, $writer, true );
$mapper->appendRule( $rule );

$reader = new ezcConfigurationIniReader();
$reader->init( dirname( __FILE__ ), 'settings' );
$ini = $reader->load();

if ( $ini->getBoolSetting( 'DevelopmentSettings', 'Debug' ) )
{
    ezcBase::setRunMode( ezcBase::MODE_DEVELOPMENT );
    $debugHandler = ezcDebug::getInstance();
    $debugHandler->setOutputFormatter( new debugHtmlFormatter() );
    debugLogger::setHandler( $debugHandler );
}

$tc = ezcTemplateConfiguration::getInstance();
$tc->templatePath = dirname( __FILE__ ) . '/templates';
$tc->compilePath = dirname( __FILE__ ) . '/cache';

$tc->addExtension( "shareTemplateFunctions" );
?>
