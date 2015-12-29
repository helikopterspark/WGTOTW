<article class='article1'>
	<?=$flash?>
	<h4>Användarnamn: <a href="<?=$this->url->create('users/id').'/'.$users->getProperties()['id']?>"><?=$users->getProperties()['acronym']?></a></h4>
		<p>Namn: <?=$users->getProperties()['name']?><br/>
			ID: <?=$users->getProperties()['id']?><br/>
			Email: <?=$users->getProperties()['email']?><br/>
			Lösenord: <?=$users->getProperties()['password']?><br/>
			Skapad: <?=$users->getProperties()['created']?><br/>
			Aktiverad: <?=$users->getProperties()['active']?><br/>
			<?php if ($users->getProperties()['updated']) : ?>
				Uppdaterad: <?=$users->getProperties()['updated']?><br/>
			<?php endif; ?>
			<?php if ($users->getProperties()['deleted']) : ?>
				Borttagen: <?=$users->getProperties()['deleted']?><br/>
			<?php endif; ?>

			<?php if ($this->di->session->has('acronym') && $this->di->session->get('acronym') === $users->getProperties()['acronym'] || $this->di->session->get('isAdmin')) : ?>
				<a href="<?=$this->url->create('users/update').'/' . $users->getProperties()['id']?>">Redigera <i class="fa fa-pencil"></i></a>
				<?php if (!$users->getProperties()['deleted']) : ?>
					| <a href="<?=$this->url->create('users/softdelete').'/'.$users->getProperties()['id']?>">Släng <i class="fa fa-trash-o"></i></a></p>
				<?php else : ?>
					| <a href="<?=$this->url->create('users/delete').'/'.$users->getProperties()['id']?>">Radera <i class="fa fa-times"></i></a>
					| <a href="<?=$this->url->create('users/undosoftdelete').'/'.$users->getProperties()['id']?>">Hämta från papperskorg <i class="fa fa-check"></i></a></p>
				<?php endif; ?>
			<?php endif; ?>

			<p><a href='<?=$this->url->create('users')?>'>Översikt</a></p>
</article>
