<article class='article1'>
	<h2><?=$title?></h2>
	<table class="tag-table">
		<?php $rowcounter = 1; ?>
		<?php foreach ($content as $tag) : ?>
			<?php if ($rowcounter == 5 || $rowcounter == 1): ?>
				<?php $rowcounter = 1; ?>
				<tr>
				<?php endif; ?>
				<td>
					<div class="tag-box">
						<p><span class="tag-badge"><a href='<?=$this->url->create('question/tag').'/'.$tag->getProperties()['id']?>'
							title='<?=$tag->getProperties()['description']?>'><?=$tag->name?></a></span>
							&nbsp;x&nbsp;<?=$tag->getProperties()['taggedquestions']?></p>
							<p><?=$tag->getProperties()['description']?></p>
						</div>
					</td>

					<?php if ($rowcounter == 5): ?>
					</tr>
				<?php endif; ?>
				<?php $rowcounter++; ?>
			<?php endforeach; ?>
		</table>
	</article>
