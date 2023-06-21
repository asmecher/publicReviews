<?php

/**
 * @file PublicReviewsHandler.inc.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.publicReviews
 * @class PublicReviewsHandler
 */

import('classes.handler.Handler');

class PublicReviewsHandler extends Handler {
	static $plugin;
	static $staticPage;


	/**
	 * Provide the plugin to the handler.
	 * @param $plugin PublicReviewsPlugin
	 */
	static function setPlugin($plugin) {
		self::$plugin = $plugin;
	}

	/**
	 * Handle index request (redirect to "view")
	 * @param $args array Arguments array.
	 * @param $request PKPRequest Request object.
	 */
	function index($args, $request) {
		$request->redirect(null, null, 'view', $request->getRequestedOp());
	}

	/**
	 * Handle view page request (redirect to "view")
	 * @param $args array Arguments array.
	 * @param $request PKPRequest Request object.
	 */
	function view($args, $request) {
		// Assign the template vars needed and display
		$templateMgr = TemplateManager::getManager($request);
		$journal = $request->getJournal();

		$reviewAssignmentId = $request->getUserVar('reviewAssignmentId');
		$reviewAssignmentDao = DAORegistry::getDAO('ReviewAssignmentDAO');
		$reviewAssignment = $reviewAssignmentDao->getById($reviewAssignmentId);
		if (!$reviewAssignment) {
			fatalError('The review assignment does not exist.');
		}

		$submissionDao = DAORegistry::getDAO('SubmissionDAO');
		$submission = $submissionDao->getById($reviewAssignment->getSubmissionId());
		if ($submission->getContextId() != $journal->getId()) {
			fatalError('Context ID for review assignment does not match.');
		}
		if ($submission->getStatus() != STATUS_PUBLISHED) {
			fatalError('The submission is not published.');
		}

		$this->setupTemplate($request);

		$submissionCommentDao = DAORegistry::getDAO('SubmissionCommentDAO');
		$reviewComments = $submissionCommentDao->getSubmissionComments($submission->getId(), COMMENT_TYPE_PEER_REVIEW, $reviewAssignmentId);

		$templateMgr->assign([
			'reviewAssignment' => $reviewAssignment,
			'reviewComments' => $reviewComments->toArray(),
		]);

		$templateMgr->display(self::$plugin->getTemplateResource('publicReview.tpl'));
	}
}

