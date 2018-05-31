<?php
/**
 * GTmetrix plugin for Craft CMS 3.x
 *
 * GTmetrix gives you insight on how well your entries load and provides actionable recommendations on how to optimise them.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2018 Luke Youell
 */

namespace lukeyouell\gtmetrix;

use lukeyouell\gtmetrix\services\TestService as TestServiceService;
use lukeyouell\gtmetrix\variables\GTmetrixVariable;
use lukeyouell\gtmetrix\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

class GTmetrix extends Plugin
{
    // Static Properties
    // =========================================================================

    public static $plugin;

    // Public Properties
    // =========================================================================

    public $schemaVersion = '1.1.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'gtmetrix/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
