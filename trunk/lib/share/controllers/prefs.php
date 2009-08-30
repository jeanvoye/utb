<?php
class sharePreferencesController extends ezcMvcController
{
	public function doDisplay()
	{
		$res = new ezcMvcResult;
		$res->variables['prefs'] = shareApp::fetchCurrentPrefs();
		return $res;
	}

	public function doUpdate()
	{
		shareApp::updatePrefs();
		$res = new ezcMvcResult;
		$res->status = new ezcMvcExternalRedirect( '/prefs' );
		return $res;
	}
}
?>
