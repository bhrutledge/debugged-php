<?php

require_once 'Smarty/Smarty.class.php';

// TODO: Define custom exceptions
// TODO: Add hooks for individual sites
// TODO: Use camel case
class Smarty_Page extends Smarty
{
    private $map_item = null;

    public function assign_page($page_url, $varname=null)
    {
        require_once 'Site/Map.php';
        
        $this->map_item = Site_Map::instance()->get_item($page_url);        
        if (!$this->map_item) {
            throw new Exception("Page not found: $page_url");
        }
        
        if ($this->map_item->grouping) {
            if (count($this->map_item->pages)) {
                $this->assign_page($this->map_item->pages[0]);
            }
            else {
                throw new Exception("Page not found: $page_url");
            }
        }
        
        if (!$varname) {
            $this->assign($this->map_item->as_array());
        }
        else {
            $this->assign($varname, $this->map_item);
        }
    }
    
    public function display_page()
    {
        $url_template = 'pages/' . $this->map_item->url . '.tpl';
        $id_template = 'pages/' . $this->map_item->id . '.tpl';
        $template = null;
        
        if ($this->templateExists($id_template)) {
            $template = $id_template;
        }
        elseif ($this->templateExists($url_template)) {
            $template = $url_template;
        }
        else {
            throw new Exception("Template not found for " .
                                $this->map_item->url);
        }

        $this->display($template);
    }
    
    public function display_error($status, $url=null)
    {
        if (!isset($url)) {
            $url = $_SERVER['REQUEST_URI'];
        }
            
        $template = "errors/$status.tpl";
        $this->assign('url', $url);
        
        header("HTTP/1.0 $status");
        
        if ($this->templateExists($template)) {
            $this->display($template);
        }
        else {
            $this->display('errors/unknown.tpl');
        }
    }
}

