<?php
declare(strict_types=1);

namespace Tyrellsys\CakePHP3MessagesValidator\Test\TestCase\Validation;

use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\TestSuite\TestCase;
use Tyrellsys\CakePHP3MessagesValidator\Validation\Validator;

class ValidatorTest extends TestCase
{
    protected $Validator;
    protected $locale;

    public function setUp(): void
    {
        parent::setUp();

        $this->Validator = new Validator();

        // no translate
        $this->locale = I18n::getLocale();
        I18n::setLocale('not exists locale');
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->Validator);
        I18n::setLocale($this->locale);
    }

    /**
     * testMessage method
     *
     * @return void
     */
    public function testMessages()
    {
        $this->Validator
            ->requirePresence('column')
            ->maxLength('column', 2)
            ->notEmptyString('column');

        $errors = $this->Validator->validate([]);
        $this->assertEquals('This field is required', $errors['column']['_required']);

        $errors = $this->Validator->validate(['column' => '']);
        $this->assertEquals('This field cannot be left empty', $errors['column']['_empty']);

        $errors = $this->Validator->validate(['column' => 'abc']);
        $this->assertEquals('The provided value is invalid', $errors['column']['maxLength']);
    }

    /**
     * testValidationName method
     *
     * @return void
     */
    public function testValidationName()
    {
        $this->Validator
            ->scalar('column') // call Validation::isScalar()
            ->allowEmptyString('column');

        $errors = $this->Validator->validate(['column' => ['aaa']]);
        // not ValidationName
        $this->assertEquals('The provided value is invalid', $errors['column']['scalar']);
    }

    /**
     * testOverrideMessage method
     *
     * @return void
     */
    public function testOverrideMessage()
    {
        Configure::write('messagesValidator.messages', [
            'required' => 'required!!',
            'notEmpty' => 'notEmpty!!',
            'maxLength' => 'maxLength!! less than {1}',
        ]);

        $this->Validator
            ->requirePresence('column')
            ->maxLength('column', 2)
            ->notEmptyString('column');

        $errors = $this->Validator->validate([]);
        $this->assertEquals('required!!', $errors['column']['_required']);

        $errors = $this->Validator->validate(['column' => '']);
        $this->assertEquals('notEmpty!!', $errors['column']['_empty']);

        $errors = $this->Validator->validate(['column' => 'abc']);
        $this->assertEquals('maxLength!! less than 2', $errors['column']['maxLength']);
    }

    /**
     * test EmptyXXXX method
     *
     * @return void
     */
    public function testEmptyXXXX()
    {
        Configure::write('messagesValidator.messages', [
            'notEmptyString' => 'notEmptyString!!',
            'notEmptyArray' => 'notEmptyArray!!',
            'notEmptyFile' => 'notEmptyFile!!',
            'notEmptyDate' => 'notEmptyDate!!',
            'notEmptyDatetime' => 'notEmptyDatetime!!',
            'notEmptyTime' => 'notEmptyTime!!',
            'allowEmptyString' => 'allowEmptyString!!',
            'allowEmptyArray' => 'allowEmptyArray!!',
            'allowEmptyFile' => 'allowEmptyFile!!',
            'allowEmptyDate' => 'allowEmptyDate!!',
            'allowEmptyDatetime' => 'allowEmptyDatetime!!',
            'allowEmptyTime' => 'allowEmptyTime!!',
        ]);

        // notEmpty
        $this->Validator
            ->notEmptyString('string')
            ->notEmptyArray('array')
            ->notEmptyFile('file')
            ->notEmptyDate('date')
            ->notEmptyDatetime('datetime')
            ->notEmptyTime('time');

        $errors = $this->Validator->validate([
            'string' => '',
            'array' => [],
            'file' => [
                'name' => 'name',
                'type' => 'type',
                'tmp_name' => 'tmp_name',
                'error' => UPLOAD_ERR_NO_FILE,
            ],
            'date' => '',
            'datetime' => '',
            'time' => '',
        ]);
        $this->assertEquals('notEmptyString!!', $errors['string']['_empty']);
        $this->assertEquals('notEmptyArray!!', $errors['array']['_empty']);
        $this->assertEquals('notEmptyFile!!', $errors['file']['_empty']);
        $this->assertEquals('notEmptyDate!!', $errors['date']['_empty']);
        $this->assertEquals('notEmptyDatetime!!', $errors['datetime']['_empty']);
        $this->assertEquals('notEmptyTime!!', $errors['time']['_empty']);

        // allowEmptyXXX
        $this->Validator
            ->notEmptyString('string')
            ->notEmptyArray('array')
            ->notEmptyFile('file')
            ->notEmptyDate('date')
            ->notEmptyDatetime('datetime')
            ->notEmptyTime('time')
            ->allowEmptyString('string', null, 'update')
            ->allowEmptyArray('array', null, 'update')
            ->allowEmptyFile('file', null, 'update')
            ->allowEmptyDate('date', null, 'update')
            ->allowEmptyDatetime('datetime', null, 'update')
            ->allowEmptyTime('time', null, 'update');

        $errors = $this->Validator->validate([
            'string' => '',
            'array' => [],
            'file' => [
                'name' => 'name',
                'type' => 'type',
                'tmp_name' => 'tmp_name',
                'error' => UPLOAD_ERR_NO_FILE,
            ],
            'date' => '',
            'datetime' => '',
            'time' => '',
        ]);
        $this->assertEquals('allowEmptyString!!', $errors['string']['_empty']);
        $this->assertEquals('allowEmptyArray!!', $errors['array']['_empty']);
        $this->assertEquals('allowEmptyFile!!', $errors['file']['_empty']);
        $this->assertEquals('allowEmptyDate!!', $errors['date']['_empty']);
        $this->assertEquals('allowEmptyDatetime!!', $errors['datetime']['_empty']);
        $this->assertEquals('allowEmptyTime!!', $errors['time']['_empty']);
    }
}
