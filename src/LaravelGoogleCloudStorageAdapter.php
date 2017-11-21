<?php


namespace mix5003\GCSProvider;


use CedricZiel\FlysystemGcs\GoogleCloudStorageAdapter;

class LaravelGoogleCloudStorageAdapter extends GoogleCloudStorageAdapter
{

    /**
     * Get a temporary URL for the file at the given path.
     *
     * @param  string  $path
     * @param  \DateTimeInterface  $expiration
     * @param  array  $options
     * @return string
     */
    public function getTemporaryUrl($path, $expiration, $options = [])
    {
        $path = $this->applyPathPrefix($path);

        return $this->bucket
            ->object($path)
            ->signedUrl($expiration, $options);
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
}
