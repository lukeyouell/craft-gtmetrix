<?php
/**
 * GTmetrix plugin for Craft CMS 3.x
 *
 * GTmetrix gives you insight on how well your entries load and provides actionable recommendations on how to optimise them.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\gtmetrix\models;

use lukeyouell\gtmetrix\GTmetrix;

use Craft;
use craft\base\Model;

/**
 * @author    Luke Youell
 * @package   GTmetrix
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $userEmail = '';

    /**
     * @var string
     */
    public $apiKey = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
      return [
          [['userEmail', 'apiKey'], 'string'],
          [['userEmail', 'apiKey'], 'required'],
      ];
    }
}
