<?php

namespace D3;

class Node {
    
    public $id;
    public $name;
    public $type;
    public $children;
    public $size;
    public $url;
    
    public function __construct($id, $name, $type, $url) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->size = 1;
        $this->url = $url;
    }
    
    public function addChild($id, $name, $type, $url) {
        if(is_null($this->children)) {
            $this->children = array();
        }
        $node = new Node($id, $name, $type, $url);
        array_push($this->children, $node);
        return $node;
    }
}