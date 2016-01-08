<article class='article1'>
	<h3>Relaterade taggar</h3>
    <?php foreach ($content as $tag): ?>
	<div class="tag-box">
	<p>
		<span class="tag-badge">
			<a href='<?=$this->url->create('question/tag').'/'.$tag->getProperties()['id']?>' title='<?=$tag->getProperties()['description']?>'><?=$tag->getProperties()['name']?></a>
		</span>
	</p>
	<p class="smaller-text"><?=$tag->getProperties()['description']?><p>
	</div>
    <?php endforeach; ?>
</article>
