	<div class='comments-heading-container'>
		<!--
		<div class='comments-heading'>
			<?php if (count($comments) == 1) : ?>
				<p><i class="fa fa-comment"></i> <?=count($comments)?> Kommentar</p>
			<?php else : ?>
				<p><i class="fa fa-comments"></i> <?=count($comments)?> Kommentarer</p>
			<?php endif; ?>
		</div> -->
		<div class='comments-heading-side'>
			<?php if ($sorting == 'ASC') $button_text = '<i class="fa fa-caret-down"></i>'; else $button_text = '<i class="fa fa-caret-up"></i>'; ?>
			<p class='button-right'><a class='sort-button' href='<?=$this->url->create("{$this->request->getRoute()}?sorting=$sorting#comments")?>' title='Ändra datumsortering'>Datum <?=$button_text?></a></p>
		</div>
	</div> <!-- comments-heading-container -->
