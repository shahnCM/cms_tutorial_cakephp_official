<?php
// src/Model/Entity/Article 
namespace App\Model\Entity;
// the Collection class
use Cake\Collection\Collection;
// Cake ORM
use Cake\ORM\Entity;

class Article extends Entity 
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
        'slug' => false,
        'tag_string' => true
    ];

    protected function _getTagString()
    {
        if (isset($this->_fields['tag_string'])) {
            return $this->_fields['tag_string'];
        }
        if (empty($this->tags)) {
            return '';
        }
        $tags = new Collection($this->tags);
        $str = $tags->reduce(function ($string, $tags) {
            return $string . $tags->title . ', ' ;
        }, '');
    }
}