<?php
/**
 * 이 파일은 iModule 출판물관리모듈의 일부입니다. (https://www.imodules.io)
 *
 * 카테고리 목록을 가져온다.
 * 
 * @file /modules/publication/process/@getCategories.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 2. 1.
 */
if (defined('__IM__') == false) exit;

$lists = $this->db()->select($this->table->category)->orderBy('sort','asc')->get();
for ($i=0, $loop=count($lists);$i<$loop;$i++) {
	if ($lists[$i]->sort != $i) {
		$this->db()->update($this->table->category,array('sort'=>$i))->where('idx',$lists[$i]->idx)->execute();
		$lists[$i]->sort = $i;
	}
	
	$lists[$i]->article = $this->db()->select($this->table->article)->where('category',$lists[$i]->idx)->count();
}

if (Request('is_all') == 'true') {
	$lists[] = array('idx'=>'','title'=>'전체 카테고리','sort'=>-1);
}

$results->success = true;
$results->lists = $lists;
$results->total = count($lists);
?>