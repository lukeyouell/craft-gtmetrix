<?php
/**
 * GTmetrix plugin for Craft CMS 3.x
 *
 * GTmetrix gives you insight on how well your entries load and provides actionable recommendations on how to optimise them.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\gtmetrix\records;

use lukeyouell\gtmetrix\GTmetrix;

use Craft;
use craft\db\ActiveRecord;

/**
 * @author    Luke Youell
 * @package   GTmetrix
 * @since     1.0.0
 */
class GTmetrixRecord extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gtmetrix_tests}}';
    }
}
