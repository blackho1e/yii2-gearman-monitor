yii2-gearman-monitor
========================

기어맨 모니터링

### [Composer](https://getcomposer.org/)로 설치
```sh
composer require blackho1e/yii2-gearman-monitor "dev-master"
```
또는 composer.json에 추가
```
"blackho1e/yii2-gearman-monitor": "dev-master"
```

### 설정

* console/config/main.php 파일에 추가:

```php
'modules' => [
    'gearman-monitor' => [
        'class' => 'blackho1e\yii2\gearman\monitor\Module',
        'params' => [
            'servers' => [
                [
                    'host' => '127.0.0.1',
                    'port' => 4730,
                    'limit' => 5,  // 5개이상인경우 알림
                    'functions' => [  // 모니터링할 평션 리스트
                        'test1',
                        'test2',
                    ],
                ],
            ],
            'slack' => [
                'url' => 'https://hooks.slack.com/services/xxxxxxxxx/xxx...',
                'options' => [
                    'username' => 'blackho1e',
                    'channel' => '#general',
                    'icon' => ':sunflower:',
                    'link_names' => true
                ]
            ]
        ]
    ]
]
```

* 시스템에 crontab 등록 `$ sudo crontab -e`:

```sh
*/30 * * * * php /home/user/app/yii gearman-monitor
```
