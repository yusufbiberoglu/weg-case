<?php

namespace App\Provider;


use App\Entity\Task;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ITTaskProvider
{
    public const URI = 'http://www.mocky.io/v2/5d47f24c330000623fa3ebfa';
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
    public function fetchITTask(): array
    {
        $iTTask = [];

        $response = $this->client->request('GET', self::URI)->toArray();

        foreach ($response as $task) {
            $iTTask[] = (new Task())
                ->setName($task['id'])
                ->setTime($task['sure'])
                ->setDifficulty($task['zorluk']);
        }

        return $iTTask;
    }



}