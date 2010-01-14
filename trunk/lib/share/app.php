<?php
class shareApp
{
	static public function addFromWeb( $text )
	{
		$tags = array();
		$message = self::parseMessage( $text, $tags, $linkIds );

		if ( strlen( $message ) == 0 )
		{
			return;
		}
		$time = time();
		$source = 'web';
		$db = ezcDbInstance::get();

		$q = $db->createInsertQuery();
		$q->insertInto( 'message' )
		  //->set( 'user_id', $q->bindValue( $_SESSION['ezcAuth_id'] ) )
		  ->set( 'user_id', $q->bindValue( "admin" ) )
		  ->set( 'date', $q->bindValue( $time ) )
		  //->set( 'type', $q->bindValue( 1 ) )
		  //->set( 'source', $q->bindValue( 'web' ) )
		  ->set( 'contents', $q->bindValue( $message ));
		$s = $q->prepare();
		$s->execute();
		$lastId = $db->lastInsertId();

		self::updateTags( $lastId, $tags );
		// self::updateLinkIDs( $lastId, $linkIds );
		// self::sendUpdate( $lastId, $_SESSION['ezcAuth_id'], $time, $source, $message, $tags );
	}

	static private function updateLinkIDs( $lastId, $linkIds )
	{
		if ( count( $linkIds ) == 0 )
		{
			return;
		}
		$db = ezcDbInstance::get();
		$q = $db->createUpdateQuery();
		$q->update( 'links' )
		  ->set( 'message_id', $lastId )
		  ->where( $q->expr->in( 'id', $linkIds ) );
		$s = $q->prepare();
		$s->execute();
	}

	static private function updateTags( $lastId, $tags )
	{
		$db = ezcDbInstance::get();
		foreach( $tags as $tag )
		{
			$tag = strtolower( $tag );
			$q = $db->createInsertQuery();
			$q->insertInto( 'message_tag' )
			  ->set( 'message_id', $lastId )
			  ->set( 'tag', $q->bindValue( $tag ) );
			$s = $q->prepare();
			$s->execute();
		}
	}

	static public function handleJabberActions( $jabber_id, $text )
	{
		echo "- handleJabberActions\n";
		$db = ezcDbInstance::get();

		// find user from jabber id
		$q = $db->createSelectQuery();
		$q->select( 'id' )->from( 'user' )->where( $q->expr->eq( 'jabber', $q->bindValue( $jabber_id ) ) );
		$s = $q->prepare();
		$s->execute();
		$r = $s->fetchAll();
		if ( count ( $r ) == 0 )
		{
			return;
		}
		$from = $r[0]['id'];

		// check actions
		$text = trim( $text );
		if ( $text == 'stop' || $text == 'shut up' )
		{
			echo "- setting jabber to shut up\n";
			// set jabber pref to 0
			$q = $db->createUpdateQuery();
			$q->update( 'user_pref' )
			  ->set( 'jabber_type', $q->bindValue( 0 ) )
			  ->where( $q->expr->eq( 'user_id', $q->bindValue( $from ) ) );
			$s = $q->prepare();
			$s->execute();

			return true;
		}
		if ( $text == 'start' )
		{
			echo "- setting jabber to noisy\n";
			// set jabber pref to 1
			$q = $db->createUpdateQuery();
			$q->update( 'user_pref' )
			  ->set( 'jabber_type', $q->bindValue( 1 ) )
			  ->set( 'queued_since', $q->bindValue( 0 ) )
			  ->where( $q->expr->eq( 'user_id', $q->bindValue( $from ) ) );
			$s = $q->prepare();
			$s->execute();
			return true;
		}
		if ( $text == 'queue' )
		{
			$q = $db->createSelectQuery();
			$q->select( 'MAX(id) as max_id' )
			  ->from( 'message' );
			$s = $q->prepare();
			$s->execute();
			$r = $s->fetchAll();

			echo "- setting lst queue id to {$r[0]['max_id']}, and shutting up user\n";

			$q = $db->createUpdateQuery();
			$q->update( 'user_pref' )
			  ->set( 'jabber_type', $q->bindValue( 0 ) )
			  ->set( 'queued_since', $q->bindValue( $r[0]['max_id'] ) )
			  ->where( $q->expr->eq( 'user_id', $q->bindValue( $from ) ) );
			$s = $q->prepare();
			$s->execute();
			return true;
		}
		return false;
	}

