<?php
namespace TheCodingMachine\GitlabRegistryApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Image extends AbstractObject
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
     * @var Registry
     */
    private $registry;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Repository constructor.
     *
     * @param mixed[] $payload
     */
    public function __construct(
        string $domain,
        string $privateKey,
        array $payload,
        Registry $registry
    ) {
        parent::__construct($payload);
        $this->domain = $domain;
        $this->privateToken = $privateKey;
        $this->registry = $registry;
        $this->client = new Client(['base_uri' => $this->domain]);
    }

    /**
     * @return Registry
     */
    public function getRegistry(): Registry
    {
        return $this->registry;
    }

    /**
     * @param Registry $registry
     */
    public function setRegistry(Registry $registry): void
    {
        $this->registry = $registry;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->getAttribute('path');
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
    public function getTagsPath(): string
    {
        return $this->getAttribute('tags_path');
    }

    /**
     * @return string
     */
    public function getDestroyPath(): string
    {
        return $this->getAttribute('destroy_path');
    }

    /**
     * @return Tag[]
     * @throws \InvalidArgumentException
     */
    public function getTags(int $page = null, int $perPage = null): array
    {
        $response = $this->client->request('GET', $this->getTagsPath().($page?'&page='.$page:'').($perPage?'&per_page='.$perPage:''), ['headers' => ['Private-Token' => $this->privateToken]]);
        $tags = \GuzzleHttp\json_decode($response->getBody(), true);
        $tagsObject = [];
        foreach ($tags as $tag) {
            $tagsObject[] = new Tag($this->domain, $this->privateToken, $tag, $this);
        }
        return $tagsObject;
    }

    /**
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function destroy():bool
    {
        $response = $this->client->request('DELETE', $this->getDestroyPath(), ['headers' => $this->getRegistry()->getHeaders()]);
        return $response->getStatusCode()==204?true:false;
    }
}
