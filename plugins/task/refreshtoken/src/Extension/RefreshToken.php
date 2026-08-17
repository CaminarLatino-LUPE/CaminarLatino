<?php

/**
 * @package     Joomla.Plugins
 * @subpackage  Task.RefreshToken
 *
 * @copyright   (C) 2021 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Plugin\Task\RefreshToken\Extension;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Component\Scheduler\Administrator\Event\ExecuteTaskEvent;
use Joomla\Component\Scheduler\Administrator\Task\Status;
use Joomla\Component\Scheduler\Administrator\Task\Task;
use Joomla\Component\Scheduler\Administrator\Traits\TaskPluginTrait;
use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Factory;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * A demo task plugin. Offers 3 task routines and demonstrates the use of {@see TaskPluginTrait},
 * {@see ExecuteTaskEvent}.
 *
 * @since 4.1.0
 */
final class RefreshToken extends CMSPlugin implements SubscriberInterface
{
    use TaskPluginTrait;

    /**
     * @var string[]
     * @since 4.1.0
     */
    private const TASKS_MAP = [
        'refreshtoken_id' => [
            'langConstPrefix' => 'PLG_TASK_REFRESHTOKEN',
            'method'          => 'refreshToken',
            'form'            => 'refreshTokenForm',
        ]
    ];

    /**
     * @var boolean
     * @since 4.1.0
     */
    protected $autoloadLanguage = true;

    /**
     * @inheritDoc
     *
     * @return string[]
     *
     * @since 4.1.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onTaskOptionsList'    => 'advertiseRoutines',
            'onExecuteTask'        => 'standardRoutineHandler',
            'onContentPrepareForm' => 'enhanceTaskItemForm',
        ];
    }


    /**
     * @param   ExecuteTaskEvent  $event  The `onExecuteTask` event.
     *
     * @return integer  The routine exit code.
     *
     * @since  4.1.0
     * @throws \Exception
     */
    private function refreshToken(ExecuteTaskEvent $event): int {

        $clientId = '9f8ac45e-d1df-4e4d-83df-93dd4c09e71e';
        $clientSecret = 'Y7h8w7CGAcl9ND8-H74XRA';



        $db = Factory::getContainer()->get('DatabaseDriver');


        $query = $db
            ->getQuery(true)
            ->select('refresh_token')
            ->from($db->quoteName('#__refresh_tokens'))
            ->where($db->quoteName('id') . " = " . $db->quote('1'));


        $db->setQuery($query);
        $result = $db->loadResult();


        if($result) {

            // Use cURL to get a new access token and refresh token
            $ch = curl_init();

            // Define base URL
            $base = 'https://authz.constantcontact.com/oauth2/default/v1/token';

            // Create full request URL
            $url = $base . '?refresh_token=' . $result . '&grant_type=refresh_token';
            curl_setopt($ch, CURLOPT_URL, $url);

            // Set authorization header
            // Make string of "API_KEY:SECRET"
            $auth = $clientId . ':' . $clientSecret;
            // Base64 encode it
            $credentials = base64_encode($auth);
            // Create and set the Authorization header to use the encoded credentials, and set the Content-Type header
            $authorization = 'Authorization: Basic ' . $credentials;
            curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization, 'Content-Type: application/x-www-form-urlencoded'));

            // Set method and to expect response
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Make the call
            $response = json_decode(curl_exec($ch));
            curl_close($ch);


            if($response->access_token != '' && $response->refresh_token != '') {

                $query = $db->getQuery(true);        

                $fields = array(
                    $db->quoteName('access_token') . ' = ' . $db->quote($response->access_token),
                    $db->quoteName('refresh_token') . ' = ' . $db->quote($response->refresh_token),

                );

                $conditions = array(
                    $db->quoteName('id') . ' = 1'
                );   

                $query->update($db->quoteName('#__refresh_tokens'))->set($fields)->where($conditions);

                $db->setQuery($query);

                $result = $db->execute();


            }


        }



      

        return Status::OK;
    }



}
