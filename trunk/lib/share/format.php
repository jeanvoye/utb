<?php
class shareFormat
{
	public static function substituteLinksInMessages( $m )
	{
		foreach( $m as &$msg )
		{
			$msg['contents'] = self::substituteLink( $msg['contents'] );
		}
		return $m;
	}

	public static function formatMessages( $m )
	{
		foreach( $m as &$msg )
		{
			$msg['contents'] = self::substituteLink( $msg['contents'] );
			self::formatMessage( $msg );
		}
		return $m;
	}

	public static function formatMessage( &$msg, $html = true )
	{
		$msg['contents'] = htmlspecialchars( $msg['contents'] );
		$msg['textStatus'] = $msg['contents'];

		$msg['contents'] = self::addHtmlLinks( $msg['contents'] );
		$msg['contents'] = self::addPersonLinks( $msg['contents'] );
		$msg['contents'] = self::addTagLinks( $msg['contents'] );
	}

	public static function callbackAddTagLink( $matches )
	{
		$installDomain = $GLOBALS['ini']->getSetting( 'TheWire', 'installDomain' );
		$tag = strtolower( $matches[1] );
		return "<a class='tag' href='http://{$installDomain}/tag/{$tag}'>{$matches[0]}</a>";
	}

	public static function addTagLinks( $text )
	{
		return preg_replace_callback( '@#([a-z][a-z0-9_-]+)@i', array( 'shareFormat', 'callbackAddTagLink' ), $text );
	}

	public static function addPersonLinks( $text )
	{
		$installDomain = $GLOBALS['ini']->getSetting( 'TheWire', 'installDomain' );
		return preg_replace( '/@([a-z]+)/i', "<a class='person' href='http://{$installDomain}/person/\\1'>\\0</a>", $text );
	}

	public static function addHtmlLinks( $text )
	{
		$bugLinkFormat = str_replace( '%nr', '\\4', $GLOBALS['ini']->getSetting( 'formats', 'bugLinkFormat' ) );
		$installDomain =                            $GLOBALS['ini']->getSetting( 'TheWire', 'installDomain' );
		$text = preg_replace( '@((issue)|(bug))\s#([0-9]+)@i', "<a class='html' href='{$bugLinkFormat}'>\\0</a>", $text );
		$text = preg_replace( "@(http://{$installDomain}/link/[0-9a-z]+)@i", "<a class='html' href='\\1'>\\1</a>", $text );
		return $text;
	}

	public static function substituteLink( $text )
	{
		$installDomain = $GLOBALS['ini']->getSetting( 'TheWire', 'installDomain' );
		$text = preg_replace( '@{LINK,([0-9a-z]+)}@', "http://{$installDomain}/link/\\1", $text );
		return $text;
	}
}
