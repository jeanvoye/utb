<?php
class shareFatalController extends ezcMvcController
{
	public function createResult()
	{
		$result = new ezcMvcResult;
		$result->variables['message'] = $this->message;
		$result->variables['stackTrace'] = $this->stackTrace;
		return $result;
	}
}
?>
