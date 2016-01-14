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
				<?php if ($this->di->UserloginController->checkLoginAdmin($this->di->session->get('id'))): ?>
					<a class='edit-button' href='<?=$this->url->create("tag/update/".$tag->getProperties()['id'])?>' title='Redigera'><i class="fa fa-pencil"></i> Redigera ämne</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</article>
