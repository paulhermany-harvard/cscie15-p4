<?php

namespace D3;

class Node {
    
    public $id;
    public $name;
    public $type;
    public $children;
    public $size;
    
    public function __construct($id, $name, $type) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->size = 1;
    }
    
    public function addChild($id, $name, $type) {
        if(is_null($this->children)) {
            $this->children = array();
        }
        $node = new Node($id, $name, $type);
        array_push($this->children, $node);
        return $node;
    }
}