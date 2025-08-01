<?php

use Config\Services;

class Uploader
{
    /**
     * Request & Validation instances
     *
     * @var \CodeIgniter\HTTP\IncomingRequest
     * @var \CodeIgniter\Validation\Validation
     */
    private $request;
    private $validation;

    /**
     * Base path relative ke PUBLICPATH,
     * misal: 'uploads/' -> full path = PUBLICPATH . 'uploads/'
     *
     * @var string
     */
    private $basePath = 'uploads/';

    public function __construct()
    {
        $this->request    = Services::request();
        $this->validation = Services::validation();
    }

    /**
     * Set custom base path (folder di PUBLICPATH)
     *
     * @param string $basePath
     * @return $this
     */
    public function setBasePath(string $basePath)
    {
        $this->basePath = rtrim($basePath, '/') . '/';
        return $this;
    }

    /**
     * Upload Image(s)
     *
     * @param array $config
     *   - file         : nama input file
     *   - dir          : subfolder di dalam $basePath
     *   - maxSize      : maksimum size (KB)
     *   - width,height : dimensi target (hanya untuk crop)
     *   - crop         : 'resize', 'fit' atau 'stretch'
     *   - prefix       : prefix nama file
     *   - fit-position : posisi untuk fit() (default 'center')
     * @return array
     */
    public function uploadImage(array $config): array
    {
        if (! $this->validateImage($config['file'], $config['maxSize'])) {
            return [
                'msg'   => 'Error',
                'error' => $this->validation->getErrors(),
            ];
        }

        $dirPath = PUBLICPATH . $this->basePath . trim($config['dir'], '/') . '/';
        if (! is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $rawFiles = $this->request->getFiles()[$config['file']];
        $files    = is_array($rawFiles) ? $rawFiles : [$rawFiles];
        $uploaded = [];

        foreach ($files as $file) {
            $uploaded[] = $this->doUploadImage($config, $file, $dirPath);
        }

        return [
            'msg'      => 'OK',
            'uploaded' => $uploaded,
        ];
    }

    /**
     * Upload PDF(s)
     *
     * @param array $config
     *   - file    : nama input file
     *   - dir     : subfolder di dalam $basePath
     *   - maxSize : maksimum size (KB)
     *   - prefix  : prefix nama file
     * @return array
     */
    public function uploadPdf(array $config): array
    {
        if (! $this->validatePdf($config['file'], $config['maxSize'])) {
            return [
                'msg'   => 'Error',
                'error' => $this->validation->getErrors(),
            ];
        }

        $dirPath = PUBLICPATH . $this->basePath . trim($config['dir'], '/') . '/';
        if (! is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        $rawFiles = $this->request->getFiles()[$config['file']];
        $files    = is_array($rawFiles) ? $rawFiles : [$rawFiles];
        $uploaded = [];

        foreach ($files as $file) {
            $uploaded[] = $this->doUploadPdf($config, $file, $dirPath);
        }

        return [
            'msg'      => 'OK',
            'uploaded' => $uploaded,
        ];
    }

    /**
     * Proses penyimpanan dan cropping untuk image
     */
    private function doUploadImage(array $config, $file, string $dirPath): array
    {
        $prefix      = $config['prefix']       ?? '';
        $fitPosition = $config['fit-position'] ?? 'center';
        $newName     = $prefix . $file->getRandomName();
        $fullPath    = $dirPath . $newName;

        // Move file
        $file->move($dirPath, $newName);

        // Crop / resize
        $img = \Config\Services::image()->withFile($fullPath);
        if (($config['crop'] ?? '') === 'resize') {
            $img->resize($config['width'], $config['height'], true);
        } elseif (($config['crop'] ?? '') === 'fit') {
            $img->fit($config['width'], $config['height'], $fitPosition);
        }
        $img->save($fullPath);

        return [
            'url'      => base_url($this->basePath . trim($config['dir'], '/') . '/' . $newName),
            'filename' => $newName,
        ];
    }

    /**
     * Proses penyimpanan untuk PDF
     */
    private function doUploadPdf(array $config, $file, string $dirPath): array
    {
        $prefix   = $config['prefix'] ?? '';
        $newName  = $prefix . $file->getRandomName();
        $fullPath = $dirPath . $newName;

        $file->move($dirPath, $newName);

        return [
            'url'      => base_url($this->basePath . trim($config['dir'], '/') . '/' . $newName),
            'filename' => $newName,
        ];
    }

    /**
     * Validasi khusus image
     */
    private function validateImage(string $field, int $maxSize): bool
    {
        $rules = [
            $field => "uploaded[$field]"
                . "|max_size[$field,$maxSize]"
                . "|is_image[$field]"
                . "|mime_in[$field,image/jpg,image/jpeg,image/png,image/gif]"
                . "|ext_in[$field,jpg,jpeg,png,gif]",
        ];
        $msgs = [
            $field => [
                'uploaded'  => lang('Validation.uploaded'),
                'max_size'  => lang('Validation.max_size'),
                'is_image'  => lang('Validation.is_image'),
                'mime_in'   => lang('Validation.mime_in'),
                'ext_in'    => lang('Validation.ext_in'),
            ],
        ];

        return $this->validation
            ->withRequest($this->request)
            ->setRules($rules, $msgs)
            ->run();
    }

    /**
     * Validasi khusus PDF
     */
    private function validatePdf(string $field, int $maxSize): bool
    {
        $rules = [
            $field => "uploaded[$field]"
                . "|max_size[$field,$maxSize]"
                . "|mime_in[$field,application/pdf]"
                . "|ext_in[$field,pdf]",
        ];
        $msgs = [
            $field => [
                'uploaded'  => lang('Validation.uploaded'),
                'max_size'  => lang('Validation.max_size'),
                'mime_in'   => lang('Validation.mime_in', ['PDF']),
                'ext_in'    => lang('Validation.ext_in'),
            ],
        ];

        return $this->validation
            ->withRequest($this->request)
            ->setRules($rules, $msgs)
            ->run();
    }

    /**
     * Hapus file lama jika berganti
     *
     * @param string|null $oldFile
     * @param string|null $newFile
     * @param string      $uploadPath full path ke direktori, tanpa nama file
     * @return void
     */
    public function removePreviousFile($oldFile, $newFile, string $uploadPath): void
    {
        if ($oldFile && $oldFile !== $newFile) {
            $this->removeFile($uploadPath . '/' . $oldFile);
        }
    }

    /**
     * Hapus file fisik
     *
     * @param string $targetFile path relatif di dalam $basePath
     * @return bool
     */
    public function removeFile(string $targetFile): bool
    {
        $path = PUBLICPATH . $this->basePath . ltrim($targetFile, '/');
        if (file_exists($path)) {
            unlink($path);
            return true;
        }
        return false;
    }
}
