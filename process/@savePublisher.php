<?php
/**
 * 이 파일은 출판물관리모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 유형별 매체를 저장한다.
 * 
 * @file /modules/publication/process/@savePublisher.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 2. 1.
 */
if (defined('__IM__') == false) exit;

$errors = array();
$idx = Request('idx');
$title = Request('title') ? Request('title') : $errors['title'] = $this->getErrorText('REQUIRED');
$search = $this->IM->getModule('keyword')->getLivecode($title);
$type = Request('type') ? Request('type') : $errors['type'] = $this->getErrorText('REQUIRED');
$isbn = Request('isbn') ? Request('isbn') : '';
$link = Request('link') ? Request('link') : '';

if (count($errors) == 0) {
	if ($idx) {
		if ($this->db()->select($this->table->publisher)->where('idx',$idx,'!=')->where('title',$title)->has() == true) {
			$results->success = false;
			$results->errors = array('title'=>$this->getErrorText('DUPLICATED'));
			return;
		}
		
		$this->db()->update($this->table->publisher,array('title'=>$title,'search'=>$search,'type'=>$type,'isbn'=>$isbn,'link'=>$link))->where('idx',$idx)->execute();
	} else {
		if ($this->db()->select($this->table->publisher)->where('title',$title)->has() == true) {
			$results->success = false;
			$results->errors = array('title'=>$this->getErrorText('DUPLICATED'));
			return;
		}
		
		$this->db()->insert($this->table->publisher,array('title'=>$title,'search'=>$search,'type'=>$type,'isbn'=>$isbn,'link'=>$link))->execute();
	}
	
	$results->success = true;
} else {
	$results->success = false;
	$results->errors = $errors;
}
?>