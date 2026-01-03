<?php

namespace App;

class ResponseHelper {
    static function success($message = "Operating has been successful", $data = null) {
        return [
            "success" => true,
            "message" => $message,
            "data" => $data,
        ];
    }
    static function failed($message = "Operating has been failed", $data = null) {
        return [
            "fail" => false,
            "message" => $message,
            "data" => $data,
        ];
    }
}
