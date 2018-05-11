<?php
/**
 * 이 파일은 출판물관리모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 카테고리를 삭제한다.
 * 
 * @file /modules/publication/process/@deleteCategory.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 5. 11.
 */
if (defined('__IM__') == false) exit;

$idx = Request('idx') ? explode(',',Request('idx')) : array();
if (count($idx) > 0) {
	$articles = $this->db()->select($this->table->article)->where('category',$idx,'IN')->get();
	foreach ($articles as $article) {
		$this->db()->delete($this->table->article)->where('idx',$article->idx)->execute();
		$this->db()->delete($this->table->author)->where('aidx',$article->idx)->execute();
	}
	
	$this->db()->delete($this->table->category)->where('idx',$idx,'IN')->execute();
}

$results->success = true;
?>