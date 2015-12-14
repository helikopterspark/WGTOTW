<article class='article1'>
	<h2><?=$title?></h2>
	<?php foreach ($content as $tag) : ?>
		<div class='tag-box'>
		<h6><a href='<?=$this->url->create('question/tag').'/'.$tag->getProperties()['id']?>'
			title='<?=$tag->getProperties()['description']?>'><?=$tag->getProperties()['name']?></a></h6><div><p>&nbsp;x&nbsp;<?=$tag->taggedquestions?></p></div>
		<p><?=$tag->getProperties()['description']?></p>
	</div> <!-- tagbox -->
	<?php endforeach; ?>
</article>
