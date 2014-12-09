<?php

namespace D3;

class Node {
    
    public $name;
    public $children;
    public $size;
    
    public function __construct($name) {
        $this->name = $name;
        $this->children = array();
        $this->size = 1;
    }
    
    public function addChild($name) {
        $node = new Node($name);
        array_push($this->children, $node);
        return $node;
    }
}

class Leaf {
    
    public $name;
    public $size;
    
    public function __construct($name) {
        $this->name = $name;
        $this->size = 1;
    }

}