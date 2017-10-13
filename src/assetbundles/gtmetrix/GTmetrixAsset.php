<?php
/**
 * GTmetrix plugin for Craft CMS 3.x
 *
 * GTmetrix gives you insight on how well your entries load and provides actionable recommendations on how to optimise them.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\gtmetrix\assetbundles\GTmetrix;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    Luke Youell
 * @package   GTmetrix
 * @since     1.0.0
 */
class GTmetrixAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = "@lukeyouell/gtmetrix/assetbundles/gtmetrix/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->css = [
            'css/foundation.css',
        ];

        parent::init();
    }
}
