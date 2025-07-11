<?php

if (! function_exists('validation_error')) {
    /**
     * Mengganti nama field dengan label pada pesan error validasi
     *
     * @param array $errors
     * @param array $rules
     * @return array
     */
    function validation_error(array $errors, array $rules): array
    {
        $messages = [];

        foreach ($errors as $field => $message) {
            $label = $rules[$field]['label'] ?? $field;
            $messages[$field] = str_replace($field, $label, $message);
        }

        return $messages;
    }
}

if (! function_exists('valid_access')) {
    function valid_access()
    {
        $result = auth()->attempt([
            'token' => get_client_token(),
        ]);

        return $result->isOK();
    }
}

if (! function_exists('get_client_token')) {
    function get_client_token()
    {
        $request = \Config\Services::request();

        return $request->getHeaderLine(setting('Auth.authenticatorHeader')['tokens'] ?? 'Authorization');
    }
}
