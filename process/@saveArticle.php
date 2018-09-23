<?php
/**
 * 이 파일은 iModule 출판물관리모듈의 일부입니다. (https://www.imodules.io)
 *
 * 출판물을 저장한다.
 * 
 * @file /modules/publication/process/@saveArticle.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 2. 1.
 */
if (defined('__IM__') == false) exit;

$idx = Request('idx');
$category = Request('category') ? $this->getCategory(Request('category')) : null;
if ($category == null) {
	$results->success = false;
	$results->errors = array('category'=>$this->getErrorText('NOT_FOUND'));
	return;
}
if ($idx) {
	$data = $this->db()->select($this->table->article)->where('idx',$idx)->getOne();
	if ($data == null) {
		$results->success = false;
		$results->message = $this->getErrorText('NOT_FOUND');
		return;
	}
	$fileIdx = $data->file;
} else {
	$fileIdx = 0;
}

$errors = array();
if ($category->type == 'PAPER') {
	$title = Request('paper_title') ? Request('paper_title') : $errors['paper_title'] = $this->getErrorText('REQUIRED');
	$publisher = Request('paper_publisher') ? Request('paper_publisher') : $errors['paper_publisher'] = $this->getErrorText('REQUIRED');
	$year = Request('paper_year') && is_numeric(Request('paper_year')) == true ? Request('paper_year') : $errors['paper_year'] = $this->getErrorText('REQUIRED');
	$volume_no = Request('paper_volume_no') !== null && is_numeric(Request('paper_volume_no')) == true ? Request('paper_volume_no') : $errors['paper_volume_no'] = $this->getErrorText('REQUIRED');
	$issue_no = Request('paper_issue_no') !== null && is_numeric(Request('paper_issue_no')) == true ? Request('paper_issue_no') : $errors['paper_issue_no'] = $this->getErrorText('REQUIRED');
	$page_no = Request('paper_page_no') ? Request('paper_page_no') : $errors['paper_page_no'] = $this->getErrorText('REQUIRED');
	$abstract = Request('paper_abstract') ? Request('paper_abstract') : $errors['paper_abstract'] = $this->getErrorText('REQUIRED');
	$link = Request('paper_link');
	$keyword = Request('paper_keyword');
}

if ($category->type == 'THESIS') {
	$title = Request('thesis_title') ? Request('thesis_title') : $errors['thesis_title'] = $this->getErrorText('REQUIRED');
	$publisher = 0;
	$year = Request('thesis_year') && is_numeric(Request('thesis_year')) == true ? Request('thesis_year') : $errors['thesis_year'] = $this->getErrorText('REQUIRED');
	$volume_no = Request('thesis_month') && is_numeric(Request('thesis_month')) == true ? Request('thesis_month') : $errors['thesis_month'] = $this->getErrorText('REQUIRED');
	$issue_no = 0;
	$page_no = Request('thesis_type') ? Request('thesis_type') : $errors['thesis_type'] = $this->getErrorText('REQUIRED');
	$abstract = '';
	$link = '';
	$keyword = '';
}

if ($category->type == 'CONFERENCE') {
	$title = Request('conference_title') ? Request('conference_title') : $errors['conference_title'] = $this->getErrorText('REQUIRED');
	$publisher = Request('conference_publisher') ? Request('conference_publisher') : $errors['conference_publisher'] = $this->getErrorText('REQUIRED');
	
	$check = $this->getPublisher($publisher);
	$year = date('Y',strtotime($check->start_date));
	$volume_no = strtotime($check->start_date);
	$issue_no = strtotime($check->end_date);
	$page_no = Request('conference_type') ? Request('conference_type') : $errors['conference_type'] = $this->getErrorText('REQUIRED');
	$abstract = '';
	$link = '';
	$keyword = '';
}

if ($category->type == 'PATENT') {
	$title = Request('patent_title') ? Request('patent_title') : $errors['patent_title'] = $this->getErrorText('REQUIRED');
	$publisher = 0;
	$volume_no = Request('patent_type') ? Request('patent_type') : $errors['patent_type'] = $this->getErrorText('REQUIRED');
	$page_no = Request('patent_date') ? Request('patent_date') : $errors['patent_date'] = $this->getErrorText('REQUIRED');
	$issue_no = 0;
	$year = date('Y',strtotime($page_no));
	$abstract = '';
	$link = '';
	$keyword = Request('patent_no') ? Request('patent_no') : $errors['patent_no'] = $this->getErrorText('REQUIRED');
}

