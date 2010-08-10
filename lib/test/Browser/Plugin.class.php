<?php
/** Extends the functionality of Test_Browser.
 *
 * @package sfJwtPhpUnitPlugin
 * @subpackage lib.test
 */
abstract class Test_Browser_Plugin extends Test_ObjectWrapper
{
  private
    $_browser;

  /** Returns the identifying name for the plugin.
   *
   * @return string
   */
  abstract public function getName(  );

  /** (Re-)Initialize the plugin.
   *
   * Note that setup() is lazy-loaded.
   *
   * @return void
   */
  abstract public function setup(  );

  /** Clear out the results of the previous request so that a new one can be
   *   initialized.
   *
   * @return void
   */
  public function reset(  )
  {
    $this->setEncapsulatedObject(null);
  }

  /** Handles a call from getPluginInstance() in the browser instance.
   *
   * Typically, this method will return the plugin instance itself, but
   *  some plugins might not be designed to be interactive (e.g., a plugin that
   *  parses some values out of the browser response might only want to return
   *  the parsed values when _doGetInstance() is invoked).
   *
   * @param mixed,... $args
   *
   * @return mixed
   */
  protected function _doGetInstance(  )
  {
    return $this;
  }

  /** Base initialization of the plugin.
   *
   * @param Test_Browser $Browser
   *
   * @return void
   * @throws RuntimeException if the plugin has already been attached to a
   *  browser instance.
   */
  final public function init( Test_Browser $Browser )
  {
    if( isset($this->_browser) )
    {
      throw new RuntimeException(sprintf(
        'Plugin "%s" has already been attached to another browser instance.',
          $this->getName()
      ));
    }

    $this->_browser = $Browser;
    $this->reset();
  }

  /** Handles a call to getPluginInstance() from the browser object.
   *
   * @param array $args
   *
   * @return mixed
   */
  final public function getInstance( array $args = array() )
  {
    return call_user_func_array(array($this, '_doGetInstance'), $args);
  }

  /** Ensure that the encapsulated object is set before returning it.
   *
   * @return Object|null
   * @final  Overwrite _doGetInstance() to customize accessor behavior.
   */
  final public function getEncapsulatedObject(  )
  {
    return
      $this->hasEncapsulatedObject()
        ? parent::getEncapsulatedObject()
        : null;
  }

  /** Returns whether the encapsulated object exists.
   *
   * Note that because
   *
   * @return bool
   */
  public function hasEncapsulatedObject(  )
  {
    if( ! parent::hasEncapsulatedObject() )
    {
      $this->setup();
    }

    return parent::hasEncapsulatedObject();
  }

  /** Accessor for $_browser.
   *
   * @return Test_Browser
   */
  public function getBrowser(  )
  {
    return $this->_browser;
  }
}