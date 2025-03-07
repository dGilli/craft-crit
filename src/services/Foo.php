<?php

namespace dgilli\craftcrit\services;

use Craft;
use yii\base\Component;

/**
 * Foo service
 */
class Foo extends Component
{
    /**
     * Checks for critical updates in Craft CMS and installed plugins.
     *
     * @return array List of critical updates found.
     */
    public function checkForCriticalUpdates(): array
    {
        // Force refresh update info
        Craft::$app->getUpdates()->getUpdates(true);

        $updates = Craft::$app->getUpdates()->getUpdates();
        $criticalUpdates = [];

        if (!empty($updates->cms->releases)) {
            $criticalUpdates = array_merge(
                $criticalUpdates,
                $this->getCriticalUpdates('craft/cms', $updates->cms->releases)
            );
        }

        if (!empty($updates->plugins)) {
            foreach ($updates->plugins as $handle => $pluginUpdate) {
                if (!empty($pluginUpdate->releases)) {
                    $criticalUpdates = array_merge(
                        $criticalUpdates,
                        $this->getCriticalUpdates($handle, $pluginUpdate->releases)
                    );
                }
            }
        }

        return $criticalUpdates;
    }

    /**
     * Extracts critical updates from a list of releases.
     *
     * @param string $handle The plugin or Craft CMS handle.
     * @param array $releases The list of releases.
     * @return array List of critical updates.
     */
    private function getCriticalUpdates(string $handle, array $releases): array
    {
        $criticalUpdates = [];

        foreach ($releases as $release) {
            if ($release->critical) {
                $criticalUpdates[] = [
                    'handle' => $handle,
                    'version' => $release->version,
                    'notes' => $release->notes,
                ];
            }
        }

        return $criticalUpdates;
    }
}
