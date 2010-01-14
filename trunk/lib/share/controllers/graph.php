<?php
class shareGraphController extends ezcMvcController
{
	public function createResult()
	{
		$res = new ezcMvcResult();

		switch( $this->action )
		{
			case 'user':
				$res->variables['graphic'] = $this->userGraph();
				break;
			case 'source':
				$res->variables['graphic'] =  $this->sourceGraph();
				break;
		}
		$res->content = new ezcMvcResultContent;
		$res->content->type = 'image/svg+xml';
		$res->cache = new ezcMvcResultCache;
		$res->cache->expire = new DateTime;
		$res->cache->controls = array( 'max-age=3600', 'must-revalidate' );
		$res->cache->pragma = 'public';

		return $res;
	}

	private function userGraph()
	{
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( 'user_id, count(user_id) as cnt' )
		  ->from( 'message' )
		  ->groupBy( 'user_id' )
		  ->orderBy( 'cnt DESC' );
		$s = $q->prepare();
		$s->execute();

		$data = array();
		$data['others'] = 0;
		foreach( $s as $item )
		{
			if ( count( $data ) < 15 )
			{
				$data[$item['user_id']] = $item['cnt'];
			}
			else
			{
				$data['others'] += $item['cnt'];
			}
		}

		$chart = new ezcGraphPieChart();
		$chart->title = 'People';
		$chart->legend = false;
		$chart->options->font->minFontSize = 1; 
		$chart->palette = new ezcGraphPaletteEz(); 
		$chart->data['browsers'] = new ezcGraphArrayDataSet( $data );

		$chart->render( 300, 200, '/tmp/graph.png' );
		return file_get_contents( '/tmp/graph.png' );
	}

	private function sourceGraph()
	{
		$q = ezcDbInstance::get()->createSelectQuery();
		$q->select( 'source, count(source)' )
		  ->from( 'message' )
		  ->groupBy( 'source' );
		$s = $q->prepare();
		$s->execute();

		$chart = new ezcGraphPieChart();
		$chart->title = 'Sources';
		$chart->legend = false;
		$chart->palette = new ezcGraphPaletteEz(); 
		$chart->data['browsers'] = new ezcGraphDatabaseDataSet( $s );

		$chart->render( 300, 200, '/tmp/graph.png' );
		return file_get_contents( '/tmp/graph.png' );
	}
}
?>
