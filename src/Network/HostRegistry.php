<?php

namespace App\Network;

use GuzzleHttp\Psr7\Request;
use Http\Client\Exception as HttpException;
use Http\Client\HttpClient;

class HostRegistry
{
    /**
     * @var string[]
     */
    private $hosts;

    /**
     * @var HttpClient
     */
    private $client;

    public function __construct(HttpClient $client, string $hosts)
    {
        $this->client = $client;
        $this->hosts = explode(',', $hosts);
    }

    public function callCommand(CommandInterface $command)
    {
        foreach ($this->hosts as $host) {
            try {
                $this->client->sendRequest(
                    new Request(
                        'POST',
                        $host . '/' . $command->getName(),
                        [],
                        json_encode($command->getPayload())
                    )
                );
            } catch (HttpException $e) {
                // TODO log
            } catch (\Exception $e) {
                // TODO log
            }
        }
    }
}
