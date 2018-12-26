<?php
return [

	// 安全检验码，以数字和字母组成的32位字符。
	'key' => 'y8z1t3vey08bgkzlw78u9cbc4pizy2sj',

	//签名方式
	'sign_type' => 'MD5',

	// 服务器异步通知页面路径。
	'notify_url' => 'http://127.0.0.23/yii-advanced-app-2.0.8/basic/web/index.php?r=pay/index"',

	// 页面跳转同步通知页面路径。
	'return_url' => 'http://127.0.0.1/yii-advanced-app-2.0.8/basic/web/index.php?r=pay/index"',

		//合作身份者id，以2088开头的16位纯数字。
	'partner_id' => '2088002075883504',

	//卖家支付宝帐户。
	'seller_id' => 'li1209@126.com'
];