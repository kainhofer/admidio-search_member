<?php
namespace AdmidioPlugin\SearchMember\Service;

use Admidio\Infrastructure\Database;
use Admidio\Infrastructure\Exception;

/**
 * @copyright 2004-2026 The Admidio Team
 * @see https://www.admidio.org/
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2.0 only
 */
final class UpdateStepsCode
{
    /**
     * @var Database
     */
    private static Database $db;

    /**
     * Set the database
     * @param Database $database The database instance
     */
    public static function setDatabase(Database $database)
    {
        self::$db = $database;
    }

    /**
     * Retrieve previous settings from config file and update the database settings accordingly.
     * @throws Exception
     */
    public static function updateStep10RetrievePreviousSettings()
    {
        global $gSettingsManager;

        // check if there is a config.php file with previous settings in the search_member plugin folder
        $configFile = ADMIDIO_PATH . '/adm_plugins/search_member/config.php';
        if (file_exists($configFile)) {
            // include the config file to get the previous settings
            include $configFile;

            // update the settings in the database with the previous settings from the config file if they are different to the current settings
            foreach (get_defined_vars() as $varName => $varValue) {
                if ($varName === 'plg_show_names') {
                    if ($gSettingsManager->getInt('search_member_show_names') !== (int)$varValue) {
                        $gSettingsManager->set('search_member_show_names', (int)$varValue);
                    }
                } elseif ($varName === 'plg_rolle_sql') {
                    $roles = (is_array($varValue) && !empty($varValue)) ? $varValue : array('All');
                    $rolesString = implode(',', $roles);
                    if ($gSettingsManager->get('search_member_roles_sql') !== $rolesString) {
                        $gSettingsManager->set('search_member_roles_sql', $rolesString);
                    }
                } elseif ($varName === 'plg_search_city') {
                    if ($gSettingsManager->getBool('search_member_search_city') !== (bool)$varValue) {
                        $gSettingsManager->set('search_member_search_city', (bool)$varValue);
                    }
                }
            }
        }
    }
}
