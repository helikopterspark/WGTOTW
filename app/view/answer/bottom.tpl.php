<!-- Bottom of question section -->
<?php if ($answerform): ?>
    <div id='answer-form' class='answer-form'>
        <h3>Svara på frågan:</h3>
        <?=$content?>
    </div>
<?php else : ?>
<div class='comment-button-container'>
    <p><a class='comment-button' href='<?=$this->url->create("{$this->request->getRoute()}?newanswer=yes#answer-form")?>' title='Nytt svar'>
        <span class='lowered-letter'><i class="fa fa-exclamation fa-2x"></i></span>&nbsp;Nytt svar</a></p>
</div>
<?php endif; ?>
<p class='uplink'><a href="#">Upp</a></p>
