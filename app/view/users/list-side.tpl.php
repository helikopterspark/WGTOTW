<article class="article2">
    <div class="user-top-list">
    <h4><?=$title?></h4>

        <?php foreach ($users as $user): ?>
            <p>
                <a href='<?=$this->url->create('users/id').'/'.$user->getProperties()['id']?>'><img src='<?=$user->gravatar?>' alt='Gravatar'/></a>
                 <a href='<?=$this->url->create('users/id').'/'.$user->getProperties()['id']?>'><?=$user->getProperties()['acronym']?></a> &#x2022; <?=$user->stats?>
            </p>
        <?php endforeach; ?>

    <p>Forumet har <?=$totalusers?> aktiva användare</p>
</div>
</article>
