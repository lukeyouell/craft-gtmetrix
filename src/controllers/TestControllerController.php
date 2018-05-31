<?php
/**
 * GTmetrix plugin for Craft CMS 3.x
 *
 * GTmetrix gives you insight on how well your entries load and provides actionable recommendations on how to optimise them.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2018 Luke Youell
 */

namespace lukeyouell\gtmetrix\controllers;

use lukeyouell\gtmetrix\GTmetrix;

use Craft;
use craft\web\Controller;

/**
 * @author    Luke Youell
 * @package   GTmetrix
 * @since     2.0.0
 */
class TestControllerController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index', 'do-something'];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Welcome to the TestControllerController actionIndex() method';

        return $result;
    }

    /**
     * @return mixed
     */
    public function actionDoSomething()
    {
        $result = 'Welcome to the TestControllerController actionDoSomething() method';

        return $result;
    }
}
