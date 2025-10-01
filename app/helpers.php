<?php

/**
 * helper functions untuk akses user data dari session
 */

if (!function_exists('current_user')) {
    /**
     * ambil data user yang sedang login dari session
     * 
     * @return array|null
     */
    function current_user()
    {
        return session('user');
    }
}

if (!function_exists('is_authenticated')) {
    /**
     * cek apakah user sudah login
     * 
     * @return bool
     */
    function is_authenticated()
    {
        return session('authenticated', false);
    }
}

if (!function_exists('is_student')) {
    /**
     * cek apakah user adalah mahasiswa
     * 
     * @return bool
     */
    function is_student()
    {
        $user = current_user();
        return $user && $user['user_type'] === 'student';
    }
}

if (!function_exists('is_institution')) {
    /**
     * cek apakah user adalah instansi
     * 
     * @return bool
     */
    function is_institution()
    {
        $user = current_user();
        return $user && $user['user_type'] === 'institution';
    }
}

if (!function_exists('is_admin')) {
    /**
     * cek apakah user adalah admin
     * 
     * @return bool
     */
    function is_admin()
    {
        $user = current_user();
        return $user && $user['user_type'] === 'admin';
    }
}

if (!function_exists('user_name')) {
    /**
     * ambil nama user yang sedang login
     * 
     * @return string
     */
    function user_name()
    {
        $user = current_user();
        
        if (!$user) {
            return 'Guest';
        }
        
        switch ($user['user_type']) {
            case 'student':
                return $user['profile']['first_name'] . ' ' . $user['profile']['last_name'];
            case 'institution':
                return $user['profile']['institution_name'];
            case 'admin':
                return $user['profile']['name'] ?? 'Admin';
            default:
                return $user['username'];
        }
    }
}

if (!function_exists('user_avatar')) {
    /**
     * ambil url avatar user
     * 
     * @return string|null
     */
    function user_avatar()
    {
        $user = current_user();
        
        if (!$user) {
            return null;
        }
        
        if ($user['user_type'] === 'student') {
            return $user['profile']['profile_photo_url'] ?? null;
        }
        
        if ($user['user_type'] === 'institution') {
            return $user['profile']['logo_url'] ?? null;
        }
        
        return null;
    }
}