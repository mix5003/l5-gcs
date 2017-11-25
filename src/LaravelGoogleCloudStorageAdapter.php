<?php


namespace mix5003\GCSProvider;


use CedricZiel\FlysystemGcs\GoogleCloudStorageAdapter;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Config;

class LaravelGoogleCloudStorageAdapter extends GoogleCloudStorageAdapter
{

    /**
     * Get a temporary URL for the file at the given path.
     *
     * @param  string $path
     * @param  \DateTimeInterface $expiration
     * @param  array $options
     * @return string
     */
    public function getTemporaryUrl($path, $expiration, $options = [])
    {
        $path = $this->applyPathPrefix($path);

        if (empty($options['cname']) && $this->isCustomUrl()) {
            $options['cname'] = $this->baseUrl;
        }

        $signedUrl = $this->bucket
            ->object($path)
            ->signedUrl($expiration, $options);

        if (!empty($options['cname'])) {
            $scheme = parse_url($this->baseUrl, PHP_URL_SCHEME);
            $signedUrl = $scheme . '://' . substr($signedUrl, strlen($this->baseUrl));
        }

        return $signedUrl;
    }

    /**
     * Converts flysystem specific config to options for the underlying API client
     *
     * @param $config Config
     *
     * @return array
     */
    protected function getOptionsFromConfig(Config $config)
    {
        $options = parent::getOptionsFromConfig($config);

        if ($config->has('metadata')) {
            $options['metadata'] = $config->get('metadata');
        }

        return $options;
    }

    /**
     * Check if it custom url
     *
     * @return boolean
     */
    private function isCustomUrl()
    {
        return substr($this->baseUrl, 0, strlen(self::GCS_BASE_URL)) !== self::GCS_BASE_URL;
    }
}
