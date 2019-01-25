<?php

namespace blackho1e\yii2\gearman\monitor\controllers;

use Yii;
use yii\console\Controller;
use TweeGearmanStat\Queue\Gearman;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $servers = Yii::$app->getModule('gearman-monitor')->params['servers'];
        $lists = [];
        for ($i = 0; $i < count($servers); $i++) {
            $lists['h' . strval($i+1)] = $servers[$i];
        }
        $adapter = new Gearman($lists);
        $status = $adapter->status();
        $i = 0;
        foreach ($status as $server => $queues) {
            foreach ($queues as $queue) {
                $functions = $servers[$i]['functions'];
                if (in_array($queue['name'], $functions)) {
                    $message = date('Y-m-d H:i:s') . ": " . $queue['name'].  "Count: " . $queue['queue'] . PHP_EOL;
                    $this->stdout($message);
                    if ($queue['queue'] >= $servers[$i]['limit']) {
                        if (!empty(Yii::$app->getModule('gearman-monitor')->params['slack'])) {
                            $url = Yii::$app->getModule('gearman-monitor')->params['slack']['url'];
                            $options = Yii::$app->getModule('gearman-monitor')->params['slack']['options'];
                            $client = new \Maknz\Slack\Client($url, $options);
                            $client->attach([
                                'color' => '#FF0000',
                                'fields' => [
                                    [
                                        'title' => 'Name: ' . $queue['name'],
                                        'value' => 'Count: ' . $queue['queue'],
                                        'short' => true
                                    ]
                                ]
                            ])->send("Gearman Monitoring");
                        }
                    }
                }
            }
            $i++;
        }
    }
}
