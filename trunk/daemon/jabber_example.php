<?php
/* Jabber Class Example
 * Copyright 2002-2007, Steve Blinch
 * http://code.blitzaffe.com
 * ============================================================================
 *
 * DETAILS
 *
 * Provides a very basic example of how to use class_Jabber.php.
 *
 * This example connects to a Jabber server, logs in, fetches (and displays)
 * the roster, and then waits until a message is received from another contact.
 *
 * It then starts a countdown which, in sequence:
 * 
 * 1) sends a "composing" event to the other contact (eg: "so-and-so is typing a message"),
 * 2) sends a "composing stopped" event,
 * 3) sends another "composing" event",
 * 4) sends a message to the other contact,
 * 5) logs out
 *
 */

// include the Jabber class
require "../lib/jabberclass-0.9/class_Jabber.php";
require "../config.php";
 
// set your Jabber server hostname, username, and password here
define("JABBER_SERVER", $ini->getSetting( 'jabber', 'domain' ) );
define("JABBER_USERNAME", $ini->getSetting( 'jabber', 'user' ) );
define("JABBER_PASSWORD", $ini->getSetting( 'jabber', 'password' ) );

define("RUN_TIME",3000);	// set a maximum run time of 30 seconds
define("CBK_FREQ",1);	// fire a callback event every second


// This class handles all events fired by the Jabber client class; you
// can optionally use individual functions instead of a class, but this
// method is a bit cleaner.
class TestMessenger {
	
	function TestMessenger(&$jab) {
		$this->jab = &$jab;
		$this->first_roster_update = true;
		
		echo "Created!\n";
		$this->countdown = 0;
	}
	
	// called when a connection to the Jabber server is established
	function handleConnected() {
		echo "Connected!\n";
		
		// now that we're connected, tell the Jabber class to login
		echo "Authenticating with " . JABBER_USERNAME . ":" . JABBER_PASSWORD . ".\n";
		$this->jab->login(JABBER_USERNAME,JABBER_PASSWORD);
	}
	
	// called after a login to indicate the the login was successful
	function handleAuthenticated() {
		echo "Authenticated!\n";
		
		
		echo "Fetching service list and roster ...\n";
		
		// browser for transport gateways
		$this->jab->browse();
		
		// retrieve this user's roster
		$this->jab->get_roster();
		
		// set this user's presence
		$this->jab->set_presence("","Share your information");
	}
	
	// called after a login to indicate that the login was NOT successful
	function handleAuthFailure($code,$error) {
		echo "Authentication failure: $error ($code)\n";
		
		// set terminated to TRUE in the Jabber class to tell it to exit
		$this->jab->terminated = true;
	}
	
	// called periodically by the Jabber class to allow us to do our own
	// processing
	function handleHeartbeat() {
		// retrieve user preferences
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( 'jabber, jabber_expand_username' )
		  ->from( 'user' )
		  ->leftJoin( 'user_pref', 'user.id', 'user_pref.user_id' );
		$s = $q->prepare();
		$s->execute();
		$userWantsFull = array();
		foreach ( $s as $user )
		{
			$userWantsFull[$user['jabber']] = $user['jabber_expand_username'];
		}

		// check jabber work log
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( '*' )
		  ->from( 'jabber_work' )
		  ->leftJoin( 'message', 'jabber_work.message_id', 'message.id' )
		  ->leftJoin( 'user', 'message.user_id', 'user.id' );
		$s = $q->prepare();
		$s->execute();

		foreach( $s as $work )
		{
			$to = $work['jabber_id'];
			if ( isset( $this->jab->roster[$to] ) && !($this->jab->roster[$to]['show'] == 'off' || $this->jab->roster[$to]['show'] == 'dnd' ) )
			{
				shareFormat::formatMessage( $work, false );
				$work['textStatus'] = shareFormat::substituteLink( $work['textStatus'] );
				$work['status'] = shareFormat::substituteLink( $work['status'] );
				if ( $userWantsFull[$to] )
				{
					echo "- $to: Sending {$work['status']} (with username expansion)\n";
					$this->jab->message($to, "chat" ,NULL, $work['fullname'] . ': '. $work['textStatus'], $work['fullname'] . ': '. $work['status'] );
				}
				else
				{
					echo "- $to: Sending {$work['status']}\n";
					$this->jab->message($to, "chat" ,NULL, $work['user_id'] . ': '. $work['textStatus'], $work['user_id'] . ': '. $work['status'] );
				}

				// update last sent message information
				// TODO: FIX THIS QUERY
/*				$q = ezcDbInstance::get()->createUpdateQuery();
				$q->update( 'user_pref' )
				  ->set( 'queued_since', $q->bindValue( $work['message_id'] ) )
				  ->where( $q->expr->eq( 'jabber_id', $q->bindValue( $work['jabber_id'] ) ) );
				$sa = $q->prepare();
				$sa->execute();
*/			}
		}

		// empty jabber work log
		$q = ezcDbInstance::get()->createDeleteQuery();
		$q->deleteFrom( 'jabber_work' );
		$s = $q->prepare();
		$s->execute();

		reset($this->jab->roster);
		foreach ($this->jab->roster as $jid=>$details) {
//			echo "$jid\t\t\t".$details["transport"]."\t".$details["show"]."\t".$details["status"]."\n";
		}
	}
	
