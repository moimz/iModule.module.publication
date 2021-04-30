<?php
/**
 * 이 파일은 iModule 출판물관리모듈의 일부입니다. (https://www.imodules.io)
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

$category = Request('category');
$start = Request('start');
$limit = Request('limit');
$sort = Request('sort') ? Request('sort') : 'year';
$dir = Request('dir') ? Request('dir') : 'desc';
$keyword = Request('keyword');

$lists = $this->db()->select($this->table->article);
if ($category) $lists->where('category',$category);
if ($keyword) $lists->where('title','%'.$keyword.'%','LIKE');

$total = $lists->copy()->count();
$lists = $lists->orderBy($sort,$dir)->orderBy('idx','desc')->limit($start,$limit)->get();

for ($i=0, $loop=count($lists);$i<$loop;$i++) {
	$category = $this->getCategory($lists[$i]->category);
	$lists[$i]->category = $category->title;
	$lists[$i]->type = $category->type;
	
	$authors = $this->db()->select($this->table->author)->where('aidx',$lists[$i]->idx)->orderBy('sort','asc')->get();
	
	if (count($authors) == 0) {
		$lists[$i]->author = '저자없음';
	} else {
		if ($authors[0]->midx > 0) {
			$member = $this->IM->getModule('member')->getMember($authors[0]->midx);
			$lists[$i]->author = $member->name.'('.$this->getAuthorName($member).')';
		} else {
			$lists[$i]->author = $authors[0]->name;
		}
		if (count($authors) > 1) {
			$lists[$i]->author.= ' 외 '.(count($authors) - 1).'명';
		}
	}
	
	if ($lists[$i]->publisher > 0) {
		$publisher = $this->getPublisher($lists[$i]->publisher);
		$lists[$i]->publisher = $publisher->title;
	} else {
		$lists[$i]->publisher = '';
	}
	
	if ($category->type == 'PAPER') {
		$lists[$i]->page = strpos($lists[$i]->page_no,'-') !== false ? 'pp.'.$lists[$i]->page_no : 'p.'.$lists[$i]->page_no;
		$lists[$i]->publisher_code = $lists[$i]->volume_no.'권 '.$lists[$i]->issue_no.'호';
	}
	
	if ($category->type == 'PATENT') {
		$lists[$i]->publisher_code = ($lists[$i]->volume_no == 1 ? '출원번호' : '등록번호').' : '.$lists[$i]->keyword;
	}
	
	if ($category->type == 'THESIS') {
		$lists[$i]->page = $lists[$i]->page_no;
	}
	
	if ($category->type == 'CONFERENCE') {
		$lists[$i]->page = $lists[$i]->page_no;
		$lists[$i]->publisher_code = $publisher->city.', '.$this->getText('country/'.$publisher->country);
	}
	
	if ($category->type == 'BOOK') {
		$lists[$i]->page = strpos($lists[$i]->page_no,'-') !== false ? 'pp.'.$lists[$i]->page_no : 'p.'.$lists[$i]->page_no;
		$lists[$i]->publisher_code = 'ISBN '.$lists[$i]->keyword;
	}
	
	$lists[$i]->cover = $lists[$i]->cover == 0 ? null : $this->IM->getModule('attachment')->getFileInfo($lists[$i]->cover);
	$lists[$i]->file = $lists[$i]->file == 0 ? null : $this->IM->getModule('attachment')->getFileInfo($lists[$i]->file);
}

$results->success = true;
$results->lists = $lists;
$results->total = $total;
?>