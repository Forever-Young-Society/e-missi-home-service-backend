<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\components;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use yii\symfonymailer\Mailer;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\Transport\Dsn;
use yii\symfonymailer\Logger;

class TMailer extends Mailer
{

    /**
     *
     * @return TransportInterface
     */
    public function getTransport(): TransportInterface
    {
        $transport = [
            'scheme' => 'smtp',
            'host' => \Yii::$app->settings->smtp->config->host,
            'username' => \Yii::$app->settings->smtp->config->username,
            'password' => \Yii::$app->settings->smtp->config->password,
            'port' => \Yii::$app->settings->smtp->config->port,
            'options' => [
                'ssl' => true,
                'allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ];

        return $this->createTransport($transport);
    }

    private function createTransport(array $config = []): TransportInterface
    {
        if (array_key_exists('enableMailerLogging', $config)) {
            $this->enableMailerLogging = $config['enableMailerLogging'];
            unset($config['enableMailerLogging']);
        }
        $logger = null;
        if ($this->enableMailerLogging) {
            $logger = new Logger();
        }
        $defaultFactories = Transport::getDefaultFactories(null, null, $logger);
        $transportObj = new Transport($defaultFactories);

        if (array_key_exists('dsn', $config)) {
            $transport = $transportObj->fromString($config['dsn']);
        } elseif (array_key_exists('scheme', $config) && array_key_exists('host', $config)) {
            $dsn = new Dsn($config['scheme'], $config['host'], $config['username'] ?? '', $config['password'] ?? '', $config['port'] ?? '', $config['options'] ?? []);
            $transport = $transportObj->fromDsnObject($dsn);
        } else {
            $transport = $transportObj->fromString('null://null');
        }
        return $transport;
    }
}
