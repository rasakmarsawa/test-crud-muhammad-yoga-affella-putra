<?php
function is_valid_email($email)
{
    $email = trim((string) $email);
    if ($email === '') {
        return false;
    }
    if (function_exists('filter_var')) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    return preg_match('/^[^@\s]+@[^@\s]+\.[^@\s]+$/', $email) === 1;
}

function is_number_only($value)
{
    $value = trim((string) $value);
    if ($value === '') {
        return false;
    }
    return ctype_digit($value);
}
