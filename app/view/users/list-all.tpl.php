
<article class='article1'>

	<h2><?=$title?></h2>

	<table>
		<thead>
			<tr>
				<td>ID</td><td>Akronym</td><td>Namn</td><td><i class="fa fa-pencil"></i></td><td style='text-align: center;'><i class="fa fa-trash-o"></i> / <i class="fa fa-times"></i> / <i class="fa fa-check"></i></td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $user) : ?>
				<tr>
					<td><?=$user->getProperties()['id']?></td>
					<td>
						<?php if ($user->getProperties()['deleted']) : ?>
							<span class='deleted'>
							<?php elseif (!$user->getProperties()['active']) : ?>
								<span class='inactive'>

								<?php else : ?>
									<span>
									<?php endif; ?>
									<a href="<?=$this->url->create('users/id').'/'.$user->getProperties()['id']?>"><?=$user->getProperties()['acronym']?></a></span>
								</td>
								<td>
									<?=$user->getProperties()['name']?></td>
									<?php if ($this->di->session->has('acronym') && $this->di->session->get('acronym') === $user->getProperties()['acronym'] || $this->di->session->get('isAdmin')) : ?>
										<td><a href="<?=$this->url->create('users/update').'/' . $user->getProperties()['id']?>"><i class="fa fa-pencil"></i></a></td>
										<td class="centered">
											<?php if (!$user->getProperties()['deleted']) : ?>
												<a href="<?=$this->url->create('users/softdelete').'/'.$user->getProperties()['id']?>"><i class="fa fa-trash-o"></i></a>
											<?php else : ?>
												<a href="<?=$this->url->create('users/delete').'/'.$user->getProperties()['id']?>"><i class="fa fa-times"></i></a> |
												<a href="<?=$this->url->create('users/undosoftdelete').'/'.$user->getProperties()['id']?>"><i class="fa fa-check"></i></a>
											<?php endif; ?>
									<?php endif; ?>
								</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
</article>
