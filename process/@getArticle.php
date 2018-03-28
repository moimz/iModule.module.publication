<?php
/**
 * 이 파일은 출판물관리모듈의 일부입니다. (https://www.imodule.kr)
 *
 * 출판물 정보를 가져온다.
 * 
 * @file /modules/publication/process/@getArticle.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 2. 1.
 */
if (defined('__IM__') == false) exit;

$idx = Request('idx');
$data = $this->db()->select($this->table->article)->where('idx',$idx)->getOne();
if ($data == null) {
	$results->success = false;
	$results->message = $this->getErrorText('NOT_FOUND');
	return;
}

$category = $this->getCategory($data->category);
if ($category->type == 'PAPER') {
	$data->paper_title = $data->title;
	unset($data->title);
	
	$data->paper_publisher = $data->publisher;
	unset($data->publisher);
	
	$data->paper_year = $data->year;
	unset($data->year);
	
	$data->paper_volume_no = $data->volume_no;
	unset($data->volume_no);
	
	$data->paper_issue_no = $data->issue_no;
	unset($data->issue_no);
	
	$data->paper_page_no = $data->page_no;
	unset($data->page_no);
	
	$data->paper_abstract = $data->abstract;
	unset($data->abstract);
	
	$data->paper_keyword = $data->keyword;
	unset($data->keyword);
}

$author = array();
$authors = $this->db()->select($this->table->author,'midx,name,sort')->where('aidx',$idx)->orderBy('sort','asc')->get();
for ($i=0, $loop=count($authors);$i<$loop;$i++) {
	if ($authors[$i]->midx > 0) {
		$member = $this->IM->getModule('member')->getMember($authors[$i]->midx);
		$authors[$i]->name = $member->name.'('.$member->nickname.')';
	}
}

$data->author = json_encode($authors,JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

$results->success = true;
$results->data = $data;
?>