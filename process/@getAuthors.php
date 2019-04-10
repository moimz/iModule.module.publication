<?php
/**
 * 이 파일은 iModule 출판물관리모듈의 일부입니다. (https://www.imodules.io)
 *
 * 저자 회원라벨에서 저자목록을 가져온다.
 * 
 * @file /modules/publication/process/@getAuthors.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2019. 4. 9.
 */
if (defined('__IM__') == false) exit;

$start = Request('start');
$limit = Request('limit');
$sort = Request('sort');
$dir = Request('dir');
$keyword = Request('keyword');

$mMember = $this->IM->getModule('member');
$lists = $this->db()->select($mMember->getTable('member').' m','m.idx')->join($mMember->getTable('member_label').' l','l.idx=m.idx','LEFT')->where('l.label',$this->getModule()->getConfig('author_label'));
if ($keyword) $lists->where('(name like ? or nickname like ?)',array('%'.$keyword.'%','%'.$keyword.'%'));
$total = $lists->copy()->count();
$lists = $lists->orderBy($sort,$dir)->limit($start,$limit)->get();
for ($i=0, $loop=count($lists);$i<$loop;$i++) {
	$member = $mMember->getMember($lists[$i]->idx);
	
	$lists[$i]->name = $member->name.'('.$this->getAuthorName($member).')';
	$lists[$i]->email = $member->email;
	$lists[$i]->count = $this->db()->select($this->table->author)->where('midx',$member->idx)->count();
}

$results->success = true;
$results->lists = $lists;
$results->total = $total;
?>