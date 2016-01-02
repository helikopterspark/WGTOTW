
<div class='comment-form-container' id='comment-editform-container'>
    <hr>
    <h4>Redigera kommentar:</h4>
    <?=$content?>
    <?php $timestamp = strtotime($comment->getProperties()['created']); ?>
    <p class=smaller-text><a href='<?=$this->url->create('users/id').'/'.$comment->user->getProperties()['id']?>'>
        <img src='<?=$comment->user->gravatar?>' alt='Gravatar'>
        &nbsp;<?=$comment->user->getProperties()['name']?></a> kommenterade för
        <?php $timeinterval = time() - $timestamp; ?>
        <?php if (($timeinterval) < 60): ?>
            <?=round($timeinterval)?> sekunder sedan
        <?php elseif (($timeinterval/60) < 1.5): ?>
            <?=round($timeinterval/60)?> minut sedan
        <?php elseif (($timeinterval/60) < 60): ?>
            <?=round($timeinterval/60)?> minuter sedan
        <?php elseif (($timeinterval/(60*60)) < 1.5): ?>
            <?=round($timeinterval/(60*60))?> timme sedan
        <?php elseif (($timeinterval/(60*60)) < 24): ?>
            <?=round($timeinterval/(60*60))?> timmar sedan
        <?php elseif (($timeinterval/(60*60*24)) < 7): ?>
            <?=round($timeinterval/(60*60*24))?> dygn sedan
        <?php elseif (($timeinterval/(60*60*24)) < 10.5) : ?>
            <?=round($timeinterval/(60*60*24*7))?> vecka sedan
        <?php elseif (($timeinterval/(60*60*24)) < 30) : ?>
            <?=round($timeinterval/(60*60*24*7))?> veckor sedan
        <?php elseif (($timeinterval/(60*60*24)) < 45) : ?>
            <?=round($timeinterval/(60*60*24*7))?> månad sedan
        <?php else : ?>
            <?=round($timeinterval/(60*60*24*30))?> månader sedan
        <?php endif; ?>

    <?php if (isset($comment->getProperties()['updated'])) : ?>
        | <span class='italics'> Uppdaterad för
        <?php $timestamp = strtotime($comment->getProperties()['updated']); ?>

        <?php $timeinterval = time() - $timestamp; ?>
        <?php if (($timeinterval) < 60): ?>
            <?=round($timeinterval)?> sekunder sedan
        <?php elseif (($timeinterval/60) < 1.5): ?>
            <?=round($timeinterval/60)?> minut sedan
        <?php elseif (($timeinterval/60) < 60): ?>
            <?=round($timeinterval/60)?> minuter sedan
        <?php elseif (($timeinterval/(60*60)) < 1.5): ?>
            <?=round($timeinterval/(60*60))?> timme sedan
        <?php elseif (($timeinterval/(60*60)) < 24): ?>
            <?=round($timeinterval/(60*60))?> timmar sedan
        <?php elseif (($timeinterval/(60*60*24)) < 7): ?>
            <?=round($timeinterval/(60*60*24))?> dygn sedan
        <?php elseif (($timeinterval/(60*60*24)) < 10.5) : ?>
            <?=round($timeinterval/(60*60*24*7))?> vecka sedan
        <?php elseif (($timeinterval/(60*60*24)) < 30) : ?>
            <?=round($timeinterval/(60*60*24*7))?> veckor sedan
        <?php elseif (($timeinterval/(60*60*24)) < 45) : ?>
            <?=round($timeinterval/(60*60*24*7))?> månad sedan
        <?php else : ?>
            <?=round($timeinterval/(60*60*24*30))?> månader sedan
        <?php endif; ?>
        </span>
    <?php endif; ?>
    </p>
</div>
