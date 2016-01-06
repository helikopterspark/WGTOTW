<!-- Index page for questions -->
<article class='article1'>
	<h2><?=$title?></h2>
	<?php foreach ($content as $question) : ?>
		<div id='question-<?=$question->getProperties()['id']?>' class='question-shortlist-container'>
			<div class='question-stats'>
				<p class="stats-box"><?=$question->getProperties()['upvotes'] - $question->getProperties()['downvotes']?><br>rank</p>
				<?php if ($question->getProperties()['noOfAnswers'] > 0): ?>
					<p class="answers-exist"><?=$question->getProperties()['noOfAnswers']?><br>svar</p>
					<?php else : ?>
						<p class="stats-box"><?=$question->getProperties()['noOfAnswers']?><br>svar</p>
				<?php endif; ?>

			</div>
			<div class="question-shortlist-content">
		<h4 class="question-title-heading"><a href='<?=$this->url->create('question/id/'.$question->getProperties()['id'])?>'><?=$question->getProperties()['title']?></a></h4>
		<p><?=substr($question->getProperties()['content'], 0, 160)?>...</p>

		<?php foreach ($question->tags as $tag) : ?>
			<div class="tag-badge"><a href='<?=$this->url->create('question/tag').'/'.$tag->getProperties()['id']?>' title='<?=$tag->getProperties()['description']?>'><?=$tag->getProperties()['name']?></a></div>
		<?php endforeach; ?>

<div class="question-shortlist-userinfo">
		<?php $timestamp = strtotime($question->getProperties()['created']); ?>
		<p class=smaller-text><img src='<?=$question->user->gravatar?>' alt='Gravatar'>
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
			<br><a href='<?=$this->url->create('users/id').'/'.$question->user->getProperties()['id']?>'>
			<?=$question->user->getProperties()['acronym']?></a><br>
			Karma: <?=$question->user->stats?></p>
		</div> <!-- question-shortlist-userinfo -->
		</div> <!-- question-shortlist-content -->
	</div> <!-- question-shortlist-container -->
	<?php endforeach; ?>
</article>
