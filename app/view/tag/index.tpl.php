<article class='article1'>
	<h2><?=$title?></h2>
	<table>
		<?php $rowcounter = 1; ?>
		<?php foreach ($content as $tag) : ?>
			<?php if ($rowcounter == 6): ?>
				<?php $rowcounter = 1; ?>
				<tr>
				<?php endif; ?>
				<td>
					<span class="tag-box">
					<p><span class="tag-badge"><a href='<?=$this->url->create('question/tag').'/'.$tag->getProperties()['id']?>'
						title='<?=$tag->getProperties()['description']?>'><?=$tag->name?></a></span>
						&nbsp;x&nbsp;<?=$tag->getProperties()['taggedquestions']?></p>
						<p><?=$tag->getProperties()['description']?></p>
					</td>
				</span>
					<?php if ($rowcounter == 6): ?>
					</tr>
				<?php endif; ?>
				<?php $rowcounter++; ?>
			<?php endforeach; ?>
		</table>
	</article>
