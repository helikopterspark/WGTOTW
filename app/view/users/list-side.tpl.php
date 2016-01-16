<article class="article2">
    <div class="user-top-list">
    <h4><?=$title?></h4>

        <?php foreach ($users as $user): ?>
            <p><img src='<?=$user->gravatar?>' alt='Gravatar'/> <?=$user->getProperties()['acronym']?> &#x2022; <?=$user->stats?></p>
        <?php endforeach; ?>

    <p>Forumet har <?=$totalusers?> aktiva användare</p>
</div>
</article>
