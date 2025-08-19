<?php
	namespace App\Sections\Api\Helpers;

	final class ControllerHelper {
		
		public static function createRoute(string $params, array $exposedRoutes): array {
			$paramsArray = explode("/", trim($params, "/"));
			$method = $_SERVER['REQUEST_METHOD'];
			$route = [];

			foreach ($exposedRoutes as $public => $internal) {
				if (strpos($public, $method . ':/') !== 0) continue;

				$pattern = explode("/", trim(substr($internal, strlen($method . ':/')), "/"));

				if (empty($paramsArray[0]) && empty($pattern[0])) {
					$route['url'] = $method . ':/';
					return $route;
				}

				if (count($pattern) !== count($paramsArray)) continue;

				$matched = true;
				$routeUrl = $method . ':/';
				$routeParams = [];

				foreach ($pattern as $i => $segment) {
					if (preg_match('/^\{p\d+\}$/', $segment)) {
						$routeUrl .= "{$segment}/";
						$routeParams[trim($segment, '{}')] = $paramsArray[$i];
					} elseif ($segment === $paramsArray[$i]) {
						$routeUrl .= "{$segment}/";
					} else {
						$matched = false;
						break;
					}
				}

				if ($matched) {
					$route['url'] = rtrim($routeUrl, '/');
					$route = array_merge($route, $routeParams);
					return $route;
				}
			}

			$route['url'] = $method . ':/';
			return $route;
		}
	}