if ($category->type == 'BOOK') {
	$title = Request('book_title') ? Request('book_title') : $errors['book_title'] = $this->getErrorText('REQUIRED');
	$publisher = Request('book_publisher') ? Request('book_publisher') : $errors['book_publisher'] = $this->getErrorText('REQUIRED');
	$year = Request('book_year') && is_numeric(Request('book_year')) == true ? Request('book_year') : $errors['book_year'] = $this->getErrorText('REQUIRED');
	$volume_no = 0;
	$issue_no = 0;
	$page_no = Request('book_page_no') ? Request('book_page_no') : '';
	$abstract = Request('book_abstract') ? Request('book_abstract') : $errors['book_abstract'] = $this->getErrorText('REQUIRED');
	$link = Request('book_link');
	$keyword = Request('book_keyword') ? Request('book_keyword') : $errors['book_keyword'] = $this->getErrorText('REQUIRED');
}

if ($category->type == 'MEDIA') {
	$title = Request('media_title') ? Request('media_title') : $errors['media_title'] = $this->getErrorText('REQUIRED');
	$publisher = Request('media_publisher') ? Request('media_publisher') : $errors['media_publisher'] = $this->getErrorText('REQUIRED');
	$page_no = Request('media_date') ? Request('media_date') : $errors['media_date'] = $this->getErrorText('REQUIRED');
	$year = date('Y',strtotime($page_no));
	$volume_no = 0;
	$issue_no = 0;
	$abstract = '';
	$link = Request('media_link');
	$keyword = '';
}

$keywords = array();
$keyword = explode(';',$keyword);
for ($i=0, $loop=count($keyword); $i<$loop;$i++) {
	if (strlen(trim($keyword[$i])) > 0) $keywords[] = trim($keyword[$i]);
}
$keyword = implode(';',$keywords);

$author = json_decode(Request('author'));

if ($author == null || count($author) == 0) {
	$author = array();
}

if (count($errors) == 0) {
	$insert = array();
	$insert['category'] = $category->idx;
	$insert['title'] = $title;
	$insert['publisher'] = $publisher;
	$insert['year'] = $year;
	$insert['volume_no'] = $volume_no;
	$insert['issue_no'] = $issue_no;
	$insert['page_no'] = $page_no;
	$insert['abstract'] = $abstract;
	$insert['keyword'] = $keyword;
	$insert['link'] = $link;
	
	if ($idx) {
		$this->db()->update($this->table->article,$insert)->where('idx',$idx)->execute();
	} else {
		$idx = $this->db()->insert($this->table->article,$insert)->execute();
	}
	
	$author_midxes = array();
	$author_names = array();
	for ($i=0, $loop=count($author);$i<$loop;$i++) {
		if ($author[$i]->midx > 0) $author_midxes[] = $author[$i]->midx;
		else $author_names[] = trim($author[$i]->name);
		$this->db()->replace($this->table->author,array('aidx'=>$idx,'midx'=>$author[$i]->midx,'name'=>($author[$i]->midx == 0 ? trim($author[$i]->name) : ''),'sort'=>$i))->execute();
	}
	
	$remove = $this->db()->delete($this->table->author)->where('aidx',$idx);
	if (count($author_midxes) > 0) $remove->where('midx',0,'!=')->where('midx',$author_midxes,'NOT IN');
	else $remove->where('midx',0,'!=');
	$remove->execute();
	
	$remove = $this->db()->delete($this->table->author)->where('aidx',$idx);
	if (count($author_names) > 0) $remove->where('midx',0)->where('name',$author_names,'NOT IN');
	else $remove->where('midx',0);
	$remove->execute();
	
	if ($fileIdx > 0 && Request('file_delete')) {
		$this->IM->getModule('attachment')->fileDelete($fileIdx);
		$fileIdx = 0;
	}
	
	if (isset($_FILES['file']) == true && $_FILES['file']['tmp_name']) {
		if ($fileIdx == 0) {
			$fileIdx = $this->IM->getModule('attachment')->fileSave($_FILES['file']['name'],$_FILES['file']['tmp_name'],'publication','article','PUBLISHED',true);
		} else {
			$fileIdx = $this->IM->getModule('attachment')->fileReplace($imageIdx,$_FILES['file']['name'],$_FILES['file']['tmp_name'],true);
		}
	}
	
	if ($fileIdx !== false) {
		$this->db()->update($this->table->article,array('file'=>$fileIdx))->where('idx',$idx)->execute();
	}
	
	$results->success = true;
} else {
	$results->success = false;
	$results->errors = $errors;
}
?>