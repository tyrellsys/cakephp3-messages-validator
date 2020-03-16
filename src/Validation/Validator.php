<?php
declare(strict_types=1);

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
     * {@inheritDoc}
     */
    public function getRequiredMessage(string $field): ?string
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
     * {@inheritDoc}
     */
    public function getNotEmptyMessage(string $field): ?string
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
     * {@inheritDoc}
     */
    public function add(string $field, $name, $rule = [])
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
    public function getMessage(string $validationName, array $args = [])
    {
        $message = Configure::read($this->_configureKeyPrefix . '.' . $validationName);
        if (!$message) {
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

    /**
     * {@inheritDoc}
     */
    public function allowEmptyString(string $field, ?string $message = null, $when = true)
    {
        return parent::allowEmptyString($field, $message ?? $this->getMessage(__FUNCTION__, [$field]), $when);
    }

    /**
     * {@inheritDoc}
     */
    public function notEmptyString(string $field, ?string $message = null, $when = false)
    {
        return parent::notEmptyString($field, $message ?? $this->getMessage(__FUNCTION__, [$field]), $when);
    }

    /**
     * {@inheritDoc}
     */
    public function allowEmptyArray(string $field, ?string $message = null, $when = true)
    {
        return parent::allowEmptyArray($field, $message ?? $this->getMessage(__FUNCTION__, [$field]), $when);
    }

    /**
     * {@inheritDoc}
     */
    public function notEmptyArray(string $field, ?string $message = null, $when = false)
    {
        return parent::notEmptyArray($field, $message ?? $this->getMessage(__FUNCTION__, [$field]), $when);
    }

    /**
     * {@inheritDoc}
     */
    public function allowEmptyFile(string $field, ?string $message = null, $when = true)
    {
        return parent::allowEmptyFile($field, $message ?? $this->getMessage(__FUNCTION__, [$field]), $when);
    }

    /**
     * {@inheritDoc}
     */
    public function notEmptyFile(string $field, ?string $message = null, $when = false)
    {
        return parent::notEmptyFile($field, $message ?? $this->getMessage(__FUNCTION__, [$field]), $when);
    }

    /**
     * {@inheritDoc}
     */
    public function allowEmptyDate(string $field, ?string $message = null, $when = true)
    {
        return parent::allowEmptyDate($field, $message ?? $this->getMessage(__FUNCTION__, [$field]), $when);
    }

    /**
     * {@inheritDoc}
     */
    public function notEmptyDate(string $field, ?string $message = null, $when = false)
    {
        return parent::notEmptyDate($field, $message ?? $this->getMessage(__FUNCTION__, [$field]), $when);
    }

    /**
     * {@inheritDoc}
     */
    public function allowEmptyDatetime(string $field, ?string $message = null, $when = true)
    {
        return parent::allowEmptyDatetime($field, $message ?? $this->getMessage(__FUNCTION__, [$field]), $when);
    }

    /**
     * {@inheritDoc}
     */
    public function notEmptyDatetime(string $field, ?string $message = null, $when = false)
    {
        return parent::notEmptyDatetime($field, $message ?? $this->getMessage(__FUNCTION__, [$field]), $when);
    }

    /**
     * {@inheritDoc}
     */
    public function allowEmptyTime(string $field, ?string $message = null, $when = true)
    {
        return parent::allowEmptyTime($field, $message ?? $this->getMessage(__FUNCTION__, [$field]), $when);
    }

    /**
     * {@inheritDoc}
     */
    public function notEmptyTime(string $field, ?string $message = null, $when = false)
    {
        return parent::notEmptyTime($field, $message ?? $this->getMessage(__FUNCTION__, [$field]), $when);
    }
}
