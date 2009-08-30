<?php
class shareMvcConfiguration implements ezcMvcDispatcherConfiguration
{
	public $config;

	function __construct( $config )
	{
		$this->config = $config;
	}

	function createRequestParser()
	{
		return new ezcMvcHttpRequestParser;
	}

	function createRouter( ezcMvcRequest $request )
	{
		return new shareRouter( $request );
	}

	function runPreRoutingFilters( ezcMvcRequest $request )
	{
	}

	function runRequestFilters( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request )
	{
		switch ( $routeInfo->matchedRoute )
		{
			case '/register/submit':
			case '/register':
			case '/login-required':
			case '/basic-auth-required':
			case '/login':
			case '/logout':
			case '/fatal':
				break;
			case '/rss':
			case '/rss/tag/:tagName':
				return $this->runRssAuthRequiredFilter( $request );
			default:
				return $this->runAuthRequiredFilter( $request );
		}
	}

	private function runRssAuthRequiredFilter( $request )
	{
		$database = new ezcAuthenticationDatabaseInfo( ezcDbInstance::get(), 'user', array( 'id', 'password' ) );
		$databaseFilter = new ezcAuthenticationDatabaseFilter( $database );

		// use the options object when creating a new Session object
		$options = new ezcAuthenticationSessionOptions();
		$options->validity = 86400;

		$session = new ezcAuthenticationSession( $options );
		$session->start();

		$user = $session->load();
		$password = null;
		$loginWithForm = true;

		if ( $user === null && $request->authentication )
		{
			$user = $request->authentication->identifier;
			$password = $request->authentication->password;
		}

		$credentials = new ezcAuthenticationPasswordCredentials( $user, md5( $password ) );
		$authentication = new ezcAuthentication( $credentials );
		$authentication->session = $session;
		$authentication->addFilter( $databaseFilter );

		if ( !$authentication->run() )
		{
			$request->uri = '/basic-auth-required';
			return new ezcMvcInternalRedirect( $request );
		}

		if ( isset( $_SESSION['ezcAuth_id'] ) )
		{
			$q = ezcDbInstance::get()->createSelectQuery();
			$q->select( '*' )
			  ->from( 'user' )
			  ->leftJoin( 'user_pref', 'user.id', 'user_pref.user_id' )
			  ->where( $q->expr->eq( 'id', $q->bindValue( $_SESSION['ezcAuth_id'] ) ) );
			$s = $q->prepare();
			$s->execute();
			$r = $s->fetchAll();

			$userName = $r[0]['fullname'];
			date_default_timezone_set( $r[0]['timezone'] );
		}
		$request->variables['user'] = $userName;
	}

	private function runAuthRequiredFilter( $request )
	{
		$database = new ezcAuthenticationDatabaseInfo( ezcDbInstance::get(), 'user', array( 'id', 'password' ) );
		$databaseFilter = new ezcAuthenticationDatabaseFilter( $database );

		// use the options object when creating a new Session object
		$options = new ezcAuthenticationSessionOptions();
		$options->validity = 86400;

		$session = new ezcAuthenticationSession( $options );
		$session->start();

		$user = $session->load();
		$password = null;
		$loginWithForm = true;

		//$debug->log( $password, ezcLog::INFO, array( __METHOD__ ) );

		$credentials = new ezcAuthenticationPasswordCredentials( $user, md5( $password ) );
		$authentication = new ezcAuthentication( $credentials );
		$authentication->session = $session;
		$authentication->addFilter( $databaseFilter );

		if ( !$authentication->run() )
		{
			$status = $authentication->getStatus();
			$request->variables['redirUrl'] = $request->uri;
			$request->variables['reasons']  = $status;

			$request->uri = '/login-required';
			return new ezcMvcInternalRedirect( $request );
		}

		if ( isset( $_SESSION['ezcAuth_id'] ) )
		{
		    /*
			$q = ezcDbInstance::get()->createSelectQuery();
			$q->select( '*' )
			  ->from( 'user' )
			  ->leftJoin( 'user_pref', 'user.id', 'user_pref.user_id' )
			  ->where( $q->expr->eq( 'id', $q->bindValue( $_SESSION['ezcAuth_id'] ) ) );
			$s = $q->prepare();
			$s->execute();
			$r = $s->fetchAll();

			$userName = $r[0]['fullname'];
			*/
            $q = ezcDbInstance::get()->createSelectQuery();
            $q->select( '*' )
              ->from( 'user' )
              ->where( $q->expr->eq( 'id', $q->bindValue( $_SESSION['ezcAuth_id'] ) ) );
            $s = $q->prepare();
            $s->execute();
            $r = $s->fetchAll();

            $userName = $r[0]['fullname'];
			date_default_timezone_set( $r[0]['timezone'] );
		}
		$request->variables['user'] = $userName;
	}

	function runAddCurrentUrlFilter( ezcMvcResult $result )
	{
		$result->variables['currentUrl'] = ezcUrlTools::getCurrentUrl();
	}


