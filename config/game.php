<?php

return [
    'Energy' => [
    'initialVolume' => 1000,
		'initialCharges' => 10,
		'levelUps' => [
			[ 'volume' => 2000, 'cost' => 1000000 ],
			[ 'volume' => 3000, 'cost' => 2000000 ],
			[ 'volume' => 4000, 'cost' => 4000000 ],
			[ 'volume' => 5000, 'cost' => 8000000 ],
			[ 'volume' => 6000, 'cost' => 16000000 ],
			[ 'volume' => 7000, 'cost' => 32000000 ],
			[ 'volume' => 8000, 'cost' => 64000000 ],
			[ 'volume' => 9000, 'cost' => 128000000 ],
			[ 'volume' => 10000, 'cost' => 256000000 ],
			[ 'volume' => 11000, 'cost' => 512000000 ]
		],
		'spendRate' => 1,
		'rewardRate' => 1,
		'uptime' => [
            'interval' => 14440,
			'rate' => 9999999
		],
		'freeChargeBonusInterval' => 60 * 60 * 8,
		'market' => [
			[ 'recharges' => 1, 'cost' => 7 ],
			[ 'recharges' => 4, 'cost' => 14 ],
			[ 'recharges' => 8, 'cost' => 20 ],
			[ 'recharges' => 16, 'cost' => 34 ],
		]
	],
	'Friends' => [
        'reward' => 2500,
		'rewardPremium' => 5000,
		'inviteMessage' => [
            'ru' => implode("\n", [
                '',
                '🫵Тапай, чтобы заработать, тапай за свободу основателя.',
                '',
                '🎁Получи 2500 наград, как подарок.',
                '🎁Получи 5000 наград, если у тебя есть Telegram Premium'
            ]),
			'en' => implode("\n", [
                '',
                '🫵Tap2Earn money, Tap2Freedom of the founder.',
                '',
                '🎁Receive 2500 rewards as a gift.',
                '🎁Get 5000 rewards if you have Telegram Premium'
            ])
		]
	],
	'bot' => [
        'username' => 'Free_Founderbot',
        'token' => '6704863667:AAFof5zKi2r69nEL89GbPydRJPXAtjW5hWE'
	]
];
