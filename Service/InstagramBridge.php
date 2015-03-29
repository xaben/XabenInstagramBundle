<?php

namespace Xaben\InstagramBundle\Service;

use Buzz\Browser;
use Doctrine\Common\Cache\CacheProvider;
use Psr\Log\LoggerInterface;

class InstagramBridge
{
    protected $cache;
    protected $logger;
    protected $app_id;

    public function __construct(CacheProvider $cache, LoggerInterface $logger, $app_id)
    {
        $this->cache = $cache;
        $this->logger = $logger;
        $this->app_id = $app_id;
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
            $dataJSON = $this->fetch("https://api.instagram.com/v1/users/$userId/media/recent/?count=$count&client_id=".$this->app_id);
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