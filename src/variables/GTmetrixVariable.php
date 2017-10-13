<?php
/**
 * GTmetrix plugin for Craft CMS 3.x
 *
 * GTmetrix gives you insight on how well your entries load and provides actionable recommendations on how to optimise them.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\gtmetrix\variables;

use lukeyouell\gtmetrix\GTmetrix;
use craft\elements\Entry;

use Craft;

/**
 * @author    Luke Youell
 * @package   GTmetrix
 * @since     1.0.0
 */
class GTmetrixVariable
{
    // Public Methods
    // =========================================================================

    /**
     */
    public function getAccountStatus() {

        return GTmetrix::$plugin->gTmetrixService->getAccountStatus();

    }

    /**
     */
    public function getAllTestRecords() {

        return GTmetrix::$plugin->gTmetrixService->getAllTestRecords();

    }

    /**
     */
    public function getTestById($testId) {

        return GTmetrix::$plugin->gTmetrixService->getTestById($testId);

    }

    /**
     */
    public function updateTest($testId) {

        return GTmetrix::$plugin->gTmetrixService->updateTest($testId);

    }

    /**
     */
    public function decodeReport($report) {

        return json_decode($report, true);

    }

    /**
     */
    public function entryElementType() {

        return Entry::class;

    }
}
