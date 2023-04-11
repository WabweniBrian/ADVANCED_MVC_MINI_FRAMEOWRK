<?php

namespace app\helpers;

use app\core\Database;

class Validator
{
    public static function validate($data, $rules): array
    {
        $errors = [];
        /** 
         * Loops through the rules and then again loops through the individual rules
         * and extracts the rule parts into an array i.e ['required', 'min:4', 'max:20', 'unique:username']
         * will become ['required], ['min', '4'], ['max', '20'], ['unique', 'username']
         * It then gets the methods from the rule parts i.e required, min and max, unique etc.
         * It then checks if the execution of of the corresponding method is true or false passing params as arguments
         * If its true, an error is added to the errors array else false, empty string.
         * @return array of errors
         */
        foreach ($rules as $field => $fieldRules) {
            foreach ($fieldRules as $rule) {
                $ruleParts = explode(':', $rule);
                $method = $ruleParts[0];
                $params = count($ruleParts) > 1 ? explode(',', $ruleParts[1]) : [];
                array_unshift($params, $data[$field]);

                // check if rule is a password confirmation rule
                if ($method === 'match' && isset($params[1])) {
                    $confirmationField = $params[1];
                    $confirmationValue = $data[$confirmationField] ?? '';
                    $params[1] = $confirmationValue;
                }

                if (!call_user_func_array([self::class, $method], $params)) {
                    $errors[$field] = self::getMessage($field, $method, $params);
                    break;
                } else {
                    $errors[$field] = '';
                }
            }
        }

        return $errors;
    }

    private static function required($value)
    {
        return !empty($value);
    }

    private static function email($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    private static function min($value, $length)
    {
        return strlen($value) >= $length;
    }
    private static function max($value, $length)
    {
        return strlen($value) <= $length;
    }

    private static function match($value1, $value2)
    {
        return $value1 === $value2;
    }

    private static function unique($value, $field)
    {
        $stmt = Database::$db->pdo->prepare("SELECT COUNT(*) FROM users WHERE $field = ?");
        $stmt->execute([$value]);
        $count = $stmt->fetchColumn();
        return $count == 0;
    }

    private static function file($value)
    {
        if ($value['name']) {
            $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
            $fileExtension = strtolower(pathinfo($value['name'], PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                return false;
            }

            return true;
        } else {
            return true;
        }
    }


    private static function size($value, $size)
    {

        $maxFileSize = $size; // 1MB in bytes
        $fileSize = $value['size'];

        if ($fileSize > $maxFileSize) {
            return false;
        }
        return true;
    }

    private static function getMessage($field, $rule, $params)
    {
        $messages = [
            'required' => 'The ' . $field . ' field is required.',
            'email' => 'The ' . $field . ' field must be a valid email address.',
            'min' => 'The ' . $field . ' field must be at least ' . (isset($params[1]) ? $params[1] : '') . ' characters.',
            'max' => 'The ' . $field . ' field must not exceed ' . (isset($params[1]) ? $params[1] : '') . ' characters.',
            'match' => 'The ' . $field . ' field does not match',
            'unique' => 'The ' . $field . ' field already exists.',
            'file' => 'The ' . $field . ' field only allows jpg, png, jpeg and gif extensions.',
            'size' => 'The ' . $field . ' field size must not exceed ' . (isset($params[1]) ? self::getSize($params[1]) : '') . ' MBS',
        ];

        return $messages[$rule];
    }

    private static function getSize($val)
    {
        if (is_numeric($val)) {
            return number_format($val / 1048576, 1);
        } else {
            return $val;
        }
    }
}
