<?php

function getDpments() {
    static $data;
    if(!isset($data)){
        $obj = getDTable('Dpment');
        $data = $obj->toArray();
    }
    return $data;
}

function getGuides() {
    static $data;
    if(!isset($data)){
        $obj = getDTable('guide');
        $obj->setListFields('id, name');
        $data = $obj->getList(100, ' ORDER BY inx DESC');
        $data = array_to_hashmap($data, 'id', 'name');
    }
    return $data;
}

function getAbouts() {
    static $data;
    if(!isset($data)){
        $obj = getDTable('about');
        $obj->setListFields('id, name');
        $data = $obj->getList(100, ' ORDER BY inx DESC');
        $data = array_to_hashmap($data, 'id', 'name');
    }
    return $data;
}

?>
