<?php
/*
 # DEBUG CALLS #
 
[[!Cliche?
	&albumsTpl=`myjournal/albums`
	&albumTpl=`myjournal/album`
	&itemTpl=`myjournal/item`
	&columns=`2`
]]

[[!Cliche?
	&albumsTpl=`myjournal/albums`
	&albumTpl=`myjournal/album`
	&itemTpl=`myjournal/item`
	&columns=`4`
	&thumbWidth=`75`
	&thumbHeight=`75`
]]

[[!Cliche?
	&albumsTpl=`myjournal/albums`
	&albumTpl=`myjournal/album`
	&itemTpl=`myjournal/item`
]]

*/

/* Properties */
$thumbWidth = $this->getOption('thumbWidth', $config, 120);
$thumbHeight = $this->getOption('thumbHeight', $config, 120);
$columns = $this->getOption('columns', $config, 3);

/* Chunks */
$albumsWrapperTpl = $this->getOption('albumsListWrapperTpl', $config, 'albumswrapper');
$albumsTpl = $this->getOption('albumsListItemTpl', $config, 'albums');
$albumWrapperTpl = $this->getOption('albumWrapperTpl', $config, 'albumwrapper');
$albumTpl = $this->getOption('albumItemTpl', $config, 'album');
$itemTpl = $this->getOption('itemTpl', $config, 'item');

/* Single Item settings */
$useZoom = $this->getOption('useZoom', $config, true);
$loadJQuery = $this->getOption('loadJQuery', $config, true);

/* View */
$view = $this->getOption('view', $_REQUEST, $this->getOption('view', $config, 'default'));

/* Load styles */
$modx->regClientCSS($this->config['chunks_url'] . 'default.css');

if($loadJQuery){
	// $modx->regClientStartUpScript('http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js');
}

switch($view){
	case 'item':
		$id = $modx->getOption('cid', $_REQUEST, $this->getOption('id', $config, null));
		if(empty($id)){
			return 'No item specified';
		}
		
		/* If the user want to see a larger version of the image using javascript on single item */
		if($useZoom){			
			$modx->regClientCSS($config['chunks_url'] . 'fancybox/jquery.fancybox-1.3.4.css');			
			$modx->regClientScript($config['chunks_url'] . 'fancybox/jquery.fancybox-1.3.4.pack.js');
			$modx->regClientHTMLBlock('<script type="text/javascript">
				$(document).ready(function() {
					$("a.zoom").fancybox({
						titlePosition	: "inside"
						,transitionIn: "elastic"
						,transitionOut: "elastic"
					});
				});
			</script>');
		}		
		
		$item = $modx->getObject('ClicheItems', $id);
		$phs = $item->toArray(); 
		$phs['width'] = $thumbWidth;
		$phs['height'] = $thumbHeight;
		$phs['image'] = $config['images_url'].$item->filename;	
		$phs['thumbnail'] = $config['phpthumb'] . urlencode($phs['image']) .'&h='. $phs['height'] .'&w='. $phs['width'] .'&zc=1';	
		
		$output = $this->getChunk($itemTpl, $phs);
		break;
	case 'album':
		$id = $modx->getOption('cid', $_REQUEST, $this->getOption('id', $config, null));
		if(empty($id)){
			return 'No album specified';
		}
		$list = ''; $i = 0;
		$c = $modx->newQuery('ClicheItems');
		$c->where(array(
			'album_id' => $id,
		));
		// $c->sortBy($sort,$dir);
		// $c->limit($limit,$start);
		$rows = $modx->getCollection('ClicheItems', $c);
		foreach($rows as $row){
			$phs = $row->toArray(); 
			$phs['width'] = $thumbWidth;
			$phs['height'] = $thumbHeight;
			$phs['url'] = $modx->makeUrl( $modx->resource->get('id'),'', array( 'view' => 'item', 'cid'=> $row->id) );	
			$phs['reqParams'] = http_build_query( array( 'view' => 'item', 'cid'=> $row->id) );	
			$phs['image'] = $config['images_url'].$row->filename;	
			$phs['thumbnail'] = $config['phpthumb'] . urlencode($phs['image']) .'&h='. $phs['height'] .'&w='. $phs['width'] .'&zc=1';	
			
			$list .= $this->getChunk($albumTpl, $phs);
			
			$i++;
			if($i == $columns){
				$list .=  '<br style="clear: both;">';
				$i = 0;
			}			
		}
		$ph['items'] = $list;
		$output = $this->getChunk($albumWrapperTpl, $ph);
		break;
	/* Album list */
	default:
		$list = ''; $i = 0;
		$rows = $modx->getCollectionGraph('ClicheAlbums', '{ "Cover":{} }');
		foreach($rows as $row){
			$phs = $row->toArray(); 
			$phs['width'] = $thumbWidth;
			$phs['height'] = $thumbHeight;
			$phs['url'] = $modx->makeUrl( $modx->resource->get('id'),'', array( 'view' => 'album', 'cid'=> $row->id) );	
			$phs['reqParams'] = http_build_query( array( 'view' => 'album', 'cid'=> $row->id) );	
			$phs['image'] = $config['images_url'] . $row->Cover->filename;
			$phs['thumbnail'] = $config['phpthumb'] . urlencode($phs['image']) .'&h='. $phs['height'] .'&w='. $phs['width'] .'&zc=1';	
			
			$list .= $this->getChunk($albumsTpl, $phs);
			
			$i++;
			if($i == $columns){
				$list .=  '<br style="clear: both;">';
				$i = 0;
			}			
		}
		$ph['items'] = $list;
		$output = $this->getChunk($albumsWrapperTpl, $ph);
		break;
}