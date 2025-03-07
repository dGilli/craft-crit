<?php

namespace dgilli\craftcrit;

use Craft;
use craft\base\Model;
use craft\base\Plugin as BasePlugin;
use dgilli\craftcrit\models\Settings;
use dgilli\craftcrit\services\Foo;

/**
 * Craft Crit plugin
 *
 * @method static Plugin getInstance()
 * @method Settings getSettings()
 * @author dGilli <me@dennisgilli.com>
 * @copyright dGilli
 * @license MIT
 * @property-read Foo $foo
 */
class Plugin extends BasePlugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;

    public static function config(): array
    {
        return [
            'components' => ['foo' => Foo::class],
        ];
    }

    public function init(): void
    {
        parent::init();

        $this->attachEventHandlers();

        // Any code that creates an element query or loads Twig should be deferred until
        // after Craft is fully initialized, to avoid conflicts with other plugins/modules
        Craft::$app->onInit(function() {
            // ...
        });

        var_dump($this->foo->checkForCriticalUpdates());
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate('craft-crit/_settings.twig', [
            'plugin' => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    private function attachEventHandlers(): void
    {
        // Register event handlers here ...
        // (see https://craftcms.com/docs/5.x/extend/events.html to get started)
    }
}
