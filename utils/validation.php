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

function validate_required($value, $label)
{
    if (trim((string) $value) === '') {
        return $label . ' wajib diisi.';
    }
    return null;
}

function validate_email_field($value, $label)
{
    $err = validate_required($value, $label);
    if ($err !== null) {
        return $err;
    }
    if (!is_valid_email($value)) {
        return 'Format ' . strtolower($label) . ' tidak valid.';
    }
    return null;
}

function validate_number_field($value, $label)
{
    $err = validate_required($value, $label);
    if ($err !== null) {
        return $err;
    }
    if (!is_number_only($value)) {
        return $label . ' harus berupa angka.';
    }
    return null;
}

function validate_max_length($value, $max, $label)
{
    $value = trim((string) $value);
    if ($value !== '' && strlen($value) > $max) {
        return $label . ' maksimal ' . $max . ' karakter.';
    }
    return null;
}

function validate_in($value, $allowed, $label)
{
    if (!in_array($value, $allowed, true)) {
        return $label . ' wajib dipilih.';
    }
    return null;
}

function parse_date_ymd($value)
{
    $value = trim((string) $value);
    if ($value === '') {
        return false;
    }
    $d = DateTime::createFromFormat('Y-m-d', $value);
    $errs = DateTime::getLastErrors();
    $warn = is_array($errs) ? $errs['warning_count'] : 0;
    $errc = is_array($errs) ? $errs['error_count'] : 0;
    if (!$d || $warn > 0 || $errc > 0 || $d->format('Y-m-d') !== $value) {
        return false;
    }
    return $d->getTimestamp();
}

function validate_date_field($value, $label)
{
    $err = validate_required($value, $label);
    if ($err !== null) {
        return $err;
    }
    if (parse_date_ymd($value) === false) {
        return $label . ' tidak valid.';
    }
    return null;
}

function count_days_inclusive($startTs, $endTs)
{
    return (int) floor(($endTs - $startTs) / 86400) + 1;
}
