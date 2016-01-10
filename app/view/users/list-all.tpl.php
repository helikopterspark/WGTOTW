<article class='article1'>
	<h2><?=$title?></h2>
	<table class="tag-table">
		<?php $rowcounter = 1; ?>
		<?php foreach ($users as $user) : ?>
			<?php if ($rowcounter == 5 || $rowcounter == 1): ?>
				<?php $rowcounter = 1; ?>
				<tr>
				<?php endif; ?>
				<td width="25%">
					<div>
                        <div class="userinfo-overview-cell">
                        <p><a href='<?=$this->url->create('users/id').'/'.$user->getProperties()['id']?>'
    						title='<?=$user->getProperties()['acronym']?>'>
                            <img src="<?=$user->gravatar?>" alt="<?=$user->getProperties()['acronym']?>"/></a>
					        <a href='<?=$this->url->create('users/id').'/'.$user->getProperties()['id']?>'
						title='<?=$user->getProperties()['acronym']?>'><?=$user->getProperties()['acronym']?></a><br>
						Karma: <?=$user->stats?></p>
                        </div>
                    </div>
					</td>

					<?php if ($rowcounter == 5): ?>
					</tr>
				<?php endif; ?>
				<?php $rowcounter++; ?>
			<?php endforeach; ?>
		</table>
	</article>
