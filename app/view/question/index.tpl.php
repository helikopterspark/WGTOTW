<!-- Index page for questions -->
<article class='article1'>
	<h2><?=$title?></h2>
	<?php foreach ($content as $question) : ?>
		<div id='question-<?=$question->getProperties()['id']?>' class='question-shortlist-container'>
			<div class='question-stats'><p><?=$question->getProperties()['noOfAnswers']?>&nbsp;svar</p></div>
		<h3><a href='<?=$this->url->create('question/id/'.$question->getProperties()['id'])?>'><?=$question->getProperties()['title']?></a></h3>
		<p><?=substr($question->getProperties()['content'], 0, 160)?>...</p>
		<p class='tags'>
		<?php foreach ($question->tags as $tag) : ?>
			<a href='<?=$this->url->create('question/tag').'/'.$tag->getProperties()['id']?>' title='<?=$tag->getProperties()['description']?>'><?=$tag->getProperties()['name']?></a>&nbsp;
		<?php endforeach; ?>
	</p>
		<?php $timestamp = strtotime($question->getProperties()['created']); ?>
		<p class=smaller-text>Frågan ställdes för
			<?php $timeinterval = time() - $timestamp; ?>
			<?php if (($timeinterval) < 60): ?>
				<?=round($timeinterval)?> sekunder sedan
			<?php elseif (($timeinterval/60) < 1.5): ?>
				<?=round($timeinterval/60)?> minut sedan
			<?php elseif (($timeinterval/60) < 60): ?>
				<?=round($timeinterval/60)?> minuter sedan
			<?php elseif (($timeinterval/(60*60)) < 1.5): ?>
				<?=round($timeinterval/(60*60))?> timme sedan
			<?php elseif (($timeinterval/(60*60)) < 24): ?>
				<?=round($timeinterval/(60*60))?> timmar sedan
			<?php elseif (($timeinterval/(60*60*24)) < 7): ?>
				<?=round($timeinterval/(60*60*24))?> dygn sedan
			<?php elseif (($timeinterval/(60*60*24)) < 10.5) : ?>
				<?=round($timeinterval/(60*60*24*7))?> vecka sedan
			<?php elseif (($timeinterval/(60*60*24)) < 30) : ?>
				<?=round($timeinterval/(60*60*24*7))?> veckor sedan
			<?php elseif (($timeinterval/(60*60*24)) < 45) : ?>
				<?=round($timeinterval/(60*60*24*7))?> månad sedan
			<?php else : ?>
				<?=round($timeinterval/(60*60*24*30))?> månader sedan
			<?php endif; ?>
		&nbsp;av <a href='<?=$this->url->create('users/id').'/'.$question->user->getProperties()['id']?>'>
			<?=$question->user->getProperties()['name']?>&nbsp;<img src='<?=$question->user->gravatar?>' alt='Gravatar'></a></p>
		<p>Rank&nbsp;<?=$question->getProperties()['upvotes'] - $question->getProperties()['downvotes']?>
			&nbsp;<span class='upvote'><i class="fa fa-thumbs-o-up"></i>&nbsp;<?=$question->getProperties()['upvotes']?></span>
		&nbsp;<span class='downvote'><i class="fa fa-thumbs-o-down"></i>&nbsp;<?=$question->getProperties()['downvotes']?></span></p>
	</div> <!-- question-shortlist-container -->
	<?php endforeach; ?>
</article>
