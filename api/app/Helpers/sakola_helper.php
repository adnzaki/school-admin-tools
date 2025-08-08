<?php

use ExcelTools\Reader;
use CodeIgniter\Validation\ValidationInterface;
use Config\Services;
use App\Models\UserInstitusiModel;

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
     * @return int|null
     */
    function get_institusi()
    {
        $userInstitusiModel = new UserInstitusiModel();
        $institusiId = $userInstitusiModel->getInstitusiIdByCurrentUser();

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
