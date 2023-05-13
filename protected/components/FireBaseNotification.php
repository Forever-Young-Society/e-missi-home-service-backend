<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\VarDumper;
use yii\base\InvalidConfigException;

/**
 *
 * @author Amr Alshroof
 */
class FireBaseNotification extends Component
{

    /**
     *
     * @var string the auth_key Firebase cloude messageing server key.
     */
    public $authKey = null;

    public $timeout = 5;

    public $sslVerifyHost = false;

    public $sslVerifyPeer = false;

    public $response;

    /**
     *
     * @var string the api_url for Firebase cloude messageing.
     */
    public $apiUrl = 'https://fcm.googleapis.com/fcm/send';

    public static function dlog($string)
    {
        if (Yii::$app instanceof \yii\console\Application) {
            echo $string . PHP_EOL;
        } else {
            Yii::error($string);
        }
    }

    public function init()
    {
        parent::init();
        if ($this->authKey == null) {
            throw new InvalidConfigException('authKey must be set.');
        }
    }

    /**
     * send raw body to FCM
     *
     * @param array $body
     * @return mixed
     */
    public function send($body)
    {
        $headers = [
            "Authorization:key={$this->authKey}",
            'Content-Type: application/json',
            'Expect: '
        ];
        
        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYHOST => $this->sslVerifyHost,
            CURLOPT_SSL_VERIFYPEER => $this->sslVerifyPeer,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_FRESH_CONNECT => false,
            CURLOPT_FORBID_REUSE => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_POSTFIELDS => json_encode($body)
        ]);
        
        $result = curl_exec($ch);
        $this->response = $result;
        
        if ($result === false) {
            self::dlog('Curl failed: ' . curl_error($ch));
            throw new \Exception("Could not send notification");
        }
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code = 300) {
            self::dlog("got unexpected response code $code");
            throw new \Exception("Could not send notification");
        }
        
        curl_close($ch);
        $result = json_decode($result, true);
        self::dlog('Result: ' . VarDumper::dump($result));
        return $result;
    }

    /**
     * send notification for a specific tokens with FCM
     *
     * @param array $tokens
     * @param array $data
     *            (can be something like ["message"=>$message] )
     * @param string $collapse_key
     * @param bool $delay_while_idle
     * @param
     *            array other
     * @return mixed
     */
    public function sendDataMessage($tokens = [], $data, $collapse_key = null, $delay_while_idle = null, $other = null)
    {
        self::dlog('sendDataMessage: ' . VarDumper::dumpAsString($tokens));
        $body = [
            'registration_ids' => $tokens,
            'data' => $data
        ];
        if ($collapse_key)
            $body['collapse_key'] = $collapse_key;
        if ($delay_while_idle !== null)
            $body['delay_while_idle'] = $delay_while_idle;
        if ($other)
            $body += $other;
        return $this->send($body);
    }
}