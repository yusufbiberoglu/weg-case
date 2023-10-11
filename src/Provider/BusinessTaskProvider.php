<?php

namespace App\Provider;


use App\Entity\Task;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BusinessTaskProvider
{
    public const URI = 'http://www.mocky.io/v2/5d47f235330000623fa3ebf7';
    public function __construct(private HttpClientInterface $client)
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchBusinessTask(): array
    {
        $businessTask = [];

        $response = $this->client->request('GET', self::URI)->toArray();

        foreach ($response as $task ) {

            $businessTask[] = (new Task())
                ->setName(key($task))
                ->setTime($task[key($task)]['estimated_duration'])
                ->setDifficulty($task[key($task)]['level']);
        }
        return $businessTask;
    }



}