<?php
namespace Tyrellsys\CakePHP3MessagesValidator\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class ValidationsFixture extends TestFixture
{
    public $table = 'validator_validations';

    public $fields = [
        'id' => ['type' => 'integer'],
        'title' => ['type' => 'string'],
        'body' => ['type' => 'text'],
        'priority' => ['type' => 'string'],
        'status' => ['type' => 'integer'],
        '_constraints' => ['primary' => ['type' => 'primary', 'columns' => ['id']]],
    ];
}
