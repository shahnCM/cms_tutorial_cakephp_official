<?php 
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
// for query
use Cake\ORM\Query;
// the validator class
use Cake\Validation\Validator;
// the text class
use Cake\Utility\Text;
// the EventInterface class
use Cake\Event\EventInterface;

class ArticlesTable extends Table
{
    public function initialize(array $config): void 
    {
        parent::initialize($config); // <- Check how does this act
        
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Tags', [
            'joinTable' => 'articles_tags',
            'dependent' => true
        ]); 
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('title')
            ->minLength('title', 10)
            ->maxLength('title', 255)

            ->notEmptyString('body')
            ->minLength('body', 10);

        return $validator;
    }

    // The $query argument is query builder instance.
    // The $options array will contain the 'tags' option we passed
    // to find('tagged') in our controller action.
    public function findTagged(Query $query, array $options)
    {
        $columns = [
            'Articles.id', 'Articles.user_id', 'Articles.title',
            'Articles.body', 'Articles.published', 'Articles.created',
            'Articles.slug'
        ];

        $query = $query
                    ->select($columns)
                    ->distinct($columns);

        if(empty($options['tags'])) {
            // If there are no tags provided, find articles that have no tags.
            $query->leftJoinWith('Tags')->where(['Tags.title IS' => null]);
        } else {
            // Find articles that have one or more of the provided tags.
            $query->innerJoinWith('Tags')->where(['Tags.title IN' => $options['tags']]);
        }
    
        return $query->group(['Articles.id']);
    }

    public function _buildTags($tagString)
    {
        // Trim tags
        $newTags = array_map('trim', explode(',', $tagString));
        // Remove all empty tags
        $newTags = array_filter($newTags);
        // Reduce Dplicated Tags
        $newTags = array_unique($newTags);

        $out = [];
        $tags = $this->Tags->find()->where(['Tags.title IN' => $newTags])->all();

        // Remove all existing tags from the list of new tags.
        foreach ($tags->extract('title') as $existing) {
            $index = array_search($existing, $newTags);
            if ($index !== false) {
                unset($newTags[$index]);
            }
        }

        // Add Existing Tags.
        foreach ($tags as $tag) {
            $out[] = $tag;
        }

        // Add New Tags.
        foreach ($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['title' => $tag]);
        }

        return $out;
    }

    // This code is simple, and doesn???t take into account duplicate slugs. 
    // But we???ll fix that later on
    public function beforeSave(EventInterface $event, $entity, $options)
    {
        if ($entity->tag_string) {
            $entity->tags = $this->_buildTags($entity->tag_string);
        }
        
        if($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            // trim slug to maximum length defined in schema
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }
}