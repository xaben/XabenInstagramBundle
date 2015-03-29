<?php

namespace Xaben\InstagramBundle\Service;

use Buzz\Browser;
use Doctrine\Common\Cache\CacheProvider;
use Psr\Log\LoggerInterface;

class InstagramBridge
{
    protected $cache;
    protected $logger;
    protected $config;

    public function __construct(CacheProvider $cache, LoggerInterface $logger, $config)
    {
        $this->cache = $cache;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @param integer $userId
     * @throws \Exception
     * @return bool|mixed|string
     */
    public function getRecentPhotos($userId, $count)
    {
        $data = $this->cache->fetch('instagram'.$userId);
        if (!$data) {
            $dataJSON = $this->fetch("https://api.instagram.com/v1/users/$userId/media/recent/?count=$count&client_id=".$this->config['api_key']);
            $data = json_decode($dataJSON);
            $saved = $this->cache->save('instagram'.$userId, $data, '1800');
            if (!$saved) {
                throw new \Exception('Cannot save data to key: "instagram'.$userId.'"');
            }
        }

        return $data;
    }

    private function fetch($address)
    {
        $browser = new Browser();

        return $browser->get($address);
    }
}