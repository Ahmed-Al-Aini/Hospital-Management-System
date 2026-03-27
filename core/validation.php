<?php
// core/Validation.php - التحقق من صحة البيانات

class Validation
{
    private $errors = [];
    private $data = [];

    public function __construct($data)
    {
        $this->data = $this->sanitize($data);
    }

    private function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    public function required($field, $message = null)
    {
        if (empty($this->data[$field])) {
            $this->errors[$field][] = $message ?? "حقل $field مطلوب";
        }
        return $this;
    }

    public function email($field, $message = null)
    {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = $message ?? "حقل $field يجب أن يكون بريداً إلكترونياً صحيحاً";
        }
        return $this;
    }

    public function min($field, $length, $message = null)
    {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field][] = $message ?? "حقل $field يجب أن يكون على الأقل $length حروف";
        }
        return $this;
    }

    public function max($field, $length, $message = null)
    {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field][] = $message ?? "حقل $field يجب أن يكون أقل من $length حرف";
        }
        return $this;
    }

    public function numeric($field, $message = null)
    {
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field][] = $message ?? "حقل $field يجب أن يكون رقماً";
        }
        return $this;
    }

    public function passes()
    {
        return empty($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }

    public function validated()
    {
        return $this->data;
    }
}
