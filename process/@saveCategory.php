<?php
/**
 * 이 파일은 출판물관리모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 카테고리를 저장한다.
 * 
 * @file /modules/publication/process/@saveCategory.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 2. 1.
 */
if (defined('__IM__') == false) exit;

$errors = array();
$idx = Request('idx');
$title = Request('title') ? Request('title') : $errors['title'] = $this->getErrorText('REQUIRED');
$type = Request('type') ? Request('type') : $errors['type'] = $this->getErrorText('REQUIRED');

if (count($errors) == 0) {
	if ($idx) {
		if ($this->db()->select($this->table->category)->where('idx',$idx,'!=')->where('title',$title)->has() == true) {
			$results->success = false;
			$results->errors = array('title'=>$this->getErrorText('DUPLICATED'));
			return;
		}
		
		$this->db()->update($this->table->category,array('title'=>$title,'type'=>$type))->where('idx',$idx)->execute();
	} else {
		if ($this->db()->select($this->table->category)->where('title',$title)->has() == true) {
			$results->success = false;
			$results->errors = array('title'=>$this->getErrorText('DUPLICATED'));
			return;
		}
		
		$sort = $this->db()->select($this->table->category,'max(sort) as sort')->getOne();
		$sort = $sort == null || isset($sort->sort) == false ? 0 : $sort->sort + 1;
		$this->db()->insert($this->table->category,array('title'=>$title,'type'=>$type,'sort'=>$sort))->execute();
	}
	
	$results->success = true;
} else {
	$results->success = false;
	$results->errors = $errors;
}
?>