	function runResultFilters( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result )
	{
		$result->variables['installDomain'] = $this->config->getSetting( 'TheWire', 'installDomain' );
		$result->variables['mailDomain'] = $this->config->getSetting( 'TheWire', 'mailDomain' );
		$result->variables['jabberUser'] = $this->config->getSetting( 'jabber', 'user' );
		$result->variables['jabberDomain'] = $this->config->getSetting( 'jabber', 'domain' );
		$result->variables['bugLinkFormat'] = $this->config->getSetting( 'formats', 'bugLinkFormat' );
		$result->variables['tagCloud'] = shareApp::tagCloud();
		$result->variables['peopleCloud'] = shareApp::peopleCloud();

		debugLogger::log( "testing ezcDebug ezcLog::DEBUG", ezcLog::DEBUG );
        debugLogger::log( "testing ezcDebug ezcLog::DEBUG", ezcLog::DEBUG );
        debugLogger::log( "testing ezcDebug ezcLog::NOTICE", ezcLog::NOTICE );
        debugLogger::log( "testing ezcDebug ezcLog::NOTICE", ezcLog::NOTICE );
        debugLogger::log( "testing ezcDebug ezcLog::WARNING", ezcLog::WARNING );
        debugLogger::log( "testing ezcDebug ezcLog::WARNING", ezcLog::WARNING );
        debugLogger::log( "testing ezcDebug ezcLog::ERROR", ezcLog::ERROR );
        debugLogger::log( "testing ezcDebug ezcLog::ERROR", ezcLog::ERROR, array( "source" => __METHOD__ ) );
        debugLogger::log( "testing ezcDebug ezcLog::ERROR", ezcLog::ERROR, array( "source" => __METHOD__ ) );
        $result->variables['debugOutput'] = debugLogger::generateOutput();

		$this->runAddCurrentUrlFilter( $result );
		switch ( $routeInfo->matchedRoute )
		{
			case '/register/submit':
			case '/register':
			case '/basic-auth-required':
			case '/login-required':
			case '/login':
			case '/logout':
			case '/fatal':
				break;
			default:
				$result->variables['user'] = isset( $request->variables['user'] ) ? $request->variables['user'] : 'Anonymous';
		}
	}

	function createView( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result )
	{
		switch ( $routeInfo->matchedRoute )
		{
			case '/rss':
				$view = new shareFeedView( $request, $result );
				$view->id = "http://{$result->variables['installDomain']}/";
				$view->title = 'TheWire: Latest updates';
				return $view;

			case '/rss/tag/:tagName':
				$view = new shareFeedView( $request, $result );
				$view->id = "http://{$result->variables['installDomain']}/tag/" . $request->variables['tagName'];
				$view->title = 'TheWire: Latest updates for #'. $request->variables['tagName'];
				return $view;

			case '/user_graph':
			case '/source_graph':
			case '/avatar/:personName':
				return new shareGraphicView( $request, $result );
			default:
				if ( preg_match( '@\.js$@', $routeInfo->matchedRoute ) )
				{
					$view = new shareAjaxView( $request, $result );
				}
				else if ( preg_match( '@\.atom$@', $routeInfo->matchedRoute ) )
				{
					$view = new shareAtomView( $request, $result );
				}
				else if ( preg_match( '@(iPhone|Minimo)@', $request->agent->agent ) )
				{
					$view = new shareMiniView( $request, $result );
				}
				else
				{
					$view = new shareView( $request, $result );
				}
		}
		switch ( $routeInfo->matchedRoute )
		{
			case '/':
			case '/updates':
			case '/updates.js':
			case '/updates.atom':
			case '/updates/:offset':
				$view->contentTemplate = 'root.ezt'; break;
			case '/update/:id':
				$view->contentTemplate = 'update.ezt'; break;
			case '/tag':
				$view->contentTemplate = 'tags.ezt'; break;
			case '/tag/:tagName':
				$view->contentTemplate = 'tag.ezt'; break;
			case '/person':
				$view->contentTemplate = 'persons.ezt'; break;
			case '/person/all':
				$view->contentTemplate = 'per-person.ezt'; break;
			case '/person/:personName':
				$view->contentTemplate = 'person.ezt'; break;
			case '/latest-links':
			case '/latest-links/:offset':
				$view->contentTemplate = 'latest-links.ezt'; break;
			case '/link/:linkId':
				$view->contentTemplate = 'link.ezt'; break;
			case '/help':
				$view->contentTemplate = 'help.ezt'; break;
			case '/prefs':
				$view->contentTemplate = 'prefs.ezt'; break;
			case '/register/submit':
				$view->contentTemplate = 'register-submit.ezt'; break;
			case '/register':
				$view->contentTemplate = 'register.ezt'; break;
			case '/login-required':
				$view->contentTemplate = 'login.ezt'; break;
			case '/fatal':
				$view->contentTemplate = 'fatal.ezt'; break;
            case '/debug/:type':
                $view->contentTemplate = 'debug.ezt'; break;
		}
		return $view;
	}

	function runResponseFilters( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result, ezcMvcResponse $response )
	{
		if ( in_array( 'gzip', $request->accept->encodings ) )
		{
			$filter = new ezcMvcGzipResponseFilter();
			$filter->filterResponse( $response );
		}
		else if ( in_array( 'deflate', $request->accept->encodings ) )
		{
			$filter = new ezcMvcGzDeflateResponseFilter();
			$filter->filterResponse( $response );
		}
	}

	function createResponseWriter( ezcMvcRoutingInformation $routeInfo, ezcMvcRequest $request, ezcMvcResult $result, ezcMvcResponse $response )
	{
		return new ezcMvcHttpResponseWriter( $response );
	}

	function createFatalRedirectRequest(ezcMvcRequest $request, ezcMvcResult $result, Exception $response )
	{
		$req = clone $request;
		$req->uri = '/fatal';
		$req->variables['message'] = $response->getMessage();
//		$req->variables['stackTrace'] = xdebug_get_formatted_function_stack();
        echo '<pre>';
        var_dump( $req->variables );
		//debug_print_backtrace();
		echo '</pre>';
		return $req;
	}
}
?>
