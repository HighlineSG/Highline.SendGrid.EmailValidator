<?php
declare(strict_types=1);
namespace Highline\SendGrid\EmailValidator\SendGrid;

use Neos\Flow\Annotations as Flow;
use Neos\Cache\Frontend\VariableFrontend;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Highline\SendGrid\EmailValidator\Exception;

/**
 * @Flow\Scope("singleton")
 */
class SendGridService
{
    /**
     * @var string
     * @Flow\InjectConfiguration(path="apiKey")
     */
    protected $apiKey;

    /**
     * @var VariableFrontend
     */
    protected $cache;

    /**
     * @param VariableFrontend $cache
     */
    public function setCache(VariableFrontend $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param mixed $value
     * @return array
     * @throws Exception
     */
    public function validateEmail($value): array
    {
        if ($this->apiKey === null || $this->apiKey === '') {
            throw new Exception('There is no api key configured for Highline.SendGrid.EmailValidator', 1581744575);
        }

        if ($this->cache->has(sha1($value))) {
            return $this->cache->get(sha1($value));
        }

        $client = new Client();

        $response = $client->request('POST', 'https://api.sendgrid.com/v3/validations/email', [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $this->apiKey
            ],
            RequestOptions::JSON => [
                'email' => $value
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $content = json_decode($response->getBody()->getContents(), true);

            $this->cache->set(sha1($value), $content['result']);

            return $content['result'];
        }
    }
}