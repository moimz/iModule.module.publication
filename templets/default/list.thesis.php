<?php
/**
 * 이 파일은 iModule 출판물관리모듈의 일부입니다. (https://www.imodules.io)
 *
 * 출판물관리모듈 기본템플릿 (목록보기)
 * 
 * @file /modules/publication/templets/default/list.thesis.php
 * @author Arzz (arzz@arzz.com)
 * @license MIT License
 * @version 3.0.0
 * @modified 2018. 8. 29.
 */
if (defined('__IM__') == false) exit;
?>
<div data-role="tabbar">
	<div>
		<ul>
			<li<?php echo $mode == 'year' ? ' class="selected"' : ''; ?>><a href="<?php echo $me->getUrl('list','year'); ?>">연도별</a></li>
			<li<?php echo $mode == 'author' ? ' class="selected"' : ''; ?>><a href="<?php echo $me->getUrl('list','author'); ?>">저자별</a></li>
		</ul>
	</div>
</div>

<div class="searchbox">
	<ul>
		<li class="input">
			<ul>
				<?php if ($mode == 'year') { ?>
				<li>
					<label>연도</label>
					<div>
						<div data-role="input">
							<select name="code">
								<option value="">전체</option>
								<?php foreach ($selectors as $selector) { ?>
								<option value="<?php echo $selector->year; ?>"<?php echo $code == $selector->year ? ' selected="selected"' : ''; ?>><?php echo $selector->year.'년도 ('.number_format($selector->count).'건)'; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</li>
				<?php } ?>
				
				<?php if ($mode == 'author') { ?>
				<li>
					<label>저자</label>
					<div>
						<div data-role="input">
							<select name="code">
								<option value="">전체</option>
								<?php foreach ($selectors as $selector) { ?>
								<option value="<?php echo $selector->idx; ?>"<?php echo $code == $selector->idx ? ' selected="selected"' : ''; ?>><?php echo $me->getAuthorName($IM->getModule('member')->getMember($selector->idx)).' ('.number_format($selector->count).'건)'; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</li>
				<?php } ?>
				
				<li>
					<label>구분</label>
					<div>
						<div data-role="input">
							<select name="page_no">
								<option value="">전체</option>
								<option value="Ph.D"<?php echo $page_no == 'Ph.D' ? ' selected="selected"' : ''; ?>>Ph.D</option>
								<option value="MS"<?php echo $page_no == 'MS' ? ' selected="selected"' : ''; ?>>MS</option>
							</select>
						</div>
					</div>
				</li>
				<li>
					<label>검색어</label>
					<div>
						<div data-role="input">
							<input type="search" name="keyword" placeholder="논문명" value="<?php echo GetString($keyword,'input'); ?>">
						</div>
					</div>
				</li>
			</ul>
		</li>
		<li class="button">
			<button type="submit">검색하기</button>
		</li>
	</ul>
</div>

<?php if ($author != null) { ?>
<div data-role="author">
	<i class="photo" style="background-image:url(<?php echo $author->photo; ?>);"></i>
	
	<div>
		<b><?php echo $me->getAuthorName($author); ?></b>
		<ul>
			<li><?php echo $me->getText('step/'.$author->extras->step); ?></li>
			<li>Email : <?php echo $author->email; ?></li>
		</ul>
	</div>
</div>
<?php } ?>

<h4>Total Results : <?php echo number_format($total); ?></h4>

<ul data-role="list">
	<?php foreach ($lists as $item) { ?>
	<li>
		<small><?php echo $item->loopnum; ?>.</small>
		<b><label data-role="<?php echo $item->page_no; ?>"><?php echo $item->page_no; ?></label><?php echo $item->title; ?><?php echo $item->file != null ? '<a href="'.$item->file->download.'" download="'.$item->file->name.'"><i class="icon" style="background-image:url('.$item->file->icon.');">'.$item->file->name.'</i></a>' : ''; ?></b>
		
		<?php if (count($item->author) > 0) { ?>
		<div class="author">
			<i class="xi xi-users"></i>
			<?php foreach ($item->author as $member) { ?>
				<?php if ($member->midx == 0) { ?><span><span><?php echo $member->name; ?></span></span><?php } ?>
				<?php if ($member->midx > 0) { $member = $IM->getModule('member')->getMember($member->midx); ?><span><a href="<?php echo $me->getUrl('list','author/'.$member->idx); ?>"><?php echo $me->getModule()->getConfig('author_photo') == true ? '<i class="photo" style="background-image:url('.$member->photo.');"></i>' : ''; ?><?php echo $me->getAuthorName($member); ?></a></span><?php } ?>
			<?php } ?>
		</div>
		<?php } ?>
		
		<div class="publisher">
			<i class="fa fa-calendar-check-o"></i> <?php echo date('F',mktime(0,0,0,$item->volume_no,1,$item->year)); ?>, <a href="<?php echo $me->getUrl('list','year/'.$item->year); ?>"><?php echo $item->year; ?></a>
		</div>
	</li>
	<?php } ?>
</ul>

<div class="pagination"><?php echo $pagination; ?></div>