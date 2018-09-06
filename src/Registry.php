<?php
namespace TheCodingMachine\GitlabRegistryApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Registry
{

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $group;

    /**
     * @var string|null
     */
    private $project = null;

    /**
     * @var string
     */
    private $privateToken;

    /**
     * @var string
     */
    private $cookie;

    /**
     * @var string
     */
    private $csrfToken;

    /**
     * @var Client
     */
    private $client;

    /**
     * Registry constructor.
     *
     * @param string $domain
     * @param string $privateToken
     * @param string $group
     * @param string|null $project
     */
    public function __construct(string $domain, string $privateToken, string $group, string $project = null)
    {
        $this->domain = $domain;
        $this->privateToken = $privateToken;
        $this->group = $group;
        $this->project = $project;
        $this->client = new Client(['base_uri' => $this->domain]);
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->group.($this->project?'/'.$this->project:'');
    }

    /**
     * @return Image[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getImages(): array
    {
        $response = $this->client->request('GET', $this->getPath().'/container_registry.json', ['headers' => ['Private-Token' => $this->privateToken]]);

        $images= \GuzzleHttp\json_decode($response->getBody(), true);
        $imagesObject = [];
        foreach ($images as $image) {
            $imagesObject[] = new Image($this->domain, $this->privateToken, $image, $this);
        }
        return $imagesObject;
    }

    /**
     * @return string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getHeaders():array
    {
        if (!$this->csrfToken) {
            $response = $this->client->request(
                'GET',
                $this->getPath().'/container_registry',
                ['headers' => ['Private-Token' => $this->privateToken]]
            );
            $content = $response->getBody()->getContents();

            $regexOut = [];
            preg_match_all(
                '/<meta *.*csrf-token*.* content="(.*|\n*?)" * \/>/',
                $content,
                $regexOut,
                PREG_PATTERN_ORDER,
                0
            );

            $this->csrfToken = $regexOut[1][0];
            $this->cookie = $response->getHeader('Set-Cookie')[0];
        }
        return ['X-CSRF-Token' => $this->csrfToken,
                        'Accept' => 'application/json, text/plain, */*',
                        'Cookie' => $this->cookie,
                        'X-Requested-With' => 'XMLHttpRequest',
                        'PRIVATE-TOKEN' => $this->privateToken];
    }
}
