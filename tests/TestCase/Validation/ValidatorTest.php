<?php
namespace Tyrellsys\CakePHP3MessagesValidator\Test\TestCase\Validation;

use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\TestSuite\TestCase;
use Tyrellsys\CakePHP3MessagesValidator\Validation\Validator;

class ValidatorTest extends TestCase
{
    protected $Validator;
    protected $locale;

    public function setUp()
    {
        parent::setUp();

        $this->Validator = new Validator();

        // no translate
        $this->locale = I18n::getLocale();
        I18n::setLocale('not exists locale');
    }

    public function tearDown()
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
            ->notEmpty('column');

        $errors = $this->Validator->errors([]);
        $this->assertEquals('This field is required', $errors['column']['_required']);

        $errors = $this->Validator->errors(['column' => '']);
        $this->assertEquals('This field cannot be left empty', $errors['column']['_empty']);

        $errors = $this->Validator->errors(['column' => 'abc']);
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
            ->allowEmpty('column');

        $errors = $this->Validator->errors(['column' => ['aaa']]);
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
            ->notEmpty('column');

        $errors = $this->Validator->errors([]);
        $this->assertEquals('required!!', $errors['column']['_required']);

        $errors = $this->Validator->errors(['column' => '']);
        $this->assertEquals('notEmpty!!', $errors['column']['_empty']);

        $errors = $this->Validator->errors(['column' => 'abc']);
        $this->assertEquals('maxLength!! less than 2', $errors['column']['maxLength']);
    }
}
