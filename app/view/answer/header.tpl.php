<!-- Heading for answer section -->
<article class="article1">
    <div id='answers' class="answers"></div>
    <div class="answers-header-container">
        <div class="answers-no-of">
            <h3><?=$content?>&nbsp;<?=$title?></h3>
        </div>
    <?php if ($content > 1) : ?>

        <div class='answers-heading-side'>
        <p class='button-right'>
            <?php if ($answersorting == 'datum') : ?>
                <a class='tab-button-selected' href='<?=$this->url->create("{$this->request->getRoute()}?answersorting=rank#answers")?>' title='Sortera efter rank (högst rank först)'>Rank</a>
                <a class='tab-button' href='<?=$this->url->create("{$this->request->getRoute()}?answersorting=datum#answers")?>' title='Sortera efter datum (äldsta svar först)'>Datum</a>
            <?php else : ?>
                <a class='tab-button' href='<?=$this->url->create("{$this->request->getRoute()}?answersorting=rank#answers")?>' title='Sortera efter rank (högst rank först)'>Rank</a>
                <a class='tab-button-selected' href='<?=$this->url->create("{$this->request->getRoute()}?answersorting=datum#answers")?>' title='Sortera efter datum (äldsta svar först)'>Datum</a>
            <?php endif; ?>
        </p>
    </div>
<?php else : ?>
    <div class="answers-heading-side"></div>
<?php endif; ?>
</div><!-- answer-header-container -->
</article>
