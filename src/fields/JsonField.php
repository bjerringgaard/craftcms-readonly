<?php

namespace QD\readonly\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\base\SortableFieldInterface;
use yii\db\Schema;

class JsonField extends Field implements PreviewableFieldInterface, SortableFieldInterface
{
    // It is stroed as Text in the DB, but it is a JSON string.
    public static function displayName(): string
    {
        return 'Read only: JSON';
    }

    public static function valueType(): string
    {
        return 'string|null';
    }

    // Setting what type of column this field should be stored as in the DB.
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @inheritdoc
     */
    // Override the default validation rules coming from Field class
    protected function defineRules(): array
    {
        $rules = parent::defineRules();
        $rules[] = [['name'], 'string', 'max' => null];
        return $rules;
    }

    // Pass along the values into the template file
    protected function inputHtml(mixed $value, ?ElementInterface $element, bool $inline): string
    {
        return Craft::$app->getView()->renderTemplate('readonly-fields/fieldtypes/json/input.twig', [
            'name' => $this->handle,
            'value' => $value,
            'field' => $this
        ]);
    }
}
