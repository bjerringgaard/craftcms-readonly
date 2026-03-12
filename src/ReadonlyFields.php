<?php

/**
 * Main class for the Read only fields plugin
 *
 * @package ReadonlyFields
 */

namespace QD\readonly;

use Craft;
use QD\readonly\fields\StringField;
use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use QD\readonly\fields\HiddenField;
use QD\readonly\fields\HiddenNumberField;
use QD\readonly\fields\ImagesField;
use QD\readonly\fields\JsonField;
use QD\readonly\fields\TableField;
use QD\readonly\plugin\Services;
use yii\base\Event;

class ReadonlyFields extends Plugin
{
  use Services;

  public static $plugin;

  /**
   * Schema version
   *
   * @var string
   */
  public string $schemaVersion = "1.0.2";

  public function __construct($id, $parent = null, array $config = [])
  {
    Craft::setAlias('@QD/readonly', __DIR__);

    static::setInstance($this);

    parent::__construct($id, $parent, $config);
  }

  /**
   * @inheritdoc
   */
  public function init()
  {
    parent::init();
    self::$plugin = $this;

    $this->initComponents();
    $this->registerFields();
  }


  protected function registerFields()
  {
    Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function (RegisterComponentTypesEvent $event) {
      $event->types[] = StringField::class;
      $event->types[] = HiddenField::class;
      $event->types[] = HiddenNumberField::class;
      $event->types[] = TableField::class;
      $event->types[] = ImagesField::class;
      $event->types[] = JsonField::class;
    });
  }
}
