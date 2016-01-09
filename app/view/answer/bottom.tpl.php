<!-- Bottom of question section -->
<article class="article1">
<?php if ($answerform): ?>
    <div id='answer-form-container' class='answer-form-container'>
        <h4>Svara på frågan:</h4>
        <?=$content?>
    </div>
    <script type="text/javascript" language="JavaScript">
    document.forms['answer-form'].elements['content'].focus();
    </script>
<?php else : ?>
<div class='answer-button-container'>
    <p>
        <a class='answer-button' href='<?=$this->url->create("{$this->request->getRoute()}?newanswer=yes#answer-form")?>' title='Nytt svar'>SVARA</a>
    </p>
</div>
<?php endif; ?>

</article>
