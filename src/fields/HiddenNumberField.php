<?php

namespace QD\readonly\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\base\PreviewableFieldInterface;
use craft\base\SortableFieldInterface;
use craft\helpers\Db;
use yii\db\Schema;


class HiddenNumberField extends Field implements PreviewableFieldInterface, SortableFieldInterface
{
    /**
     * @var int The number of digits allowed after the decimal point
     */
    public int $decimals = 0;

     /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        $rules = parent::defineRules();
        $rules[] = [['decimals'], 'integer', 'min' => 0, 'max' => 2];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate(
            'readonly-fields/fieldtypes/hiddenNumber/settings',
            [
                'field' => $this,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('readonly-fields', 'Hidden Number');
    }

    /**
     * @inheritdoc
     */
    public static function phpType(): string
    {
        return 'int|float';
    }

    /**
     * @inheritdoc
     */
    public function getTableAttributeHtml(mixed $value, ElementInterface $element): string
    {
        if ($value === null) {
            return '';
        }
        
        if ($this->decimals > 0) {
            return number_format($value, $this->decimals);
        }
        
        return (string)$value;
    }

    /**
     * @inheritdoc
     */
    public static function dbType(): string
    {
        if (Craft::$app->getDb()->getIsMysql()) {
            return sprintf('%s(65,2)', Schema::TYPE_DECIMAL); // Allow 2 decimal places
        }
        return Schema::TYPE_DECIMAL;
    }

    /**
     * @inheritdoc
     */
    protected function dbTypeForValueSql(): array|string|null
    {
        if (!$this->decimals) {
            return Schema::TYPE_INTEGER;
        }

        if (Craft::$app->getDb()->getIsMysql()) {
            return sprintf('%s(65,%s)', Schema::TYPE_DECIMAL, $this->decimals);
        }

        return Schema::TYPE_DECIMAL;
    }

     /**
     * @inheritdoc
     */
    public function normalizeValue(mixed $value, ?ElementInterface $element = null): float|int|null
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_numeric($value)) {
            $value = round((float)$value, $this->decimals);
            return $this->decimals === 0 ? (int)$value : (float)$value;
        }

        return 0;
    }

    /**
     * @inheritdoc
     */
    public static function queryCondition(array $instances, mixed $value, array &$params): ?array
    {
        $valueSql = static::valueSql($instances);
        return Db::parseNumericParam($valueSql, $value, columnType: static::dbType());
    }

    /**
     * @inheritdoc
     */
    public function inputHtml(mixed $value, ?ElementInterface $element = null, bool $inline = false): string
    {
        $id = Craft::$app->getView()->namespaceInputId($this->handle);
        
        return Craft::$app->getView()->renderTemplate(
            'readonly-fields/fieldtypes/hiddenNumber/input',
            [
                'id' => $id,
                'name' => $this->handle,
                'value' => $value,
                'field' => $this,
                'currentUser' => Craft::$app->getUser()->getIdentity(),
            ]
        );
    }
}