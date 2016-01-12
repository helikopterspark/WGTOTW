<!-- Detail view for question -->
<article class='article1'>
	<?=$flash?>
	<div id='question-<?=$question->getProperties()['id']?>' class='question-container'>
		<h3 class="question-detail-title-heading"><a href='<?=$this->url->create('question/id/'.$question->getProperties()['id'])?>'><?=$question->getProperties()['title']?></a></h3>
		<div class="question-detail-stats">
			<p>
				<?php if (!$vote): ?>
					<span class="smaller-text"><?=$question->getProperties()['upvotes']?></span><br>
					<a class='upvote-active' href='<?=$this->url->create("question/upvote/".$question->getProperties()['id'])?>' title='Bra fråga'><i class="fa fa-caret-up fa-3x"></i></a><br>
					<?=$question->getProperties()['upvotes'] - $question->getProperties()['downvotes']?><br>
					<a class='downvote-active' href='<?=$this->url->create("question/downvote/".$question->getProperties()['id'])?>' title='Mindre bra fråga'><i class="fa fa-caret-down fa-3x"></i></a><br>
					<span class="smaller-text"><?=$question->getProperties()['downvotes']?></span>
				<?php else : ?>
					<span class="smaller-text"><?=$question->getProperties()['upvotes']?></span><br>
					<span class='upvote'><i class="fa fa-caret-up fa-3x"></i></span><br>
					<?=$question->getProperties()['upvotes'] - $question->getProperties()['downvotes']?><br>
					<span class='downvote'><i class="fa fa-caret-down fa-3x"></i></span><br>
					<span class="smaller-text"><?=$question->getProperties()['downvotes']?></span>
				<?php endif; ?>
			</p>
		</div> <!-- question-detail-stats -->
		<div class="question-detail-content">
			<?=$question->filteredcontent?>
			<div class='tags'>
				<?php foreach ($question->tags as $tag) : ?>
					<div class="tag-badge"><a href='<?=$this->url->create('question/tag').'/'.$tag->getProperties()['id']?>' title='<?=$tag->getProperties()['description']?>'><?=$tag->getProperties()['name']?></a></div>
				<?php endforeach; ?>
			</div>
			<div class="question-detail-bottom">

				<?php if ($this->di->UserloginController->checkLoginCorrectUser($question->user->getProperties()['id'])): ?>
					<a class='edit-button' href='<?=$this->url->create("question/update/".$question->getProperties()['id'])?>' title='Redigera'><i class="fa fa-pencil"></i> Redigera fråga</a>
				<?php endif; ?>

				<?php $timestamp = strtotime($question->getProperties()['created']); ?>
				<div class="question-detail-userinfo smaller-text">
					<div><img src='<?=$question->user->gravatar?>' alt='Gravatar'></div>
					<div class="smaller-text">
						<a href='<?=$this->url->create('users/id').'/'.$question->user->getProperties()['id']?>'><?=$question->user->getProperties()['acronym']?></a>
						&nbsp;&#8226;&nbsp;<?=$question->user->stats?>
					</div>
					<div class="userinfo-text">
						<div class="smaller-text">
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
						</div>

					<?php if (isset($question->getProperties()['updated'])) : ?>
						<div class="smaller-text">
							<span class='italics'>Uppdaterad för
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
							</span></div>
						<?php endif; ?>
					</div> <!-- userinfo-text -->
				</div> <!-- question-detail-userinfo -->
			</div> <!-- question-detail-bottom -->
		</div> <!-- question-detail-content -->
	</div> <!-- question-container -->
</article>
