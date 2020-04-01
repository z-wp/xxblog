<?php
return [

	/**
	 *
	 * The site key
	 * get site key @ www.google.com/recaptcha/admin
	 *
	 */
	'api_site_key'                 => env('RECAPTCHA_SITE_KEY', ''),

	/**
	 *
	 * The secret key
	 * get secret key @ www.google.com/recaptcha/admin
	 *
	 */
	'api_secret_key'               => env('RECAPTCHA_SECRET_KEY', ''),
];