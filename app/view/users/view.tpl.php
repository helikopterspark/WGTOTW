<article class='article1'>
	<h4>Användarnamn: <a href="<?=$this->url->create('users/id').'/'.$users[0]->getProperties()['id']?>"><?=$users[0]->getProperties()['acronym']?></a></h4>
		<p>Namn: <?=$users[0]->getProperties()['name']?><br/>
			ID: <?=$users[0]->getProperties()['id']?><br/>
			Email: <?=$users[0]->getProperties()['email']?><br/>
			Lösenord: <?=$users[0]->getProperties()['password']?><br/>
			Skapad: <?=$users[0]->getProperties()['created']?><br/>
			Aktiverad: <?=$users[0]->getProperties()['active']?><br/>
			<?php if ($users[0]->getProperties()['updated']) : ?>
				Uppdaterad: <?=$users[0]->getProperties()['updated']?><br/>
			<?php endif; ?>
			<?php if ($users[0]->getProperties()['deleted']) : ?>
				Borttagen: <?=$users[0]->getProperties()['deleted']?><br/>
			<?php endif; ?>
			<a href="<?=$this->url->create('users/update').'/' . $users[0]->getProperties()['id']?>">Redigera <i class="fa fa-pencil"></i></a>
			<?php if (!$users[0]->getProperties()['deleted']) : ?>
				| <a href="<?=$this->url->create('users/softdelete').'/'.$users[0]->getProperties()['id']?>">Släng <i class="fa fa-trash-o"></i></a></p>
			<?php else : ?>
				| <a href="<?=$this->url->create('users/delete').'/'.$users[0]->getProperties()['id']?>">Radera <i class="fa fa-times"></i></a>
				 | <a href="<?=$this->url->create('users/undosoftdelete').'/'.$users[0]->getProperties()['id']?>">Hämta från papperskorg <i class="fa fa-check"></i></a></p>
			<?php endif; ?>

			<p><a href='<?=$this->url->create('users-')?>'>Översikt</a></p>
</article>