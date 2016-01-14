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
					<div>
					<div class="tag-box">
						<p><span class="tag-badge"><a href='<?=$this->url->create('question/tag').'/'.$tag->getProperties()['id']?>'
							title='<?=$tag->getProperties()['description']?>'><?=$tag->name?></a></span>
							&nbsp;x&nbsp;<?=$tag->getProperties()['taggedquestions']?></p>
							<p><?=$tag->getProperties()['description']?></p>
							<?php if ($this->di->UserloginController->checkLoginAdmin($this->di->session->get('id'))): ?>
								<a class='edit-button' href='<?=$this->url->create("tag/update/".$tag->getProperties()['id'])?>' title='Redigera'><i class="fa fa-pencil"></i> Redigera ämne</a>
							<?php endif; ?>
						</div>
					</div>
					</td>

					<?php if ($rowcounter == 5): ?>
					</tr>
				<?php endif; ?>
				<?php $rowcounter++; ?>
			<?php endforeach; ?>
		</table>
			<?=$pages?>
	</article>
