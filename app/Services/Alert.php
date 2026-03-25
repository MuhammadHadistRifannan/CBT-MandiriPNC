<?php

namespace App\Services;

class Alert
{
    public static function success($title, $text)
    {
        session()->flash('alert', [
            'type' => 'success',
            'title' => $title,
            'text' => $text,
        ]);
    }

    public static function error($title, $text)
    {
        session()->flash('alert', [
            'type' => 'error',
            'title' => $title,
            'text' => $text,
        ]);
    }

    public static function warning($title, $text)
    {
        session()->flash('alert', [
            'type' => 'warning',
            'title' => $title,
            'text' => $text,
        ]);
    }

    public static function info($title, $text)
    {
        session()->flash('alert', [
            'type' => 'info',
            'title' => $title,
            'text' => $text,
        ]);
    }
}