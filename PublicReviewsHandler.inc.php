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
		die('here');
		$this->setupTemplate($request);
		$templateMgr->assign('title', self::$staticPage->getLocalizedTitle());

		$vars = array();
		if ($context) $vars = array(
			'{$contactName}' => $context->getData('contactName'),
			'{$contactEmail}' => $context->getData('contactEmail'),
			'{$supportName}' => $context->getData('supportName'),
			'{$supportPhone}' => $context->getData('supportPhone'),
			'{$supportEmail}' => $context->getData('supportEmail'),
		);
		$templateMgr->assign('content', strtr(self::$staticPage->getLocalizedContent(), $vars));

		$templateMgr->display(self::$plugin->getTemplateResource('content.tpl'));
	}
}

