<?php
namespace app\modules\sms\components;

use app\modules\sms\models\Gateway;
use app\modules\sms\models\History;
use yii\base\Component;

class Sms extends Component
{

    static function array_keys_exists(array $keys, array $arr)
    {
        return ! array_diff_key(array_flip($keys), $arr);
    }

    public function compose($param = [], $Sendnow = true)
    {
        static::send($param, $Sendnow);
    }

    public static function send($param = [], $Sendnow = true)
    {
        $required_keys = [
            'text',
            'to',
            'model'
        ];
     
        if (! static::array_keys_exists($required_keys, $param)) {
        
            throw new \Exception(join(', ', $required_keys) . " key must be set");
        }
        $gateway = Gateway::findActive()->one();
        if (empty($gateway)) {
            throw new \Exception("Gateway setting not found. Please add or activate any sms Gateway setting");
        }
        $model = new History();
        $model->from = (string) $gateway;
        $model->to = $param['to'];
        $model->text = $param['text'];
        $model->model_id = $param['model']->id;
        $model->model_type = get_class($param['model']);
        $model->gateway_id = $gateway->type_id;
        $model->created_by_id = $model->createdById;
        if (! $model->save()) {
            throw new \Exception($model->getErrorsString());
        }
        if ($Sendnow) {
            $gateway->sendSms($model);
        }
    }

    public static function getPostCurlResponse($url, $params = [])
    {
        $ch = curl_init();
        $postData = http_build_query($params, '', '&');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{$postData}");
        // curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function getGetCurlResponse($url, $params = [])
    {
        $url = $url . '?' . http_build_query($params, '', '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}