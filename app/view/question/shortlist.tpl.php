<article class="article1">
    <h2>Senaste frågorna</h2>
    <table class="frontpage-question-table">
        <th></th>
        <?php foreach ($content as $question): ?>
            <tr>
                <td>
                    <div class="stats-box-small">
                        <p><?=$question->getProperties()['upvotes'] - $question->getProperties()['downvotes']?><br>rank</p>
                    </div>
                </td>
                <td>
                    <?php if ($question->getProperties()['noOfAnswers'] > 0): ?>
                        <div class="stats-box-small">
                            <p class="answers-exist-small"><?=$question->getProperties()['noOfAnswers']?><br>svar</p>
                        </div>
                    <?php else : ?>
                        <div class="stats-box-small">
                            <p><?=$question->getProperties()['noOfAnswers']?><br>svar</p>
                        </div>
                    <?php endif; ?>
                </td>
                <td>
                    <p class="question-title-heading"><a href='<?=$this->url->create('question/id/'.$question->getProperties()['id'])?>'><?=$question->getProperties()['title']?></a></p>
                    <div class="badge-section">
                    <?php foreach ($question->tags as $tag) : ?>
                        <div class="tag-badge"><a class="smaller-text" href='<?=$this->url->create('question/tag').'?tag='.$tag->getProperties()['id']?>' title='<?=$tag->getProperties()['description']?>'><?=$tag->getProperties()['name']?></a></div>
                    <?php endforeach; ?>
                </div>

                    <div class="comment-userinfo">
                        <p class="smaller-text">
                            <span class='comments-name'>
                                <a href='<?=$this->url->create('users/id').'/'.$question->user->getProperties()['id']?>'><?=$question->user->getProperties()['acronym']?></a> &#x2022; <?=$question->user->stats?>
                            </span>
                            <span class='comments-id-time'>|
                                <?php $timestamp = strtotime($question->getProperties()['created']); ?>

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

                                <?php if (isset($question->getProperties()['updated'])) : ?>
                                    | <span class='italics'> Uppdaterad för
                                        <?php $timestamp = strtotime($question->getProperties()['updated']); ?>

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

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </article>
