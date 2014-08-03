<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Translation Catalogue Interface
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Nooku\Library\Translator
 */
interface TranslatorCatalogueInterface extends \IteratorAggregate, \ArrayAccess, \Serializable
{
    /**
     * Get a string from the registry
     *
     * @param  string $string
     * @return  string  The translation of the string
     */
    public function get($string);

    /**
     * Set a string in the registry
     *
     * @param  string $string
     * @param  string $translation
     * @return TranslatorCatalogueInterface
     */
    public function set($string, $translation);

    /**
     * Check if a string exists in the registry
     *
     * @param  string $string
     * @return boolean
     */
    public function has($string);

    /**
     * Remove a string from the registry
     *
     * @param  string $string
     * @return TranslatorCatalogueInterface
     */
    public function remove($string);

    /**
     * Clears out all strings from the registry
     *
     * @return  TranslatorCatalogueInterface
     */
    public function clear();

    /**
     * Load translations into the catalogue.
     *
     * @param array  $translations Associative array containing translations.
     * @param bool   $override     Whether or not existing translations can be overridden during import.
     * @return bool True on success, false otherwise.
     */
    public function load($translations, $override = false);

    /**
     * Get a list of all strings in the catalogue
     *
     * @return  array
     */
    public function getStrings();

    /**
     * Get a list of all sources that are loaded
     *
     * @return  array
     */
    public function getSources();

    /**
     * Sets a source as loaded in the catalogue.
     *
     * A source can be anything that contains translations, e.g. a component, an object, a file, an URI, etc. They
     * are referenced on catalogues for determining if their translations were already loaded.
     *
     * @param mixed $source The source.
     * @return TranslatorCatalogueInterface
     */
    public function setLoaded($source);

    /**
     * Tells if translations from a given source are already loaded.
     *
     * For more information about what a source is @see TranslatorCatalogueInterface::setLoaded
     *
     * @param mixed $source The source to check against
     * @return bool True if loaded, false otherwise.
     */
    public function isLoaded($source);
}
