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
                HookRegistry::register('Templates::Article::Footer::PageFooter', [$this, 'display']);
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

        /*$templateMgr->assign([
        ]);*/
        $output .= $templateMgr->fetch($this->getTemplateResource('publicReviews.tpl'));
        return false;
    }
}
