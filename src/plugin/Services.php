<?php

namespace QD\readonly\plugin;

use QD\readonly\services\RelationsService;

trait Services
{
  private function initComponents()
  {
    $this->setComponents([
      'relations' => RelationsService::class,
    ]);
  }

  public function getRelationsService(): RelationsService
  {
    return $this->get('relations');
  }
}
