<div id='answer-<?=$answer->getProperties()['id']?>' class="anchor"></div>
<div class='answer-editform-container'>
    <h4>Redigera svar:</h4>
    <?=$content?>
    <div class="answer-form-userinfo">
        <?php $timestamp = strtotime($answer->getProperties()['created']); ?>
        <p class=smaller-text><a href='<?=$this->url->create('users/id').'/'.$answer->user->getProperties()['id']?>'>
            &nbsp;<?=$answer->user->getProperties()['acronym']?></a> svarade för
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

            <?php if (isset($answer->getProperties()['updated'])) : ?>
                | <span class='italics'> Uppdaterad för
                    <?php $timestamp = strtotime($answer->getProperties()['updated']); ?>

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
    </div> <!-- answer-form-userinfo -->
</div>
