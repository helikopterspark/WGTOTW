<article class='article2'>
	<h3><?=$title?></h3>
    <h4>Populära taggar</h4>
    <?php foreach ($content as $tag): ?>
	<div class="tag-box">
	<p>
		<span class="tag-badge">
			<a href='<?=$this->url->create('question/tag').'?tag='.$tag->getProperties()['id']?>' title='<?=$tag->getProperties()['description']?>'>
                <?=$tag->getProperties()['name']?>
            </a>
		</span>
         x <?=$tag->getProperties()['taggedquestions']?>
	</p>
	</div>
    <?php endforeach; ?>
</article>
