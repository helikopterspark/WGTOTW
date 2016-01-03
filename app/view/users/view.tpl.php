<article class='article1'>
	<?=$flash?>
	<h4>Användarnamn: <a href="<?=$this->url->create('users/id').'/'.$user->getProperties()['id']?>"><?=$user->getProperties()['acronym']?></a></h4>
	<div class="right_float_with_margin">
		<h4>Karma: <?=$rank?></h4>
		<p><img src='<?=$user->gravatar?>' alt='Gravatar'></p>
	</div>
		<p>Namn: <?=$user->getProperties()['name']?><br/>
			Email: <a href="mailto:<?=$user->getProperties()['email']?>"><?=$user->getProperties()['email']?></a><br/>
			Webbsida: <?=$user->getProperties()['url']?><br/>
			Skapad: <?=$user->getProperties()['created']?><br/>
			Aktiverad: <?=$user->getProperties()['active']?><br/>
			<?php if ($user->getProperties()['updated']) : ?>
				Uppdaterad: <?=$user->getProperties()['updated']?><br/>
			<?php endif; ?>
			<?php if ($user->getProperties()['deleted']) : ?>
				Borttagen: <?=$user->getProperties()['deleted']?><br/>
			<?php endif; ?>

			<?php if ($this->di->UserloginController->checkLoginCorrectUser($user->getProperties()['id'])) : ?>
				<a href="<?=$this->url->create('users/update').'/' . $user->getProperties()['id']?>">Redigera <i class="fa fa-pencil"></i></a>
				<?php if (!$user->getProperties()['deleted']) : ?>
					| <a href="<?=$this->url->create('users/softdelete').'/'.$user->getProperties()['id']?>">Släng <i class="fa fa-trash-o"></i></a></p>
				<?php else : ?>
					| <a href="<?=$this->url->create('users/delete').'/'.$user->getProperties()['id']?>">Radera <i class="fa fa-times"></i></a>
					| <a href="<?=$this->url->create('users/undosoftdelete').'/'.$user->getProperties()['id']?>">Hämta från papperskorg <i class="fa fa-check"></i></a></p>
				<?php endif; ?>
			<?php endif; ?>

			<hr>
			<h3>Aktivitet</h3>
