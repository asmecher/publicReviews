{**
 * templates/content.tpl
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Display Static Page content
 *}
{include file="frontend/components/header.tpl" pageTitleTranslated=$title}

<div class="page">
	<h2>Peer Review</h2>
	<div class="reviewerName">Reviewer: {$reviewAssignment->getReviewerFullName()|escape}</div>
	<div class="reviewContent">
		<ul>
			{foreach from=$reviewComments item=comment}
				<li>
					<h3>{$comment->getCommentTitle()|escape}</h3>
					<div class="reviewComment">
						{$comment->getComments()|strip_unsafe_html}
					</div>
				</li>
			{/foreach}
		</ul>
	</div>
</div>

{include file="frontend/components/footer.tpl"}
