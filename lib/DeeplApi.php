<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class DeeplApi
{
    public function __construct(private Client $client, private string $apiKey)
    {}

    public static function make() : static
    {
        $url = $_ENV['DEEPL_URL'] . (str_ends_with($_ENV['DEEPL_URL'], '/') ? '' : '/');
        $client = new Client([
            'base_uri' =>  $url . 'v2/',
        ]);
        return new self($client, $_ENV['DEEPL_KEY']);
    }

    /**
     * @param string $text
     * @param string $targetLanguage
     * @param string|null $sourceLanguage
     * @return array|null
     */
    public function translate(string $text, string $targetLanguage, string $sourceLanguage = null) : ? string
    {
        try {
            $res = $this->client->post('translate', [
                'form_params' => [
                    'auth_key' => $this->apiKey,
                    'target_lang' => $targetLanguage,
                    'text' => $text,
                    'preserve_formating' => '0',
                ] + (isset($sourceLanguage)? [
                    'source_lang' => $sourceLanguage,
                ] : []),
            ]);
            $json = $res->getBody()->getContents();
            $result = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            return $result['translations'][0]['text'];
        } catch (GuzzleException|JsonException $exception){
            echo $exception->getMessage();
        }
        return null;
    }

    #[\JetBrains\PhpStorm\ArrayShape(['character_count' => 'int', 'character_limit' => 'int'])]
    public function getUsage() : ?array
    {
        try {
            $res = $this->client->post('translate', [
                'form_params' => [
                        'auth_key' => $this->apiKey,
                ]
            ]);
            $json = $res->getBody()->getContents();
            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $exception){
            echo $exception->getMessage();
        }
        return null;
    }

    /**
     * Tests if char amount is still inside the charlimits
     * @param int $charAmount
     * @return bool true if amount is inside limit, false if not
     */
    public function checkUsage(int $charAmount) : bool
    {
        $res = $this->getUsage();
        $usage = (int) $res['character_count'];
        $limit =  (int) $res['character_limit'];
        return $usage + $charAmount <= $limit;
    }
}