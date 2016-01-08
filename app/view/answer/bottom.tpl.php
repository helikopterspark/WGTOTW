<!-- Bottom of question section -->
<article class="article1">
<?php if ($answerform): ?>
    <div id='answer-form' class='answer-form'>
        <h3>Svara på frågan:</h3>
        <?=$content?>
    </div>
<?php else : ?>
<div class='answer-button-container'>
    <p>
        <a class='answer-button' href='<?=$this->url->create("{$this->request->getRoute()}?newanswer=yes#answer-form")?>' title='Nytt svar'>SVARA</a>
    </p>
</div>
<?php endif; ?>
<!-- <p class='uplink'><a href="#">Upp</a></p> -->
</article>
