<?php

// TODO: Use camel case
class Site_MapItem
{
    public $url = '';
    public $id = '';
    public $title = '';
    public $grouping = false;
    public $pages = array(); // urls
    public $path = array(); // urls
    public $parent = null;
    // TODO: published, updated, priority
    
    public function as_array()
    {
        $vars = array('url' => $this->url, 'id' => $this->id,
                      'title' => $this->title, 'path' => $this->path,
                      'pages' => $this->pages);

        $vars['parent'] = $this->parent ?
            $this->parent->as_array() : array();
            
        return $vars;
    }
}

abstract class Site_MapHandler
{    
    abstract public function get_item($url);
}

class Site_MapHandler_Array extends Site_MapHandler
{
    private $urls = array();
    
    protected function create_map_item()
    {
        return new Site_MapItem();
    }
    
    protected function process_map_element($element, $item)
    {
    }
    
    private function parse_map($map, $parent=null)
    {        
        foreach ($map as $element) {            
            $item = $this->create_map_item();
                        
            if (isset($element['url'])) {
                $item->url = $element['url'];
            }
            elseif (isset($element['slug'])) {
                $item->url = $parent && $parent->url ? 
                    "{$parent->url}/{$element['slug']}" : $element['slug'];
            }
            // TODO: Throw exception or warning on missing URL
        
            if (isset($element['id'])) {
                $item->id = $element['id'];
            }
            else {
                $item->id = str_replace('/', '-', $item->url);
            }

            if (isset($element['title'])) {
                $item->title = $element['title'];
            }
        
            if (isset($element['grouping'])) {
                $item->grouping = (boolean) $element['grouping'];
            }
            
            if ($parent) {
                $parent->pages[] = $item->url;
                $item->parent = $parent;
                $item->path = array_merge($parent->path,
                                          array($parent->url));
            }
            
            $this->urls[$item->url] = $item;            
            $this->process_map_element($element, $item);
            
            $pages = array();
            if (isset($element['pages'])) {
                $pages = $element['pages'];
            }
            elseif (isset($element['contents'])) {
                $pages = $element['contents'];
            }
            
            if (count($pages)) {
                $this->parse_map($pages, $item);
            }
        }
    }
    
    public function __construct($map)
    {
        $this->parse_map($map);
    }
    
    public function get_item($url)
    {
        if (array_key_exists($url, $this->urls)) {
            return $this->urls[$url];
        }

        return null;
    }
}

class Site_MapHandler_Registration extends Site_MapHandler
{
    private $handler;
    private $path;
    
    public function __construct($handler, $path=null)
    {
        $this->handler = $handler;
        // Only needed in reverse order
        $this->path = is_array($path) ? array_reverse($path) : array();
    }
    
    public function get_item($url)
    {
        $item = $this->handler->get_item($url);
        if ($item) {
            foreach ($this->path as $parent_url) {
                array_unshift($item->path, $parent_url);
            }
        }
        
        return $item;
    }
}

/**
 * Sitemap
 *
 * @package Site
 * @author Brian Rutledge <brian@debugged.org>
 */
class Site_Map
{
    private $handlers = array();

    /**
     * Singleton instance
     * @var mixed
     */
    static private $_instance = null;
 
    /**
     * Singleton constructor
     * @return void
     */
    private function __construct()
    {}
 
    /**
     * Retrieves singleton instance
     * @return mixed  the singleton instance
     */
    static public function instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Site_Map;
        }
        
        return self::$_instance;
    }
    
    private function get_handler($url)
    {
        $prefix_elements = explode('/', $url);
        $handler = null;
        
        while (count($prefix_elements) > 0) {
            $prefix = implode('/', $prefix_elements);
            
            if (array_key_exists($prefix, $this->handlers)) {
                $handler = $this->handlers[$prefix];
                break;
            }
            
            array_pop($prefix_elements);
        }
        
        if (!$handler) {
            $handler = $this->handlers[''];
        }
        
        return $handler;
    }
    
    public function register_handler($prefix, $handler)
    {
        $path = null;
        if ($prefix != '') {
            // The prefix URL must already exist in a registered handler
            $prefix_item = $this->get_item($prefix);
            $path = $prefix_item->path;
        }

        $this->handlers[$prefix] = 
            new Site_MapHandler_Registration($handler, $path);
    }
    
    // TODO: Handle undefined urls
    public function get_item($url)
    {
        $handler = $this->get_handler($url);
        return $handler->get_item($url);
    }
    
    public function to_html($urls, $attributes="")
    {   
        $html = "\n<ul$attributes>\n";

        foreach ($urls as $url) {
            $html .= "<li>";

            $item = $this->get_item($url);
            $html .= $item->grouping ?
                $item->title : "<a href=\"$item->url\">$item->title</a>";

            if (count($item->pages) > 0) {
                $html .= $this->to_html($item->pages);
            }

            $html .= "</li>\n";
        }

        $html .= "</ul>\n";
        return $html;
    }
}
