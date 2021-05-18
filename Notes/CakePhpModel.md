Cake Php Study / Understanding

## Models 

___CakePHP’s models are composed of Table and Entity objects___

__2 Things__ need to be put in consideration here.

1. Table
2. Entity

### Table :

We need to `use Cake\ORM\Table` 

```php
// src/Model/Table/ArticlesTable.php
namespace App\Model\Table;

use Cake\ORM\Table;
// for query
use 
// the validator class
use Cake\Validation\Validator;
// the text class
use Cake\Utility\Text;
// the EventInterface class
use Cake\Event\EventInterface;

class ArticlesTable extends Table  
{

}
```

Here we define Realationship with in `initialize` method

```php
    public function initialize(array $config): void 
    {
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Tags', [
            'joinTable' => 'articles_tags',
            'dependent' => true
        ]); 
    }
```

We deal with validations against correspondent table's fields

We need to `use Cake\Validation\Validator`

```php
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
```

We can use RulesChecker
We need to `use Cake\ORM\RulesChecker`

```php
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }
```

We also write `CUSTOM QUERY` methods in this class.

We need to `use Cake\ORM\Query`

```php
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
```

And we also trigger some methods before and after we save some JUST LIKE OBSERVERS,

For that we need to `use Cake\Event\EventInterface`

```php
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
```

### Entity : 
Entities represent a single record in the database, and provide row level behavior for our data

```php
// Cake ORM
use Cake\ORM\Entity;
// src/Model/Entity/Article 
namespace App\Model\Entity;
// the Collection class
use Cake\Collection\Collection;

class Article extends Entity 
{
}
```

We can define the accessible fields,
`_accessible` property controls how properties can be modified by __Mass Assignments__

```php
    protected $_accessible = [
        '*' => true,
        'id' => false,
        'slug' => false,
        'tag_string' => true
    ];
```





