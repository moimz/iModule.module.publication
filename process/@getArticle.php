<?php
/**
 * 이 파일은 iModule 출판물관리모듈의 일부입니다. (https://www.imodules.io)
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
	
	$data->paper_link = $data->link;
	unset($data->link);
	
	$data->paper_keyword = $data->keyword;
	unset($data->keyword);
}

if ($category->type == 'THESIS') {
	$data->thesis_title = $data->title;
	unset($data->title);
	
	$data->thesis_type = $data->page_no;
	unset($data->page_no);
	
	$data->thesis_year = $data->year;
	unset($data->year);
	
	$data->thesis_month = $data->volume_no;
	unset($data->page_no);
}

if ($category->type == 'CONFERENCE') {
	$data->conference_title = $data->title;
	unset($data->title);
	
	$data->conference_publisher = $data->publisher;
	unset($data->publisher);
	
	$data->conference_type = $data->page_no;
	unset($data->page_no);
}

if ($category->type == 'PATENT') {
	$data->patent_title = $data->title;
	unset($data->title);
	
	$data->patent_type = $data->volume_no;
	unset($data->volume_no);
	
	$data->patent_date = $data->page_no;
	unset($data->page_no);
	
	$data->patent_no = $data->keyword;
	unset($data->keyword);
}

if ($category->type == 'BOOK') {
	$data->book_title = $data->title;
	unset($data->title);
	
	$data->book_publisher = $data->publisher;
	unset($data->publisher);
	
	$data->book_page_no = $data->page_no;
	unset($data->page_no);
	
	$data->book_year = $data->year;
	unset($data->year);
	
	$data->book_abstract = $data->abstract;
	unset($data->abstract);
	
	$data->book_link = $data->link;
	unset($data->link);
	
	$data->book_keyword = $data->keyword;
	unset($data->keyword);
}

if ($category->type == 'MEDIA') {
	$data->media_title = $data->title;
	unset($data->title);
	
	$data->media_publisher = $data->publisher;
	unset($data->publisher);
	
	$data->media_date = $data->page_no;
	unset($data->page_no);
	
	$data->media_link = $data->link;
	unset($data->link);
}

$author = array();
$authors = $this->db()->select($this->table->author,'midx,name,sort')->where('aidx',$idx)->orderBy('sort','asc')->get();
for ($i=0, $loop=count($authors);$i<$loop;$i++) {
	if ($authors[$i]->midx > 0) {
		$member = $this->IM->getModule('member')->getMember($authors[$i]->midx);
		$authors[$i]->name = $member->name.'('.$this->getAuthorName($member).')';
	}
}

$data->author = json_encode($authors,JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);

$data->file = $data->file == 0 ? null : $this->IM->getModule('attachment')->getFileInfo($data->file);

$results->success = true;
$results->data = $data;
?>