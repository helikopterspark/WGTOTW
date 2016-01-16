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
			Medlem sedan: <?=$user->getProperties()['created']?><br/>
			<!-- Aktiverad: <?=$user->getProperties()['active']?><br/> -->
			<?php if ($user->getProperties()['updated']) : ?>
				Uppdaterad: <?=$user->getProperties()['updated']?><br/>
			<?php endif; ?>
			<?php if ($user->getProperties()['deleted']) : ?>
				Avslutat konto: <?=$user->getProperties()['deleted']?><br/>
			<?php endif; ?>
		</p>
	</div> <!-- userdetail-center-info -->
	<?php if ($this->di->UserloginController->checkLoginCorrectUser($user->getProperties()['id'])) : ?>
		<div class="user-actions">
			<?php if ($user->getProperties()['deleted']): ?>
				<h4>Avslutat konto</h4>
			<?php endif; ?>
			<p>
				<a href="<?=$this->url->create('users/update').'/' . $user->getProperties()['id']?>"><i class="fa fa-pencil"></i> Redigera</a>
				<?php if (!$user->getProperties()['deleted']) : ?>
					<br><a href="<?=$this->url->create('users/softdelete').'/'.$user->getProperties()['id']?>"><i class="fa fa-trash-o"></i> Avsluta konto</a>
				<?php else : ?>
					<?php if ($this->di->session->get('isAdmin')): ?>
						<br><a href="<?=$this->url->create('users/delete').'/'.$user->getProperties()['id']?>"><i class="fa fa-times"></i> Radera alla uppgifter</a>
					<?php endif; ?>
					<br><a href="<?=$this->url->create('users/undosoftdelete').'/'.$user->getProperties()['id']?>"><i class="fa fa-check"></i> Återskapa konto</a>
				<?php endif; ?>
			</p>
			<?php if ($this->di->session->get('acronym') == $user->getProperties()['acronym']): ?>
				<p><a href="<?=$this->di->get('url')->create('logout')?>" title="Logga ut"><i class="fa fa-sign-out"></i>Logga ut</a></p>
			<?php endif; ?>
		</div> <!-- user-actions -->
	<?php endif; ?>
	<hr>
	<h3>Aktivitet</h3>
