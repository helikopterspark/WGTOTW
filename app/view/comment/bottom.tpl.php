<?php if (!$noForm) : ?>
	<div class='comment-button-container'>
		<p><a class='comment-button' href='<?=$this->url->create("{$this->request->getRoute()}?{$type}comment=yes&postid={$postId}#comment-form")?>' title='Ny kommentar'><i class="fa fa-comment-o"></i>&nbsp;Ny kommentar</a></p>
	</div>
<?php endif; ?>
