<!-- Heading for answer section -->
<article class="article1">
    <div id='answers'></div>
    <h3><?=$content?>&nbsp;<?=$title?></h3>
    <?php if ($content > 1) : ?>
    <div class='comments-heading-side'>
        <?php if ($answersorting == 'rank') $button_text = "rank"; else $button_text = "datum"; ?>
        <p class='button-right'><a class='sort-button' href='<?=$this->url->create("{$this->request->getRoute()}?answersorting=$answersorting#answers")?>' title='Ändra sortering'><i class="fa fa-sort"></i> Sortera svar utefter <?=$button_text?></a></p>
    </div>
<?php endif; ?>
</article>
