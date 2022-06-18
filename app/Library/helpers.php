<?php

if (!function_exists('auth_user')) {
    function auth_user()
    {
        return auth()->user();
    }
}
