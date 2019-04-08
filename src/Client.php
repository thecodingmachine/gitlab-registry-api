<?php
namespace TheCodingMachine\GitlabRegistryApi;

class Client
{

    /**
     * @var string
     */
    private $domain;

    /**
     * @var string
     */
    private $privateToken;
    
    public function __construct(string $domain, string $privateToken)
    {
        if (strrpos($domain, '/') !== strlen($domain) - 1) {
            $domain .= '/';
        }
        $this->domain = $domain;
        $this->privateToken = $privateToken;
    }

    /**
     * @param string $group
     * @param string $project
     *
     * @return Registry
     */
    public function getRegistry(string $group, string $project = null): Registry
    {
        return new Registry($this->domain, $this->privateToken, $group, $project);
    }
}
