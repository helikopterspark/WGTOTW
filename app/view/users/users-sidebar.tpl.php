<article class='article1'>
<h2>Administrera</h2>
<p><i class="fa fa-user-plus"></i> <a href="<?=$this->url->create('users/add')?>">Lägg till användare</a></p>
<p><i class="fa fa-users"></i> <a href="<?=$this->url->create('users')?>">Visa alla</a><br/>
<i class="fa fa-users"></i> <a href="<?=$this->url->create('users/active')?>">Visa aktiva</a><br/>
	<span class='inactive'><i class="fa fa-users"></i></span> <a href="<?=$this->url->create('users/inactive')?>">Visa inaktiva</a><br/>
	<i class="fa fa-users"></i> <a href="<?=$this->url->create('users/nondeleted')?>">Visa aktiva och inaktiva</a><br/>
	<i class="fa fa-trash-o"></i> <a href="<?=$this->url->create('users/trash')?>">Visa papperskorgen</a></p>
	<p><i class="fa fa-database"></i> <a href="<?=$this->url->create('users/setup')?>">Återställ databasen</a></p>
	</article>
