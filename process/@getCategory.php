<?php
/**
 * 이 파일은 출판물관리모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 카테고리 정보를 가져온다.
 * 
 * @file /modules/publication/process/@getCategory.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 2. 1.
 */
if (defined('__IM__') == false) exit;

$idx = Request('idx');
$data = $this->db()->select($this->table->category)->where('idx',$idx)->getOne();
if ($data == null) {
	$results->success = false;
	$results->message = $this->getErrorText('NOT_FOUND');
	return;
}

$results->success = true;
$results->data = $data;
?>