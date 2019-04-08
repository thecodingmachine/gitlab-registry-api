<?php
namespace TheCodingMachine\GitlabRegistryApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Tag extends AbstractObject
{
    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $privateToken;

    /**
     * @var Image
     */
    private $image;

    /**
     * @var Client
     */
    private $client;

    /**
     * Tag constructor.
     *
     * @param string $domain
     * @param string $privateKey
     * @param mixed[] $payload
     */
    public function __construct(
        string $domain,
        string $privateKey,
        array $payload,
        Image $image
    ) {
        parent::__construct($payload);
        $this->domain = $domain;
        $this->privateToken = $privateKey;
        $this->image = $image;
        $this->client = new Client(['base_uri' => $this->domain]);
    }

    /**
     * @return \TheCodingMachine\GitlabRegistryApi\Image
     */
    public function getImage(): \TheCodingMachine\GitlabRegistryApi\Image
    {
        return $this->image;
    }

    /**
     * @param \TheCodingMachine\GitlabRegistryApi\Image $image
     */
    public function setImage(\TheCodingMachine\GitlabRegistryApi\Image $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getAttribute('name');
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->getAttribute('location');
    }

    /**
     * @return string
     */
    public function getRevision(): string
    {
        return $this->getAttribute('revision');
    }

    /**
     * @return string
     */
    public function getShortRevision(): string
    {
        return $this->getAttribute('short_revision');
    }

    /**
     * @return string
     */
    public function geTotalSize(): string
    {
        return $this->getAttribute('total_size');
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->getDateTimeAttribute('created_at');
    }

    /**
     * @return string
     */
    public function getDestroyPath(): string
    {
        return $this->getAttribute('destroy_path');
    }

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function destroy():bool
    {
        $response = $this->client->request('DELETE', $this->getDestroyPath(), ['headers' => $this->getImage()->getRegistry()->getHeaders()]);
        return $response->getStatusCode()==204?true:false;
    }
}
