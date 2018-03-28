<?php
/**
 * 이 파일은 출판물관리모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 출판물 목록을 가져온다.
 * 
 * @file /modules/publication/process/@getArticles.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 2. 1.
 */
if (defined('__IM__') == false) exit;

$type = Request('type');
$start = Request('start');
$limit = Request('limit');
$sort = Request('sort') ? Request('sort') : 'year';
$dir = Request('dir') ? Request('dir') : 'desc';
$keyword = Request('keyword');

$lists = $this->db()->select($this->table->article);
if ($type) $lists->where('type',$type);
//if ($keyword) $lists->where('title','%'.$keyword.'%','LIKE');

$total = $lists->copy()->count();
$lists = $lists->orderBy($sort,$dir)->orderBy('idx','desc')->limit($start,$limit)->get();

for ($i=0, $loop=count($lists);$i<$loop;$i++) {
	$category = $this->getCategory($lists[$i]->category);
	$lists[$i]->category = $category->title;
	$lists[$i]->type = $category->type;
	
	$authors = $this->db()->select($this->table->author)->where('aidx',$lists[$i]->idx)->orderBy('sort','asc')->get();
	
	if ($authors[0]->midx > 0) {
		$member = $this->IM->getModule('member')->getMember($authors[0]->midx);
		$lists[$i]->author = $member->name.'('.$member->nickname.')';
	} else {
		$lists[$i]->author = $authors[0]->name;
	}
	if (count($authors) > 1) {
		$lists[$i]->author.= ' 외 '.(count($authors) - 1).'명';
	}
	
	$publisher = $this->getPublisher($lists[$i]->publisher);
	$lists[$i]->publisher = $publisher->title;
	if ($publisher->type == 'PAPER') {
		$lists[$i]->publisher_code = $lists[$i]->volume_no.'권 '.$lists[$i]->issue_no.'호, P.'.$lists[$i]->page_no;
	}
	
	if ($publisher->type == 'BOOK') {
		$lists[$i]->publisher_code = 'ISBN '.$lists[$i]->page_no;
	}
}

$results->success = true;
$results->lists = $lists;
$results->total = count($lists);
?>