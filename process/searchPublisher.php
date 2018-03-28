<?php
/**
 * 이 파일은 출판물관리모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 매체를 검색한다.
 * 
 * @file /modules/publication/process/searchPublisher.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 2. 1.
 */
if (defined('__IM__') == false) exit;

$type = Request('type');
$keyword = Request('keyword');
$search = $this->IM->getModule('keyword')->getLivecode($keyword);
$keywords = $this->db()->select($this->table->publisher,'title')->where('type',$type)->where('search',$search.'%','LIKE')->get('title');

$results->success = true;
$results->keywords = $keywords;
?>