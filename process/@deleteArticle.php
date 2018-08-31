<?php
/**
 * 이 파일은 출판물관리모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 카테고리를 삭제한다.
 * 
 * @file /modules/publication/process/@deleteArticle.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 8. 28.
 */
if (defined('__IM__') == false) exit;

$idx = Request('idx') ? explode(',',Request('idx')) : array();
if (count($idx) > 0) {
	$lists = $this->db()->select($this->table->article)->where('idx',$idx,'IN')->get();
	for ($i=0, $loop=count($lists);$i<$loop;$i++) {
		$this->db()->delete($this->table->author)->where('aidx',$lists[$i]->idx)->execute();
	}
	$this->db()->delete($this->table->article)->where('idx',$idx,'IN')->execute();
}

$results->success = true;
?>