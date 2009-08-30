<?php
class shareAtomView extends ezcMvcView
{
	public $contentTemplate;

	function createZones( $layout )
	{
		$zones = array();
		$zones[] = new ezcMvcFeedViewHandler( 'content', NULL, 'atom' );
		return $zones;
	}
}
?>
