<?php

/**
 * @file PublicReviewsPlugin.inc.php
 *
 * Copyright (c) 2023 Simon Fraser University
 * Copyright (c) 2023 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @brief Public Reviews plugin
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class PublicReviewsPlugin extends GenericPlugin {
    /**
     * @copydoc Plugin::register()
     */
    function register($category, $path, $mainContextId = null) {
        if (parent::register($category, $path, $mainContextId)) {
            if ($this->getEnabled($mainContextId)) {
                HookRegistry::register('Templates::Article::Details', [$this, 'display']);
                HookRegistry::register('LoadHandler', [$this, 'callbackHandleContent']);
            }
            return true;
        }
        return false;
    }

    /**
     * Get the display name of this plugin.
     * @return String
     */
    function getDisplayName() {
        return __('plugins.generic.publicReviews.displayName');
    }

    /**
     * Get a description of the plugin.
     */
    function getDescription() {
        return __('plugins.generic.publicReviews.description');
    }

    /**
     * Hook callback for displaying the publication facts label.
     */
    function display($hookName, $args) {
        $templateMgr =& $args[1];
        $output =& $args[2];
        $journal = Application::get()->getRequest()->getContext();
        $submission = $templateMgr->getTemplateVars('article');

        $reviewAssignmentDao = DAORegistry::getDAO('ReviewAssignmentDAO');
        $reviewAssignments = $reviewAssignmentDao->getBySubmissionId($submission->getId());

        $reviewRoundDao = DAORegistry::getDAO('ReviewRoundDAO');
        $reviewRounds = $reviewRoundDao->getBySubmissionId($submission->getId());
        //error_log(print_r($reviewRounds,true));
        $templateMgr->assign([
            'pr_reviewAssignments' => $reviewAssignments,
            'pr_reviewRounds' => $reviewRounds->toArray(),
        ]);

        $output .= $templateMgr->fetch($this->getTemplateResource('publicReviews.tpl'));
        return false;
    }

    /**
     * Declare the handler function to process the actual page PATH
     * @param $hookName string The name of the invoked hook
     * @param $args array Hook parameters
     * @return boolean Hook handling status
     */
    function callbackHandleContent($hookName, $args) {
        $request = Application::get()->getRequest();
        $templateMgr = TemplateManager::getManager($request);

        $page =& $args[0];
        $op =& $args[1];

        if ($page == 'publicReviews' && $op == 'view') {
            define('HANDLER_CLASS', 'PublicReviewsHandler');
            $this->import('PublicReviewsHandler');

            PublicReviewsHandler::setPlugin($this);
        }
        return false;
    }
}
