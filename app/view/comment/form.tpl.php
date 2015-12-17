
<div class='comment-form' id="comment-form">
    <hr>
    <form method=post>
        <input type=hidden name="redirect" value="<?=$this->url->create($redirect)?>">
        <fieldset>
            <!-- <legend>Lämna en kommentar</legend> -->
            <?php if ($error) : ?>
                <p class='error'>Felaktig inmatning:
                <?php foreach ($error as $key => $value) : ?>
                    <?php foreach ($value as $message) : ?>
                         <?=$message?>.
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </p>
            <?php endif; ?>
            <p><label>Kommentar (använd gärna Markdown):*<br/><textarea name='content'><?=$content?></textarea></label></p>
            <p><label>Namn:*<br/><input class=text type='text' name='name' value='<?=$name?>'/></label></p>
            <p><label>Email:*<br/><input class=text type='text' name='mail' value='<?=$mail?>'/></label></p>
            <p><label>Hemsida:<br/><input class=text type='text' name='web' value='<?=$web?>'/></label></p>
            <p>
                <input type=hidden name="page" value="<?=$page?>">
                <?php if ($update) : ?>
                    <input type=hidden name='timestamp' value="<?=$timestamp?>">
                    <input type=hidden name="commentID" value="<?=$id?>">
                    <input class=buttons type='submit' name='doUpdate' value='Uppdatera' onClick="this.form.action = '<?=$this->url->create('comment/update/'.$page)?>'"/>
                    <input class=buttons type='reset' value='Återställ'/>
                    <input class=buttons type='submit' name='doRemoveOne' value='Radera' onClick="this.form.action = '<?=$this->url->create('comment/remove-one/'.$page)?>'"/>
                <?php else : ?>
                    <input class=buttons type='submit' name='doCreate' value='Kommentera' onClick="this.form.action = '<?=$this->url->create('comment/add-to-page/'.$page)?>'"/>
                    <input class=buttons type='submit' name='doRemoveAll' value='Radera alla' onClick="this.form.action = '<?=$this->url->create('comment/remove-all')?>'"/>
                <?php endif; ?>
                <input class=buttons type='submit' value='Avbryt' value='doCancel' onClick="this.form.action = ''" />     
            </p>
            <output><?=$output?></output>
        </fieldset>
    </form>
</div>