	static public function addFromJabber( $jabber_id, $text )
	{
		echo "- addFromJabber\n";
		$db = ezcDbInstance::get();

		// find user from jabber id
		$q = $db->createSelectQuery();
		$q->select( 'id' )->from( 'user' )->where( $q->expr->eq( 'jabber', $q->bindValue( $jabber_id ) ) );
		$s = $q->prepare();
		$s->execute();
		$r = $s->fetchAll();
		if ( count ( $r ) == 0 )
		{
			return;
		}
		$from = $r[0]['id'];

		echo "- found user '$from'\n";

		$tags = array();
		$message = self::parseMessage( $text, $tags, $linkIds );
		$time = time();
		$source = 'jabber';

		$q = $db->createInsertQuery();
		$q->insertInto( 'message' )
		  ->set( 'user_id', $q->bindValue( $from ) )
		  ->set( 'date', $q->bindValue( $time ) )
		  ->set( 'type', $q->bindValue( 1 ) )
		  ->set( 'source', $q->bindValue( $source ) )
		  ->set( 'status', $q->bindValue( $message ) );
		$s = $q->prepare();
		$s->execute();
		$lastId = $db->lastInsertId();

		self::updateTags( $lastId, $tags );
		self::updateLinkIDs( $lastId, $linkIds );
		self::sendUpdate( $lastId, $from, $time, $source, $message, $tags );
	}

	static public function sendUpdate( $msgId, $user_id, $time, $source, $message, $tags )
	{
		self::sendUpdateJabber( $msgId, $user_id, $time, $source, $message, $tags );
	}

	static public function sendUpdateJabber( $msgId, $user_id, $time, $source, $message, $tags )
	{
		// find all users that have jabber on
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( 'jabber, jabber_type, jabber_expand_username' )
		  ->from( 'user_pref' )
		  ->leftJoin( 'user', 'user.id', 'user_pref.user_id' )
		  ->where( $q->expr->neq( 'jabber_type', 0 ) );
		if ( $source == 'jabber' )
		{
			$q->where( $q->expr->neq( 'user.id', $q->bindValue( $user_id ) ) );
		}
		$s = $q->prepare();
		$s->execute();
		$s = $s->fetchAll();

		foreach( $s as $user )
		{
			$q = ezcDbInstance::get()->createInsertQuery();
			$q->insertInto( 'jabber_work' )
			  ->set( 'jabber_id', $q->bindValue( $user['jabber'] ) )
			  ->set( 'message_id', $msgId );
			$s = $q->prepare();
			$s->execute();
		}
	}

