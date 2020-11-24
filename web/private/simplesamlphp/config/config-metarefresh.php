<?php

$config = [
    'sets' => [

        'ucsc' => [
            'cron' => ['hourly'],
            'sources' => [
                [
                    'src' => 'https://login.ucsc.edu/metadata/idp-metadata.xml',
                ],
            ],
            'outputDir' => 'metadata/metadata-ucsc/',
        ],
    ],
];
