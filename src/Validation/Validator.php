<?php
namespace Tyrellsys\CakePHP3MessagesValidator\Validation;

use Cake\Core\Configure;
use Cake\Validation\Validator as CakeValidator;

class Validator extends CakeValidator
{
    /**
     * @var string
     */
    protected $_i18nDomain = 'validation';

    /**
     * @var string
     */
    protected $_configureKeyPrefix = 'messagesValidator.messages';

    /**
     * Gets the required message for a field
     * override method
     *
     * @param string $field Field name
     * @return string|null
     */
    public function getRequiredMessage($field)
    {
        if (isset($this->_presenceMessages[$field])) {
            return $this->_presenceMessages[$field];
        }
        
        $message = $this->getMessage('required', [$field]);
        if ($message) {
            return $message;
        }

        return parent::getRequiredMessage($field);
    }

    /**
     * Gets the notEmpty message for a field
     * override metho
     *
     * @param string $field Field name
     * @return string|null
     */
    public function getNotEmptyMessage($field)
    {
        if (isset($this->_allowEmptyMessages[$field])) {
            return $this->_allowEmptyMessages[$field];
        }
        
        $message = $this->getMessage('notEmpty', [$field]);
        if ($message) {
            return $message;
        }

        return parent::getNotEmptyMessage($field);
    }

    /**
     * Adds a new rule to a field's rule set. If second argument is an array
     * then rules list for the field will be replaced with second argument and
     * third argument will be ignored.
     *
     * ### Example:
     *
     * ```
     *      $validator
     *          ->add('title', 'required', ['rule' => 'notBlank'])
     *          ->add('user_id', 'valid', ['rule' => 'numeric', 'message' => 'Invalid User'])
     *
     *      $validator->add('password', [
     *          'size' => ['rule' => ['lengthBetween', 8, 20]],
     *          'hasSpecialCharacter' => ['rule' => 'validateSpecialchar', 'message' => 'not valid']
     *      ]);
     * ```
     * override method
     *
     * @param string $field The name of the field from which the rule will be added
     * @param array|string $name The alias for a single rule or multiple rules array
     * @param array|\Cake\Validation\ValidationRule $rule the rule to add
     * @return $this
     */
    public function add($field, $name, $rule = [])
    {
        if (!is_array($name)) {
            $rules = [$name => $rule];
        } else {
            $rules = $name;
        }

        foreach ($rules as $name => $rule) {
            if (is_array($rule)) {
                $rule += ['rule' => $name];
            }

            if (!isset($rule['message'])) {
                $args = [$field];
                $_rule = $rule['rule'];
                if (is_array($_rule)) {
                    array_shift($_rule);
                    $args = array_merge($args, $_rule);
                }

                $message = $this->getMessage($name, $args);
                if ($message) {
                    $rule['message'] = $message;
                }
            }

            $rules[$name] = $rule;
        }

        return parent::add($field, $rules);
    }

    /**
     * getMessage method
     *
     * @param string $validationName The Validation Name (not validation rule)
     * @param array $args args
     * @return string|null
     */
    public function getMessage($validationName, $args)
    {
        if (!$message = Configure::read($this->_configureKeyPrefix . '.' . $validationName)) {
            return null;
        }
        $args[0] = __d($this->_i18nDomain, $args[0]);

        // not support array values
        foreach ($args as $no => $arg) {
            if (is_array($arg)) {
                $args[$no] = null;
            }
        }

        return __d($this->_i18nDomain, $message, ...$args);
    }
}