	static public function parseMessage( $text, &$tags, &$linkIds )
	{
		$text = trim( $text );
		preg_match_all( '@#([a-z][a-z0-9_-]+)@i', $text, $matches );
		$tags = $matches[1];

		// do process links
		$linkIds = array();
		$db = ezcDbInstance::get();
		while ( preg_match( '
		(
			(?:^|[\s,.!?])
				(<)?
					(\[)?
						(\()?
							([\'"]?)
								(?P<url>https?://(?!dev\.thewire)[^\s]*?)
							 \4
						(?(3)\))
					(?(2)\])
				(?(1)>)
			[.,?!]?(?:\s|$)
		)xm', $text, $match ) )
		{
			$q = $db->createInsertQuery();
			$q->insertInto( 'links' )
			  ->set( 'link', $q->bindValue( $match['url'] ) );
			$s = $q->prepare();
			$s->execute();
			$lastId = $db->lastInsertId();
			$linkIds[] = $lastId;

			$text = str_replace( $match['url'], '{LINK,' . base_convert( $lastId, 10, 36 ) . '}', $text );
		}

		// trim content
		return substr( $text, 0, 255 );
	}

	static public function tagCloud()
	{
		// select count(tag), tag from message_tag group by tag order by tag;
		$q = ezcDbInstance::get()->createSelectQuery();
		$sq = $q->subSelect();

		$sq->select( 'MAX(id) - 400 as max' )
		   ->from( 'message ');
		$q->select( "count(tag) as cnt, tag" )
		  ->from( 'message_tag' )
		  ->where( $q->expr->gt( 'message_id', $sq ) )
		  ->groupBy( 'tag' )
		  ->orderBy( 'cnt', ezcQuerySelect::DESC )
		  ->limit( 30 );
		$s = $q->prepare();
		$s->execute();
		$tags = array();
		$highest = 0;
		foreach( $s as $tag )
		{
			if ( $tag['cnt'] > $highest )
			{
				$highest = $tag['cnt'];
			}
			$tags[strtolower( $tag['tag'] )] = $tag['cnt'];
		}
		ksort( $tags );
		return array( 'tags' => $tags, 'highest' => $highest );
	}

	static public function peopleCloud()
	{
		// select count(tag), tag from message_tag group by tag order by tag;
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( "count(user_id) as cnt, user_id" )
		  ->from( 'message' )
		  ->groupBy( 'user_id' )
		  ->orderBy( 'user_id' );
		$s = $q->prepare();
		$s->execute();
		$tags = array();
		$highest = 0;
		foreach( $s as $tag )
		{
			if ( $tag['cnt'] > $highest )
			{
				$highest = $tag['cnt'];
			}
			$tags[strtolower( $tag['user_id'] )] = $tag['cnt'];
		}
		return array( 'tags' => $tags, 'highest' => $highest );
	}

	static function fetchCurrentPrefs()
	{
		$prefs = array(
			'jabber_type' => false,
			'timezone' => 'Europe/Oslo',
		);

		// from prefs
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( "*" )
		  ->from( 'user_pref' )
		  ->where( $q->expr->eq( 'user_id', $q->bindValue( $_SESSION['ezcAuth_id'] ) ) );
		$s = $q->prepare();
		$s->execute();
		$r = $s->fetchAll();

		if ( isset( $r ) )
		{
			$prefs['jabber_expand_username'] = $r[0]['jabber_expand_username'] != 0;
			$prefs['jabber_type'] = $r[0]['jabber_type'] != 0;
			$prefs['http_refresh'] = $r[0]['http_refresh'] != 0;
		}

		// from user
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( "*" )
		  ->from( 'user' )
		  ->where( $q->expr->eq( 'id', $q->bindValue( $_SESSION['ezcAuth_id'] ) ) );
		$s = $q->prepare();
		$s->execute();
		$r = $s->fetchAll();

		$prefs['fullname'] = $r[0]['fullname'];
		$prefs['timezone'] = $r[0]['timezone'];
		$prefs['jabber_name'] = $r[0]['jabber'];
		$prefs['id'] = $r[0]['id'];

		return $prefs;
	}

	static function register( &$message )
	{
		$db = ezcDbInstance::get();
		$ini = $GLOBALS['ini'];
		$maxLength = $ini->getSetting( 'TheWire', 'maxUsernameLength' );
		$user_id = substr( preg_replace( '/[^a-z]/', '', $_POST['user_id'] ), 0, $maxLength );
		$fullname= ucwords( strtolower( preg_replace( '/[^a-zæøå ]/ui', '', $_POST['fullname'] ) ) );
		$jabber  = preg_replace( '/[^a-z.@]/', '', $_POST['jabber_name'] );

		// check if the user already exists.
		$q = $db->createSelectQuery();
		$q->select( 'id' )->from( 'user' )->where( $q->expr->eq( 'id', $q->bindValue( $user_id ) ) );
		$s = $q->prepare();
		$s->execute();
		$r = $s->fetchAll();

		if ( count( $r ) > 0 )
		{
			$message = "A user with username '{$user_id}' already exists.";
			return false;
		}

		// generate password
		mt_srand( base_convert( $user_id, 36, 10 ) * microtime( true ) );
		$a = base_convert( mt_rand(), 10, 36 );
		$b = base_convert( mt_rand(), 10, 36 );
		$password = substr( $b . $a, 1, 8 );;

		// create user
		$q = $db->createInsertQuery();
		$q->insertInto( 'user' )
		  ->set( 'id', $q->bindValue( $user_id ) )
		  ->set( 'jabber', $q->bindValue( $jabber ) )
		  ->set( 'fullname', $q->bindValue( $fullname ) )
		  ->set( 'password', $q->bindValue( md5( $password ) ) )
		  ->set( 'timezone', $q->bindValue( $_POST['timezone'] ) );
		$s = $q->prepare();
		$s->execute();

		// create user prefs
		$db = ezcDbInstance::get();
		$q = $db->createInsertQuery();
		$q->insertInto( 'user_pref' )
		  ->set( 'user_id', $q->bindValue( $user_id ) )
		  ->set( 'mail_type', $q->bindValue( 0 ) )
		  ->set( 'http_refresh', $q->bindValue( 1 ) )
		  ->set( 'jabber_type', $q->bindValue( 1 ) );
		$s = $q->prepare();
		$s->execute();

		// fetch settings
		$ini = $GLOBALS['ini'];
		$fromAddress = $ini->getSetting( 'TheWire', 'mailFrom' );
		$mailDomain = $ini->getSetting( 'TheWire', 'mailDomain' );
		$jabberUser = $ini->getSetting( 'jabber', 'user' );
		$jabberDomain = $ini->getSetting( 'jabber', 'domain' );
		$url = $ini->getSetting( 'TheWire', 'installDomain' );

		// send registration mail
		$m = new ezcMailComposer;
		$m->from = new ezcMailAddress( $fromAddress, 'The Wire' );
		$m->addTo( new ezcMailAddress( $user_id . '@' . $mailDomain, $fullname ) );
		$m->subject = 'Registration for The Wire';
		$m->plainText = <<<ENDT
Hello!

We've created a user account. Your password is:
	{$password}

You can now login at http://$url.
Please change your password to something you want it to be.

In order to make jabber integration work, please follow the following
procedure:

- Add the user "{$jabberUser}@{$jabberDomain}" to your jabber account.
- Send a message like "hello" to this new user.
- The jabber user will now authenticate you and ask for your
  authorization as well.
- Authorize the jabber user so that it can send messages to you.

regards,
The Wire.

ENDT;
		$m->build();

		$s = new ezcMailMtaTransport();
		$s->send( $m );

		$message = "A user account has been created, see your mail to find further instructions.";
		return true;
	}

	public static function updatePrefs()
	{
		// update user
		$q = ezcDbInstance::get()->createUpdateQuery();
		$q->update( 'user' );
		if ( isset( $_POST['timezone'] ) && in_array( $_POST['timezone'], timezone_identifiers_list() ) )
		{
			$q->set( 'timezone', $q->bindValue( $_POST['timezone'] ) );
		}
		if ( isset( $_POST['password'] ) && trim( $_POST['password'] ) !== '' )
		{
			$q->set( 'password', $q->bindValue( md5( trim( $_POST['password'] ) ) ) );
		}
		if ( isset( $_POST['fullname'] ) && trim( $_POST['fullname'] ) !== '' )
		{
			$q->set( 'fullname', $q->bindValue( trim( $_POST['fullname'] ) ) );
		}
		if ( isset( $_POST['jabber_name'] ) && trim( $_POST['jabber_name'] ) !== '' )
		{
			$q->set( 'jabber', $q->bindValue( trim( $_POST['jabber_name'] ) ) );
		}
		$q->where( $q->expr->eq( 'id', $q->bindValue( $_SESSION['ezcAuth_id'] ) ) );
		$s = $q->prepare();
		$s->execute();

		// update user_pref
		$q = ezcDbInstance::get()->createUpdateQuery();
		$q->update( 'user_pref' )
		  ->set( 'http_refresh', $q->bindValue( ( isset( $_POST['http_refresh'] ) && $_POST['http_refresh'] == 'on' ) ? 1 : 0 ) )
		  ->set( 'jabber_type', $q->bindValue( ( isset( $_POST['jabber_type'] ) && $_POST['jabber_type'] == 'on' ) ? 1 : 0 ) )
		  ->set( 'jabber_expand_username', $q->bindValue( ( isset( $_POST['jabber_expand_username'] ) && $_POST['jabber_expand_username'] == 'on' ) ? 1 : 0 ) )
		  ->where( $q->expr->eq( 'user_id', $q->bindValue( $_SESSION['ezcAuth_id'] ) ) );
		$s = $q->prepare();
		$s->execute();
	}

	public static function updateAvatar()
	{
		if ( isset( $_FILES['avatar'] ) && $_FILES['avatar']['error'] == 0 )
		{
			$q = ezcDbInstance::get()->createSelectQuery();
			$q->select( 'avatar' )->from( 'avatar' )->where( $q->expr->eq( 'user_id', $q->bindValue( $_SESSION['ezcAuth_id'] ) ) );
			$s = $q->prepare();
			$s->execute();
			$r = $s->fetchAll();

			$settings = new ezcImageConverterSettings(
				array(
					new ezcImageHandlerSettings( 'GD', 'ezcImageGdHandler' ),
				)
			);

			$converter = new ezcImageConverter( $settings );

			$filters = array(
				new ezcImageFilter(
					'croppedThumbnail',
					array(
						'width' => 32,
						'height' => 32,
					)
				),
			);

			$converter->createTransformation( 'icon', $filters, array( 'image/png' ) );

			$converter->transform(
				'icon',
				$_FILES['avatar']['tmp_name'],
				$_FILES['avatar']['tmp_name'] . '.tmp.png'
			);

			if ( count( $r ) )
			{
				$q = ezcDbInstance::get()->createUpdateQuery();
				$q->update( 'avatar' );
				$q->set( 'avatar', $q->bindValue( base64_encode( file_get_contents( $_FILES['avatar']['tmp_name'] . '.tmp.png' ) ) ) );
				$q->where( $q->expr->eq( 'user_id', $q->bindValue( $_SESSION['ezcAuth_id'] ) ) );
			}
			else
			{
				$q = ezcDbInstance::get()->createInsertQuery();
				$q->insertInto( 'avatar' );
				$q->set( 'user_id', $q->bindValue( $_SESSION['ezcAuth_id'] ) );
				$q->set( 'avatar', $q->bindValue( base64_encode( file_get_contents( $_FILES['avatar']['tmp_name'] . '.tmp.png' ) ) ) );
			}
			$s = $q->prepare();
			$s->execute();
		}
	}
}
?>
