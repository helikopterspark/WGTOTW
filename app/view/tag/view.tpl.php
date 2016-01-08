<article class='article2'>
	<h3><?=$title?></h3>
	<?php if ($tag): ?>
		<div class="tag-box">
			<p>
				<span class="tag-badge">
					<a href='<?=$this->url->create('question/tag').'/'.$tag->getProperties()['id']?>' title='<?=$tag->getProperties()['description']?>'><?=$tag->getProperties()['name']?></a>
				</span>
			</p>
			<p><?=$tag->getProperties()['description']?><p>
			</div>
		<?php endif; ?>
	</article>
