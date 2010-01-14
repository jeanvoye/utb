<?php
class shareGraphicView extends ezcMvcView
{
	public $contentTemplate;

	function createZones( $layout )
	{
		$zones = array();
		$zones[] = new ezcMvcPhpViewHandler( 'potato', '../templates/graphic.php' );
		return $zones;
	}
}
?>
