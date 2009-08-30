<?php
class sharePersonController extends ezcMvcController
{
	public function doList()
	{
		$q = ezcDbInstance::get()->createSelectQuery();
		// select last 25 updates
		$q->select( 'message.id as id, user_id, date, status, fullname, source' )
		  ->from( 'message' )
		  ->innerJoin( 'user', 'message.user_id', 'user.id' )
		  ->orderBy( 'date', ezcQuerySelect::DESC )->limit( 25 );
		$s = $q->prepare();
		$s->execute();
		$r = $s->fetchAll();

		// collect all IDs
		$ids = array();
		$msgs = array();
		$user_idIndex = array();
		$user_ids = array();
		foreach( $r as $message )
		{
			$ids[] = $message['id'];
			$msgs[$message['id']] = $message;

			$user_idIndex[$message['id']][$message['user_id']] = true;
			$user_ids[$message['user_id']] = $message['user_id'];
		}
		sort( $user_ids );

		// loop over all messages and build the user_id lists.
		$user_idList = array();
		foreach( $msgs as $msg )
		{
			foreach( $user_ids as $user_id )
			{
				if ( isset( $user_idIndex[$msg['id']][$user_id] ) )
				{
					if ( !isset( $user_idList[$user_id] ) )
					{
						$user_idList[$user_id] = array( $msg );
					}
					else
					{
						$user_idList[$user_id][] = $msg;
					}
				}
			}
		}

		foreach( $user_idList as &$peruser_id )
		{
			$peruser_id = shareFormat::formatMessages( $peruser_id );
		}

		$res = new ezcMvcResult;
		$res->variables['updates'] = $user_idList;
		return $res;
	}

	public function doDisplay()
	{
		$user_id = $this->personName;
		$q = ezcDbInstance::get()->createSelectQuery();
		// fetch username
		$q->select( '*' )
		  ->from( 'user' )
		  ->where( $q->expr->eq( 'id', $q->bindValue( $user_id ) ) );
		$s = $q->prepare();
		$s->execute();
		$r = $s->fetchAll();

		if ( count( $r ) == 0 )
		{
			$res = new ezcMvcResult;
			$res->status = new ezcMvcExternalRedirect( $this->router->generateUrl( 'personIndex' ) );
			return $res;
		}

		$user_name = $r[0]['fullname'];

		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( 'message.*, user.fullname' )
		  ->from( 'message' )
		  ->innerJoin( 'user', 'message.user_id', 'user.id' )
		  ->where( $q->expr->eq( 'user_id', $q->bindValue( $user_id ) ) )
		  ->orderBy( 'date', ezcQuerySelect::DESC )->limit( 25 );
		$s = $q->prepare();
		$s->execute();
		$messages = shareFormat::formatMessages( $s->fetchAll() );

		$res = new ezcMvcResult;
		$res->variables['user_id'] = $user_id;
		$res->variables['user_name'] = $user_name;
		$res->variables['updates'] = $messages;
		return $res;
	}

	public function doStatus()
	{
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( 'user_id, max(message.id), fullname, message.*' )
		  ->from( 'message' )
		  ->leftJoin( 'user', 'message.user_id', 'user.id' )
		  ->groupBy( 'user_id' )
		  ->orderBy( 'fullname' );
		$s = $q->prepare();
		$s->execute();
		$messages = shareFormat::formatMessages( $s->fetchAll() );

		$res = new ezcMvcResult;
		$res->variables['updates'] = $messages;
		return $res;
	}
}
?>
