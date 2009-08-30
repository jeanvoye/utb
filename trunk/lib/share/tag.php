<?php
class shareTagController extends ezcMvcController
{
	public function createResult()
	{
		switch( $this->action )
		{
			case 'list':
			case 'display':
				return $this->{$this->action}();
		}
	}

	private function list()
	{
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
	}
}
?>
