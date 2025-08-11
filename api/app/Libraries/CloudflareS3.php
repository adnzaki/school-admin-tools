<?php

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Credentials\Credentials;

class CloudflareS3
{
    private $bucket;

    private $client;

    public function __construct($bucket = '')
    {
        $credentials = new Credentials(env('s3_access_key'), env('s3_secret_key'));
        $options = [
            'region'        => 'auto',
            'endpoint'      => 'https://' . env('cloudflare_id') . '.r2.cloudflarestorage.com',
            'version'       => 'latest',
            'credentials'   => $credentials
        ];

        $this->client = new S3Client($options);

        if (! empty($bucket)) {
            $this->setBucket($bucket);
        }
    }

    public function setBucket($bucket)
    {
        $this->bucket = $bucket;

        return $this;
    }

    public function putObject($filepath, $contentType)
    {
        $result = $this->client->putObject([
            'Bucket'        => $this->bucket,
            'Key'           => basename($filepath),
            'SourceFile'    => $filepath,
            'Content-Type'  => $contentType,
        ]);

        return $result['ObjectURL'];
    }

    public function removePreviousObject($oldFile, $newFile)
    {
        if ($oldFile && $oldFile !== $newFile) {
            $this->deleteObject($oldFile);
        }
    }

    public function deleteObject($filename)
    {
        $this->client->deleteObject([
            'Bucket'        => $this->bucket,
            'Key'           => basename($filename),
        ]);
    }

    public function getObjectUrl($filename)
    {
        return $this->client->getObjectUrl($this->bucket, $filename);
    }

    public function getPresignedObjectUrl($filename, $expires = '+20 minutes')
    {
        $cmd = $this->client->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key'    => $filename
        ]);

        $request = $this->client->createPresignedRequest($cmd, $expires);

        return (string) $request->getUri();
    }

    public function getObject($filename)
    {
        try {
            $result = $this->client->getObject([
                'Bucket' => $this->bucket,
                'Key'    => $filename
            ]);

            // Display the object in the browser.
            header("Content-Type: {$result['ContentType']}");
            return $result['Body'];
        } catch (S3Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
