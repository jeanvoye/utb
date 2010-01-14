<?php
class shareTagController extends ezcMvcController
{
	public function doList()
	{
		$q = ezcDbInstance::get()->createSelectQuery();
		// select last 25 updates with a tag
		$q->select( 'DISTINCT message.id as id, user_id, date, status, fullname, source' )
		  ->from( 'message' )->leftJoin( 'message_tag', 'message.id', 'message_tag.message_id' )
		  ->innerJoin( 'user', 'message.user_id', 'user.id' )
		  ->orderBy( 'date', ezcQuerySelect::DESC )->limit( 25 );
		$s = $q->prepare();
		$s->execute();
		$r = $s->fetchAll();

		// collect all IDs
		$ids = array();
		$msgs = array();
		foreach( $r as $message )
		{
			$ids[] = $message['id'];
			$msgs[$message['id']] = $message;
		}

		// select tags per message
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( '*' )
		  ->from( 'message_tag' )
		  ->innerJoin( 'message', 'message.id', 'message_tag.message_id' )
		  ->where( $q->expr->in( 'message_id', $ids ) );
		$s = $q->prepare();
		$s->execute();

		// massage tags
		$tagIndex = array();
		$tags = array();
		foreach( $s->fetchAll() as $res )
		{
			$tagIndex[$res['message_id']][$res['tag']] = true;
			$tags[strtolower($res['tag'])] = $res['tag'];
		}
		sort( $tags );

		// loop over all messages and build the tag lists.
		$tagList = array();
		foreach( $msgs as $msg )
		{
			foreach( $tags as $tag )
			{
				if ( isset( $tagIndex[$msg['id']][$tag] ) )
				{
					if ( !isset( $tagList[$tag] ) )
					{
						$tagList[$tag] = array( $msg );
					}
					else
					{
						$tagList[$tag][] = $msg;
					}
				}
			}
		}

		foreach( $tagList as &$perTag )
		{
			$perTag = shareFormat::formatMessages( $perTag );
		}

		$res = new ezcMvcResult;
		$res->variables['updates'] = $tagList;
		return $res;
	}

	public function doDisplay()
	{
		$q = ezcDbInstance::get()->createSelectQuery();
		$tag = $this->tagName;
		$q->select( 'message.*, user.fullname' )
		  ->from( 'message' )
		  ->innerJoin( 'message_tag', 'message.id', 'message_tag.message_id' )
		  ->innerJoin( 'user', 'message.user_id', 'user.id' )
		  ->where( $q->expr->eq( 'tag', $q->bindValue( $tag ) ) )
		  ->orderBy( 'date', ezcQuerySelect::DESC )->limit( 25 );
		$s = $q->prepare();
		$s->execute();

		$messages = shareFormat::formatMessages( $s->fetchAll() );

		$res = new ezcMvcResult;
		$res->variables['tag'] = $tag;
		$res->variables['updates'] = $messages;
		$res->variables['rss'] = '/tag/' . $tag;
		return $res;
	}
}
?>
