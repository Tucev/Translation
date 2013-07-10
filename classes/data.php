<?php
	class Data {
		public static function get($selector, $data = false) {
			$value = Config::get($selector, $data);
			if($value) {
				return $value;
			} else {
				return "{{ " . $selector . " }}";
			}
		}
	}