	// called when an error is received from the Jabber server
	function handleError($code,$error,$xmlns) {
		echo "Error: $error ($code)".($xmlns?" in $xmlns":"")."\n";
	}
	
	// called when a message is received from a remote contact
	function handleMessage($from,$to,$body,$subject,$thread,$id,$extended) {
		/*
		echo "Subject: $subject\tThread; $thread\n";
		echo "ID: $id\n";
		echo "\n";
		*/

		$from = preg_replace( '@/.*$@', '', $from );

		echo "Incoming message!\n";
		echo "From: $from\t\tTo: $to\n";
		echo "Body: $body\n";
		echo "Extended:\n";

		if ( !isset( $this->jab->roster[$from] ) ||  $this->jab->roster[$from]['show'] == 'off' )
		{
			$this->jab->subscription_request_accept( $from );
			$this->jab->subscribe( $from, "checking" );
			$this->jab->message($from,"chat",NULL,"Welcome, please authorise me!");

			return;
		}

		if ( trim( $body ) === '' )
		{
			return;
		}

		if ( !shareApp::handleJabberActions( $from, $body ) )
		{
			shareApp::addFromJabber( $from, $body );
		}
	}
	
	function _contact_info($contact) {
		return sprintf("Contact %s (JID %s) has status %s and message %s\n",$contact['name'],$contact['jid'],$contact['show'],$contact['status']);
	}
	
	function handleRosterUpdate($jid) {
		if ($this->first_roster_update) {
			// the first roster update indicates that the entire roster has been
			// downloaded for the first time
			echo "Roster downloaded:\n";
			
			foreach ($this->jab->roster as $k=>$contact) {
				echo $this->_contact_info($contact);
			}	
			$this->first_roster_update = false;
		} else {
			// subsequent roster updates indicate changes for individual roster items
			$jid = preg_replace( '@/.*$@', '', $jid );
			$contact = $this->jab->roster[$jid];
			echo "Contact updated: " . $this->_contact_info($contact);
		}
	}
	
	function handleDebug($msg,$level) {
		echo "DBG: $msg\n";
	}
	
}

// create an instance of the Jabber class
$display_debug_info = false;
$jab = new Jabber($display_debug_info);

// create an instance of our event handler class
$test = new TestMessenger($jab);

// set handlers for the events we wish to be notified about
$jab->set_handler("connected",$test,"handleConnected");
$jab->set_handler("authenticated",$test,"handleAuthenticated");
$jab->set_handler("authfailure",$test,"handleAuthFailure");
$jab->set_handler("heartbeat",$test,"handleHeartbeat");
$jab->set_handler("error",$test,"handleError");
$jab->set_handler("message_normal",$test,"handleMessage");
$jab->set_handler("message_chat",$test,"handleMessage");
$jab->set_handler("debug_log",$test,"handleDebug");
$jab->set_handler("rosterupdate",$test,"handleRosterUpdate");

echo "Connecting ...\n";

// connect to the Jabber server
if (!$jab->connect(JABBER_SERVER)) {
	die("Could not connect to the Jabber server!\n");
}

// now, tell the Jabber class to begin its execution loop
$jab->execute(CBK_FREQ, -1);

// Note that we will not reach this point (and the execute() method will not
// return) until $jab->terminated is set to TRUE.  The execute() method simply
// loops, processing data from (and to) the Jabber server, and firing events
// (which are handled by our TestMessenger class) until we tell it to terminate.
//
// This event-based model will be familiar to programmers who have worked on
// desktop applications, particularly in Win32 environments.

// disconnect from the Jabber server
$jab->disconnect();
?>
