	<div class='comments-heading-container'>
		<div class='comments-heading'>
			<?php if (count($comments) == 1) : ?>
				<p><i class="fa fa-comment"></i> <?=count($comments)?> Kommentar</p>
			<?php else : ?>
				<p><i class="fa fa-comments"></i> <?=count($comments)?> Kommentarer</p>
			<?php endif; ?>
		</div>
		<div class='comments-heading-side'>
			<?php if ($sorting == 'ASC') $button_text = "äldsta"; else $button_text = "senaste"; ?>
			<p class='button-right'><a class='sort-button' href='<?=$this->url->create("{$this->request->getRoute()}?sorting=$sorting#comments")?>' title='Ändra sortering'><i class="fa fa-sort"></i> Visa <?=$button_text?> kommentar först</a></p>
		</div>
	</div> <!-- comments-heading-container -->
