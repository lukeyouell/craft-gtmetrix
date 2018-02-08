<?php
/**
 * GTmetrix plugin for Craft CMS 3.x
 *
 * GTmetrix gives you insight on how well your entries load and provides actionable recommendations on how to optimise them.
 *
 * @link      https://github.com/lukeyouell
 * @copyright Copyright (c) 2017 Luke Youell
 */

namespace lukeyouell\gtmetrix\services;

use lukeyouell\gtmetrix\GTmetrix;

use Craft;
use craft\base\Component;

/**
 * @author    Luke Youell
 * @package   GTmetrix
 * @since     1.0.0
 */
class GTmetrixService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     */
    public function getAccountStatus() {

      // Get account status from GTmetrix
      return $this->get('status');

    }

    /**
     */
    public function addTestRecord($entry, $post, $response) {

      \Yii::$app->db->createCommand()
        ->insert(
          '{{%gtmetrix_tests}}',
          [
            'testId' => (!isset($response->test_id) or empty($response->test_id)) ? null : $response->test_id,
            'entryId' => (!isset($entry->id) or empty($entry->id)) ? null : $entry->id,
            'entryTitle' => (!isset($entry->title) or empty($entry->title)) ? null : $entry->title,
            'entryUrl' => (!isset($entry->url) or empty($entry->url)) ? null : $entry->url,
            'location' => (!isset($post['location']) or empty($post['location'])) ? null : $post['location'],
            'browser' => (!isset($post['browser']) or empty($post['browser'])) ? null : $post['browser'],
            'connection' => (!isset($post['x-metrix-throttle']) or empty($post['x-metrix-throttle'])) ? null : $post['x-metrix-throttle'],
          ]
          )->execute();

      return true;

    }

    /**
     */
    public function getAllTestRecords() {

      return \Yii::$app->db->createCommand('SELECT * FROM {{%gtmetrix_tests}} ORDER BY dateCreated DESC')->queryAll();

    }

    /**
     */
    public function getTestById($testId) {

      return \Yii::$app->db->createCommand('SELECT * FROM {{%gtmetrix_tests}} WHERE testId="'.$testId.'"')->queryOne();

    }

    /**
     */
    public function updateTest($testId) {

      // Fetch test record
      $record = $this->getTestById($testId);

      if ($record) {

        // Get test state from GTmetrix
        $response = $this->get('test/'.$testId);

        if ($response['success']) {

          // If test is completed, fetch pagespeed & yslow reports
          if ($response['body']->state === 'completed') {

            $response['body']->pagespeed = $this->get('test/'.$testId.'/pagespeed')['body'];
            $response['body']->yslow = $this->get('test/'.$testId.'/yslow')['body'];

          }

          // Update record
          $update = $this->updateTestRecord($record, $response['body']);

          if ($update) {

            // Return updated record
            return $this->getTestById($testId);

          }

        } else {

          // Response failed for some reason, so return existing record
          return $record;

        }

      }

      return false;

    }

    /**
     */
    public function updateTestRecord($test, $response) {

      // Update record
      return \Yii::$app->db->createCommand()
               ->update('{{%gtmetrix_tests}}',
                   [
                     'state' => (!isset($response->state) or empty($response->state)) ? 'error' : $response->state,
                     'response' => (!isset($response) or empty($response)) ? null : json_encode($response)
                   ],
                   'id=:id',
                   [
                     ':id' => $test['id']
                   ]
                 )->execute();

    }

    /**
     */
    public function get($clientPath = null) {

      // Get settings
      $settings = GTmetrix::$plugin->getSettings();

      $client = new \GuzzleHttp\Client([
        'base_uri' => 'https://gtmetrix.com',
        'http_errors' => false,
        'timeout' => 5,
        'auth' => [$settings->userEmail, $settings->apiKey],
      ]);

      try {

        $response = $client->request('GET', 'api/0.1/'.$clientPath);

        if ($response->getStatusCode() === 200) {

          // Request was handled
          return [
            'success' => true,
            'status' => $response->getReasonPhrase(),
            'statusCode' => $response->getStatusCode(),
            'body' => json_decode($response->getBody())
          ];

        } else {

          return [
            'error' => true,
            'reason' => $response->getReasonPhrase(),
            'body' => json_decode($response->getBody())
          ];

        }

      } catch (\Exception $e) {

        return ['error' => true, 'reason' => $e->getMessage()];

      }

    }

    /**
     */
    public function post($clientPath = null, $postData = null) {

      $settings = GTmetrix::$plugin->getSettings();

      $client = new \GuzzleHttp\Client([
        'base_uri' => 'https://gtmetrix.com',
        'http_errors' => false,
        'timeout' => 5,
        'auth' => [$settings->userEmail, $settings->apiKey],
      ]);

      try {

        $response = $client->request('POST', 'api/0.1/'.$clientPath, ['form_params' => $postData]);

        if ($response->getStatusCode() === 200) {

          // Request was handled
          return [
            'success' => true,
            'error' => false,
            'status' => $response->getReasonPhrase(),
            'statusCode' => $response->getStatusCode(),
            'body' => json_decode($response->getBody())
          ];

        } else {

          return [
            'success' => false,
            'error' => true,
            'reason' => $response->getReasonPhrase(),
            'body' => json_decode($response->getBody())
          ];

        }

      } catch (\Exception $e) {

        return [
          'success' => false,
          'error' => true,
          'reason' => $e->getMessage()
        ];

      }

    }

    /**
     */
    public function cleanPostData($post)
    {

      // Unset data we don't want to send to GTmetrix
      $allowed = ['location', 'browser', 'x-metrix-throttle', 'x-metrix-adblock', 'login-user', 'login-pass', 'x-metrix-cookies', 'x-metrix-whitelist', 'x-metrix-blacklist'];

      return array_intersect_key($post, array_flip($allowed));

    }

}
