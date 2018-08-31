<?php
/**
 * 이 파일은 출판물관리모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 유형별 매체 목록을 가져온다.
 * 
 * @file /modules/publication/process/@getPublishers.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 2. 1.
 */
if (defined('__IM__') == false) exit;

$type = Request('type');
$start = Request('start');
$limit = Request('limit');
$sort = Request('sort') ? Request('sort') : 'title';
$dir = Request('dir') ? Request('dir') : 'asc';
$keyword = Request('keyword');

$lists = $this->db()->select($this->table->publisher);
if ($type) $lists->where('type',$type);
if ($keyword) $lists->where('title','%'.$keyword.'%','LIKE');

if ($limit) {
	$total = $lists->copy()->count();
	$lists = $lists->orderBy($sort,$dir)->limit($start,$limit)->get();
} else {
	$lists = $lists->orderBy($sort,$dir)->get();
	$total = count($lists);
}

for ($i=0, $loop=count($lists);$i<$loop;$i++) {
	$lists[$i]->article = $this->db()->select($this->table->article)->where('publisher',$lists[$i]->idx)->count();
}

$results->success = true;
$results->lists = $lists;
$results->total = count($lists);
?>