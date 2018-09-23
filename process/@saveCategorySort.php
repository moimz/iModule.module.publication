<?php
/**
 * 이 파일은 iModule 출판물관리모듈의 일부입니다. (https://www.imodules.io)
 *
 * 카테고리를 순서를 저장한다.
 * 
 * @file /modules/publication/process/@saveCategorySort.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 5. 11.
 */
if (defined('__IM__') == false) exit;

$updated = json_decode(Request('updated'));
for ($i=0, $loop=count($updated);$i<$loop;$i++) {
	$this->db()->update($this->table->category,array('sort'=>$updated[$i]->sort))->where('idx',$updated[$i]->idx)->execute();
}

$categories = $this->db()->select($this->table->category)->orderBy('sort','asc')->get();
for ($i=0, $loop=count($categories);$i<$loop;$i++) {
	$this->db()->update($this->table->category,array('sort'=>$i))->where('idx',$categories[$i]->idx)->execute();
}

$results->success = true;
?>