<?php
	namespace App\Sections\Api\Helpers;

	final class HtmlHelper {

		public static function getHTML($url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
			$html = curl_exec($ch);
			curl_close($ch);

			return $html;
		}
	}