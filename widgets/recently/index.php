<?php
/**
 * 이 파일은 iModule 출판물관리모듈의 일부입니다. (https://www.imodules.io)
 *
 * 출판물 최근항목을 가져온다.
 * 
 * @file /modules/publication/widgets/recently/index.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 8. 29.
 */
if (defined('__IM__') == false) exit;

$category = $Widget->getValue('category');
$count = $Widget->getValue('count');
$cache = $Widget->getValue('cache');

if (true || $Widget->checkCache() < time() - $cache) {
	$lists = $me->db()->select($me->getTable('article').' a','a.*, c.type')->join($me->getTable('category').' c','c.idx=a.category','LEFT');
	if (count($category) > 0) $lists->where('a.category',$category,'IN');
	$lists = $lists->limit($count)->orderBy('a.year','desc')->orderBy('a.idx','desc')->get();
	
	for ($i=0, $loop=count($lists);$i<$loop;$i++) {
		$lists[$i]->category = $lists[$i]->category == 0 ? null : $me->getCategory($lists[$i]->category);
		$lists[$i]->publisher = $me->getPublisher($lists[$i]->publisher);
		
		$page = $IM->getContextUrl('publication',$lists[$i]->category->idx,array(),array(),true);
		$lists[$i]->link = $page;
	}
	
//	$Widget->storeCache(json_encode($lists,JSON_UNESCAPED_UNICODE));
} else {
	$lists = json_decode($Widget->getCache());
}

return $Templet->getContext('index',get_defined_vars());
?>