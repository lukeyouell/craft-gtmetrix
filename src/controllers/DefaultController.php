<?php
/**
 * GTmetrix plugin for Craft CMS 3.x
 *
 * GTmetrix gives you insight on how well your entries load and provides actionable recommendations on how to optimise them.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\gtmetrix\controllers;

use lukeyouell\gtmetrix\GTmetrix;

use Craft;
use craft\web\Controller;
use craft\elements\Entry;

/**
 * @author    Luke Youell
 * @package   GTmetrix
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = [];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionStartTest()
    {

        $this->requirePostRequest();

        $post = Craft::$app->request->post();

        if ($post['entries']) {

          $criteria = Entry::find();

          foreach($post['entries'] as $entryId) {

            // Fetch entry using entryId
            $criteria->id = $entryId;
            $criteria->limit = 1;
            $entry = $criteria->one();

            if ($entry) {

              // Clean post data
              $cleanData = GTmetrix::$plugin->gTmetrixService->cleanPostData($post);

              // Set GTmetrix URL
              $cleanData['url'] = $entry->url;

              // Submit to GTmetrix
              $response = GTmetrix::$plugin->gTmetrixService->post('test', $cleanData);

              if ($response['success']) {

                $record = GTmetrix::$plugin->gTmetrixService->addTestRecord($entry, $cleanData, $response['body']);

                if ($record) {

                  Craft::$app->getSession()->setNotice(Craft::t('app', 'Test requested. You have '.$response['body']->credits_left.' credits left.'));

                } else {

                  Craft::$app->getSession()->setError(Craft::t('app', 'Error: Failed to request new test.'));

                }

              } else {

                Craft::$app->getSession()->setError(Craft::t('app', 'Error: '.$response['reason']));

              }

            }

          }

        }

        return $this->redirectToPostedUrl();

    }

}
