<?php if (!$noForm) : ?>
	<div class='comment-button-container'>
		<a class='edit-button' href='<?=$this->url->create("{$this->request->getRoute()}?{$type}comment=yes&postid={$postId}#comment-form")?>' title='Ny kommentar'><i class="fa fa-comment-o"></i>&nbsp;Ny kommentar</a>
	</div>
<?php endif; ?>
