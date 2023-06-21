{**
 * templates/pfl.tpl
 *
 * Copyright (c) 2023 Simon Fraser University
 * Copyright (c) 2023 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Journal Integrity Initiative Publication Facts Label template
 *}

<ul>
{foreach from=$pr_reviewRounds item=reviewRound}
    <li>
        <div>{translate key="plugins.generic.publicReviews.round" round=$reviewRound->getRound()|escape}</div>
        <ul>
            {foreach from=$pr_reviewAssignments item=reviewAssignment}
                {* Make sure we're dealing with a review assignment in this round *}
                {if $reviewAssignment->getReviewRoundId() != $reviewRound->getId()}
                    {continue}
                {/if}

                <li>
                    <a href="{url page=publicReviews op=view reviewAssignmentId=$reviewAssignment->getId()}">{$reviewAssignment->getReviewerFullName()|escape}</a>
                </li>

            {/foreach}
        </ul>
    </li>
{/foreach}
</ul>
