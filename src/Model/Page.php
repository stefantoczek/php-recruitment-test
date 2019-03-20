<?php

namespace Snowdog\DevTest\Model;

/**
 * Class Page
 *
 * @package Snowdog\DevTest\Model
 */
class Page
{

    public $page_id;
    public $url;
    public $website_id;
    public $last_visited;

    /** @var string  */
    private $last_visited_string;

    public function __construct()
    {
        $this->website_id = (int)$this->website_id;
        $this->page_id = (int)$this->page_id;
        $this->last_visited = (int)$this->last_visited;

        if ($this->last_visited > 0) {
            $this->last_visited_string = date('d.m.Y H:i:s', $this->last_visited);
        } else {
            $this->last_visited_string = 'never';
        }
    }

    /**
     * @return int
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->website_id;
    }

    /**
     * @return int
     */
    public function getLastVisited()
    {
        return $this->last_visited;
    }

    /**
     * @return string
     */
    public function getLastVisitedString()
    {
        return $this->last_visited_string;
    }

}