<?php
class shareAddController extends ezcMvcController
{
	public function createResult()
	{
		if ( isset( $_POST['update'] ) )
		{
			shareApp::addFromWeb( $_POST['update'] );
		}
		$res = new ezcMvcResult;
		$res->status = new ezcMvcExternalRedirect( $_POST['redirUrl'] );
		return $res;
	}
}
?>
