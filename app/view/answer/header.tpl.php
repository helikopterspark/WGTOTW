<!-- Heading for answer section -->
<article class="article1">
    <div id='answers' class="answers anchor"></div>
    <div class="answers-header-container">
        <div class="answers-no-of">
            <h3><?=$content?>&nbsp;<?=$title?></h3>
        </div>
    <?php if ($content > 1) : ?>

        <!-- <div class='answers-heading-side'> -->
            <div class="tab-sidebar-answers"></div>
            <?php if ($answersorting == 'datum') : ?>
                <div class='tab-button-selected'>
                    <a href='<?=$this->url->create("{$this->request->getRoute()}?answersorting=rank#answers")?>' title='Sortera efter rank (högst rank först)'>Rank</a>
                </div>
                <div class='tab-button'>
                    <a href='<?=$this->url->create("{$this->request->getRoute()}?answersorting=datum#answers")?>' title='Sortera efter datum (äldsta svar först)'>Datum</a>
                </div>
            <?php else : ?>
                <div class='tab-button'>
                    <a href='<?=$this->url->create("{$this->request->getRoute()}?answersorting=rank#answers")?>' title='Sortera efter rank (högst rank först)'>Rank</a>
                </div>
                <div class='tab-button-selected'>
                    <a href='<?=$this->url->create("{$this->request->getRoute()}?answersorting=datum#answers")?>' title='Sortera efter datum (äldsta svar först)'>Datum</a>
                </div>
            <?php endif; ?>
    <!-- </div> -->
<?php else : ?>
    <div class="answers-heading-side"></div>
<?php endif; ?>
</div><!-- answer-header-container -->
</article>
