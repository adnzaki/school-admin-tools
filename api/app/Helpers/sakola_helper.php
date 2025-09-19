<?php

use ExcelTools\Reader;
use CodeIgniter\Validation\ValidationInterface;
use Config\Services;
use App\Models\UserInstitusiModel;
use App\Models\SiswaModel;

if (! function_exists('request')) {
    /**
     * Shortcut to CodeIgniter request object
     * 
     * @return \CodeIgniter\HTTP\IncomingRequest
     */
    function request()
    {
        return service('request');
    }
}

if (! function_exists('encrypt')) {
    /**
     * Encrypt IDs with openssl encryption and base64 encoding
     * 
     * @param string $id The ID to encrypt
     * @param string $key The encryption key
     * 
     * @return string
     */
    function encrypt($id, $key)
    {
        $cipher = "AES-256-CBC";
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        $encrypted = openssl_encrypt($id, $cipher, $key, 0, $iv);
        $data = base64_encode($iv . $encrypted);

        // Ubah ke format URL-safe
        return rtrim(strtr($data, '+/', '-_'), '=');
    }
}

if (! function_exists('decrypt')) {
    /**
     * Decript IDs that is encrypted with openssl encryption and base64 encoding
     * 
     * @param string $id The ID to decrypt
     * @param string $key The encryption key
     * 
     * @return string|false The encrypted ID or false if it is invalid
     */
    function decrypt($encryptedData, $key)
    {
        $cipher = "AES-256-CBC";

        // Kembalikan ke base64 normal
        $data = strtr($encryptedData, '-_', '+/');
        $data = base64_decode($data);

        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
    }
}


if (! function_exists('osdate')) {

    /**
     * Shortcut to OstiumDate object
     * 
     * @return \OstiumDate
     */
    function osdate()
    {
        return new \OstiumDate;
    }
}

/**
 * Format NIP menjadi XXXXXXXX XXXXXX X XXX
 * 
 * @param string|int $number NIP yang akan di format
 * @return string
 */
if (! function_exists('formatNIP')) {
    function formatNIP($number)
    {
        if ($number === null || $number === '') {
            return '';
        }

        return substr($number, 0, 8) . ' ' . substr($number, 8, 6) . ' ' . substr($number, 14, 1) . ' '  . substr($number, 15, 3);
    }
}

if (! function_exists('get_institusi')) {
    /**
     * Mendapatkan ID institusi berdasarkan user yang sedang login
     *
     * @param int|null $userId
     * @return int|null
     */
    function get_institusi($userId = null)
    {
        $userInstitusiModel = new UserInstitusiModel();
        $institusiId = $userInstitusiModel->getInstitusiIdByCurrentUser($userId);

        return $institusiId;
    }
}

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

            preg_match('/\b([a-z_]+)\b/i', $message, $match);
            $ruleName = $match[1] ?? null;

            $customKey = $ruleName && $field ? $ruleName . '_' . $field : null;

            // ✅ Revisi aman untuk Intelephense
            $customMessage = $customKey ? lang('Validation.' . $customKey, [], false) : null;

            if (! empty($customMessage) && $customMessage !== 'Validation.' . $customKey) {
                $message = str_replace(['{field}', '{param}'], [$label, ''], $customMessage);
            } else {
                $message = str_replace($field, $label, $message);
            }

            // filter kata yg duplikat berurutan
            $message = preg_replace('/\b(\w+)\s+\1\b/u', '$1', $message);

            $messages[$field] = $message;
        }

        return $messages;
    }
}

if (!function_exists('import_spreadsheet')) {
    /**
     * Universal Import Handler
     *
     * @param array $defaultFields Default keys to merge (to ensure field exists)
     * @param array $rules CI4 validation rules per row
     * @param Closure $onSuccess Callback function to handle valid rows
     * @return array JSON-serializable response
     */
    function import_spreadsheet(array $defaultFields, array $rules, Closure $onSuccess): array
    {
        /**
         * @var \CodeIgniter\HTTP\IncomingRequest $request
         */
        $request = service('request');
        $file = $request->getFile('file');

        if (! $file || $file->getError() !== UPLOAD_ERR_OK) {
            return [
                'status'  => 'error',
                'message' => lang('Validation.uploaded', ['field' => 'file']),
            ];
        }

        $mimeTypes = [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];

        if (! in_array($file->getMimeType(), $mimeTypes)) {
            return [
                'status'  => 'error',
                'message' => lang('Validation.spreadsheet_only'),
            ];
        }

        try {
            $reader = new Reader();
            $reader->loadFromFile($file->getTempName());
            $rows = $reader->getSheetData(true);
        } catch (\Throwable $e) {
            return [
                'status'  => 'error',
                'message' => lang('Validation.unable_to_read_file') . $e->getMessage(),
            ];
        }

        /** @var ValidationInterface $validation */
        $validation = Services::validation();
        $errors = [];
        $validRows = [];

        foreach ($rows as $index => $row) {
            $data = array_merge($defaultFields, $row);

            $validation->setRules($rules);

            if (! $validation->run($data)) {
                $errors[$index + 2] = validation_error($validation->getErrors(), $rules);
            } else {
                $validRows[] = $data;
            }
        }

        if (! empty($errors)) {
            return [
                'status'  => 'error',
                'message' => lang('Validation.invalid_rows'),
                'errors'  => $errors,
            ];
        }

        // Lakukan proses penyimpanan via callback
        $onSuccess($validRows);

        return [
            'status'  => 'success',
            'message' => lang('General.dataSaved'),
        ];
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
