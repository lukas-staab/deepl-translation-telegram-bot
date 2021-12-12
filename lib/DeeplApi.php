<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Longman\TelegramBot\TelegramLog;

class DeeplApi
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var string
     */
    private $apiKey;

    public function __construct(Client $client, string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    public static function make() : DeeplApi
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
            TelegramLog::debug('start deepl translation');
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
            TelegramLog::debug('fetched translation');
            return $result['translations'][0]['text'];
        } catch (GuzzleException|JsonException $exception){
            echo $exception->getMessage();
        }
        return null;
    }

    #[\JetBrains\PhpStorm\ArrayShape(['character_count' => 'int', 'character_limit' => 'int', 'character_percent' => 'string'])]
    public function getUsage() : ?array
    {
        try {
            TelegramLog::debug('fetch deepl usage');
            $res = $this->client->post('usage', [
                'form_params' => [
                    'auth_key' => $this->apiKey,
                ]
            ]);
            $json = $res->getBody()->getContents();
            $array = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            $array['character_percent'] =  number_format((1.00 * $array['character_count'] / $array['character_limit']) * 100,1,',', '') . '%';
            return $array;
        } catch (GuzzleException|JsonException $exception){
            TelegramLog::debug($exception->getMessage(), $exception->getTrace());
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

    /**
     * @param string|null $filter 'source', 'target' or null allowed
     * @return array
     */
    public function getSupportedLanguages(string $filter = null) : array
    {
        TelegramLog::debug('get supported deepl languages');
        try {
            $res = $this->client->post('languages', [
                'form_params' => [
                    'auth_key' => $this->apiKey,
                ] + (isset($filter) ? [
                    'type' => $filter
                ] : []),
            ]);
            $json = $res->getBody()->getContents();
            $result = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            $langs = array_column($result, 'language');
            return array_combine($langs, $result);
        }catch (GuzzleException|JsonException $exception){
            TelegramLog::debug($exception->getMessage(), $exception->getTrace());
        }
        return [];
    }
}