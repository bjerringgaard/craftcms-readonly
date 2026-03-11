<?php

namespace QD\readonly\assetbundles\fields;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class Fields extends AssetBundle
{
  // Public Methods
  // =========================================================================

  /**
   * @inheritdoc
   */
  public function init()
  {
    $this->sourcePath = "@QD/readonly/assetbundles/fields/dist";

    $this->depends = [
      CpAsset::class,
    ];

    // $this->js = [
    //   'js/index.js',
    // ];

    $this->css = [
      'css/index.css',
    ];

    parent::init();
  }
}
