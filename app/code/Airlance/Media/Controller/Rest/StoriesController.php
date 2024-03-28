<?php
namespace Airlance\Media\Controller\Rest;

use Middleware\Framework\Rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

class StoriesController extends Controller
{
    public function actionUpdate(): Response
    {
        return $this->asJson([
            'totalItems' => 4,
            'myStories' => [
                'account' => [
                    'uuid' => '246296108',
                    'username' => 'resoul.ua',
                    'fullName' => 'YM',
                    'profilePicture' => '/246296107/4335712153130804/4549742191903244288_n.jpg',
                    'isPrivate' => false,
                    'biography' => 'Explore',
                    'media' => 0,
                    'followers' => 0,
                    'following' => 0
                ],
                'lastViewedIndex' => 0,
                'stories' => [
                    [
                        'mimeType' => 'video',
                        'path' => '/246296107/4335712153130804/6368178735875283046_n.mp4',
                        'duration' => 5.367,
                        'lastUpdated' => '14m',
                    ],
                    [
                        'mimeType' => 'video',
                        'path' => '/246296107/4335712153130804/7558656618610710209_n.mp4',
                        'duration' => 10.376548752834466,
                        'lastUpdated' => '14m',
                    ],
                    [
                        'mimeType' => 'image',
                        'path' => '/246296107/4335712153130804/7810105988318989189_n.jpg',
                        'duration' => 5,
                        'lastUpdated' => '14m',
                    ],
                ]
            ],
            'items' => [
                [
                    'account' => [
                        'uuid' => '246296107',
                        'username' => 'johndoe',
                        'fullName' => 'John Doe',
                        'profilePicture' => '/357923075/1714888252274738/124345621262000371_n.jpg',
                        'isPrivate' => false,
                        'biography' => 'Explore',
                        'media' => 0,
                        'followers' => 0,
                        'following' => 0,
                    ],
                    'lastViewedIndex' => 0,
                    'stories' => [
                        [
                            'mimeType' => 'image',
                            'path' => '/246296107/4335712153130804/828976705172228782_n.jpg',
                            'duration' => 5,
                            'lastUpdated' => '14m',
                        ],
                        [
                            'mimeType' => 'image',
                            'path' => '/246296107/4335712153130804/1728455242631446903_n.jpg',
                            'duration' => 5,
                            'lastUpdated' => '14m',
                        ],
                        [
                            'mimeType' => 'image',
                            'path' => '/246296107/4335712153130804/1240845163032602559_n.jpg',
                            'duration' => 5,
                            'lastUpdated' => '14m',
                        ],
                    ]
                ],
                [
                    'account' => [
                        'uuid' => '246296107',
                        'username' => 'johndoe',
                        'fullName' => 'John Doe',
                        'profilePicture' => '/49329050/385215318706403/8704988687026356224_n.jpg',
                        'isPrivate' => false,
                        'biography' => 'Explore',
                        'media' => 0,
                        'followers' => 0,
                        'following' => 0,
                    ],
                    'lastViewedIndex' => 0,
                    'stories' => [
                        [
                            'mimeType' => 'image',
                            'path' => '/246296107/4335712153130804/2075994038980486116_n.jpg',
                            'duration' => 5,
                            'lastUpdated' => '14m',
                        ]
                    ]
                ],
                [
                    'account' => [
                        'uuid' => '246296107',
                        'username' => 'johndoe',
                        'fullName' => 'John Doe',
                        'profilePicture' => '/246296107/4335712153130804/4549742191903244288_n.jpg',
                        'isPrivate' => false,
                        'biography' => 'Explore',
                        'media' => 0,
                        'followers' => 0,
                        'following' => 0,
                    ],
                    'lastViewedIndex' => 0,
                    'stories' => [
                        [
                            'mimeType' => 'video',
                            'path' => '/246296107/4335712153130804/2326865499224532597_n.mp4',
                            'duration' => 4.269548752834467,
                            'lastUpdated' => '14m',
                        ],
                        [
                            'mimeType' => 'image',
                            'path' => '/246296107/4335712153130804/3557483292759278855_n.jpg',
                            'duration' => 5,
                            'lastUpdated' => '14m',
                        ],
                        [
                            'mimeType' => 'image',
                            'path' => '/246296107/4335712153130804/5760022998253460604_n.jpg',
                            'duration' => 5,
                            'lastUpdated' => '14m',
                        ],
                    ]
                ]
            ]
        ]);
    }
}