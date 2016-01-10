<!-- user detail view -->
<article class='article1'>
	<?=$flash?>
	<div class="userdetail-left-info">
		<div class="userdetail-karma-votes">
		<img src='<?=$user->gravatar?>' alt='Gravatar'>

			<h3>Karma: <?=$rank?></h3>
			<?php if ($votes == 1): ?>
				<p class="smaller-text"><?=$user->getProperties()['acronym']?> har röstat <?=$votes?> gång</p>
			<?php else : ?>
				<p class="smaller-text"><?=$user->getProperties()['acronym']?> har röstat <?=$votes?> gånger</p>
			<?php endif; ?>
		</div>

	</div> <!-- userdetail-left-info -->
	<div class="userdetail-center-info">
		<h3><?=$user->getProperties()['acronym']?></h3>
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
		</p>
		<?php if ($this->di->UserloginController->checkLoginCorrectUser($user->getProperties()['id'])) : ?>
			<a href="<?=$this->url->create('users/update').'/' . $user->getProperties()['id']?>">Redigera <i class="fa fa-pencil"></i></a>
			<?php if (!$user->getProperties()['deleted']) : ?>
				| <a href="<?=$this->url->create('users/softdelete').'/'.$user->getProperties()['id']?>">Avsluta konto <i class="fa fa-trash-o"></i></a>
			<?php else : ?>
				| <a href="<?=$this->url->create('users/delete').'/'.$user->getProperties()['id']?>">Radera alla uppgifter (går ej att ångra) <i class="fa fa-times"></i></a>
				| <a href="<?=$this->url->create('users/undosoftdelete').'/'.$user->getProperties()['id']?>">Återskapa konto <i class="fa fa-check"></i></a>
			<?php endif; ?>
		<?php endif; ?>
	</div> <!-- userdetail-center-info -->
	<hr>
	<h3>Aktivitet</h3>
