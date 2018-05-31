<?php
/**
 * GTmetrix plugin for Craft CMS 3.x
 *
 * GTmetrix gives you insight on how well your entries load and provides actionable recommendations on how to optimise them.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2018 Luke Youell
 */

namespace lukeyouell\gtmetrix\services;

use lukeyouell\gtmetrix\GTmetrix;

use Craft;
use craft\base\Component;

/**
 * @author    Luke Youell
 * @package   GTmetrix
 * @since     2.0.0
 */
class TestService extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (GTmetrix::$plugin->getSettings()->someAttribute) {
        }

        return $result;
    }
}
