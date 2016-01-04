<!-- Detail view for question -->
<article class='article1'>
	<?=$flash?>
	<div id='question-<?=$question->getProperties()['id']?>' class='question-container'>
		<h3><i class="fa fa-question"></i> <a href='<?=$this->url->create('question/id/'.$question->getProperties()['id'])?>'><?=$question->getProperties()['title']?></a></h3>
		<p><?=$question->getProperties()['content']?></p>
		<p class='tags'>
			<?php foreach ($question->tags as $tag) : ?>
				<a href='<?=$this->url->create('question/tag').'/'.$tag->getProperties()['id']?>' title='<?=$tag->getProperties()['description']?>'><?=$tag->getProperties()['name']?></a>&nbsp;
			<?php endforeach; ?>
		</p>
		<?php $timestamp = strtotime($question->getProperties()['created']); ?>
		<p class=smaller-text><a href='<?=$this->url->create('users/id').'/'.$question->user->getProperties()['id']?>'>
			<img src='<?=$question->user->gravatar?>' alt='Gravatar'>&nbsp;
		<?=$question->user->getProperties()['name']?></a> frågade för
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

			<?php if (isset($question->getProperties()['updated'])) : ?>
				| <span class='italics'> Uppdaterad för
				<?php $timestamp = strtotime($question->getProperties()['updated']); ?>

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
				</span>
			<?php endif; ?>
			</p>
			<p>Rank&nbsp;<?=$question->getProperties()['upvotes'] - $question->getProperties()['downvotes']?>
				<?php if (!$vote): ?>
					&nbsp;<a class='upvote-active' href='<?=$this->url->create("question/upvote/".$question->getProperties()['id'])?>' title='Bra fråga'><i class="fa fa-thumbs-o-up"></i>&nbsp;<?=$question->getProperties()['upvotes']?></a>
					&nbsp;<a class='downvote-active' href='<?=$this->url->create("question/downvote/".$question->getProperties()['id'])?>' title='Mindre bra fråga'><i class="fa fa-thumbs-o-down"></i>&nbsp;<?=$question->getProperties()['downvotes']?></a>
				<?php else : ?>
					&nbsp;<span class='upvote'><i class="fa fa-thumbs-o-up"></i></span>&nbsp;<?=$question->getProperties()['upvotes']?>
					&nbsp;<span class='downvote'><i class="fa fa-thumbs-o-down"></i></span>&nbsp;<?=$question->getProperties()['downvotes']?>
				<?php endif; ?>
			</p>
					<?php if ($this->di->UserloginController->checkLoginCorrectUser($question->user->getProperties()['id'])): ?>
						<p><a class='edit-button' href='<?=$this->url->create("question/update/".$question->getProperties()['id'])?>' title='Redigera'><i class="fa fa-pencil"></i> Redigera fråga</a></p>
					<?php endif; ?>
				</div> <!-- question-container -->
			</article>
