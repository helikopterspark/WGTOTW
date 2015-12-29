<!-- Index page for answers -->
<article class='article1'>
	<?php foreach ($content as $answer) : ?>
		<div id='answer-<?=$answer->getProperties()['id']?>' class='answer-shortlist-container'>
			<div class='answer-accepted'>
				<?php if ($answer->getProperties()['accepted']) : ?>
					<p><i class="fa fa-check fa-2x"></i></p>
				<?php endif; ?>
			</div>
		<p><i class="fa fa-exclamation"></i> <?=$answer->getProperties()['data']?></p>

		<?php $timestamp = strtotime($answer->getProperties()['created']); ?>
		<p class=smaller-text><a href='<?=$this->url->create('users/id').'/'.$answer->user->getProperties()['id']?>'>
			<img src='<?=$answer->user->gravatar?>' alt='Gravatar'>
			&nbsp;<?=$answer->user->getProperties()['name']?></a> svarade för
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
		</p>
		<p>Rank&nbsp;<?=$answer->getProperties()['upvotes'] - $answer->getProperties()['downvotes']?>
			&nbsp;<span class='upvote'><i class="fa fa-thumbs-o-up"></i>&nbsp;<?=$answer->getProperties()['upvotes']?></span>
		&nbsp;<span class='downvote'><i class="fa fa-thumbs-o-down"></i>&nbsp;<?=$answer->getProperties()['downvotes']?></span></p>
	</div> <!-- answer-shortlist-container -->
	<?php endforeach; ?>
</article>
