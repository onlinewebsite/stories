<?php
namespace Stories\Model\Table;

use Cake\Core\Configure;
use Cake\Database\Schema\TableSchema;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Log\Log;


/**
 * Stories Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsToMany $Phinxlog
 */
class StoriesTable extends Table
{
    /**
     * @param TableSchema $schema
     *
     * @return TableSchema
     */
    protected function _initializeSchema(TableSchema $schema)
    {
        $schema->setColumnType('data_load', 'json');
        return $schema;
    }
    
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('stories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $userConfig = Configure::read('Stories.Users');
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'className' => $userConfig['table'],
            'targetForeignKey' => $userConfig['id']
        ]);
        $this->belongsToMany('Phinxlog', [
            'foreignKey' => 'story_id',
            'targetForeignKey' => 'phinxlog_id',
            'joinTable' => 'stories_phinxlog',
            'className' => 'Stories.Phinxlog'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('controller', 'create')
            ->notEmpty('controller');

        $validator
            ->requirePresence('action', 'create')
            ->notEmpty('action');

        $validator
            ->requirePresence('ip', 'create')
            ->notEmpty('ip');

        $validator
            ->requirePresence('level', 'create')
            ->notEmpty('level');

        $validator
            ->allowEmpty('webroot');

        $validator
            ->requirePresence('currentpath', 'create')
            ->notEmpty('currentpath');

        $validator
            ->allowEmpty('plugin');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }


}
