<?php

namespace idiorm\orm;

/**
 * A result set class for working with collections of model instances
 *
 * @author Simon Holywell <treffynnon@php.net>
 */
class IdiormResultSet implements \Countable, \IteratorAggregate, \ArrayAccess, \Serializable
{

  /**
   * The current result set as an array
   *
   * @var array
   */
  protected $_results = array();

  /**
   * Optionally set the contents of the result set by passing in array
   *
   * @param array $results
   */
  public function __construct(array $results = array())
  {
    $this->set_results($results);
  }

  /**
   * Set the contents of the result set by passing in array
   *
   * @param array $results
   */
  public function set_results(array $results)
  {
    $this->_results = $results;
  }

  /**
   * Get the current result set as an array
   *
   * @return array
   */
  public function get_results()
  {
    return $this->_results;
  }

  /**
   * Return the content of the result set
   * as an array of associative arrays. Column
   * names may optionally be supplied as arguments,
   * if so, only those keys will be returned.
   *
   * @return array
   */
  public function as_array()
  {
    $rows = array();
    $args = func_get_args();

    foreach ($this->_results as $key => $row) {
      if (
          is_object($row)
          &&
          method_exists($row, 'as_array')
          &&
          is_callable(array($row, 'as_array'))
      ) {
        $rows[] = call_user_func_array(array($row, 'as_array'), $args);
      } else {
        $rows[$key] = $row;
      }
    }

    return $rows;
  }

  /**
   * Get the current result set as an json
   *
   * @param int $options
   *
   * @return string
   */
  public function as_json($options = 0)
  {
    return json_encode($this->get_results(), $options);
  }

  /**
   * Get the number of records in the result set
   *
   * @return int
   */
  public function count()
  {
    return count($this->_results);
  }

  /**
   * Get an iterator for this object. In this case it supports foreaching
   * over the result set.
   *
   * @return \ArrayIterator
   */
  public function getIterator()
  {
    return new \ArrayIterator($this->_results);
  }

  /**
   * ArrayAccess
   *
   * @param int|string $offset
   *
   * @return bool
   */
  public function offsetExists($offset)
  {
    return isset($this->_results[$offset]);
  }

  /**
   * ArrayAccess
   *
   * @param int|string $offset
   *
   * @return mixed
   */
  public function offsetGet($offset)
  {
    return $this->_results[$offset];
  }

  /**
   * ArrayAccess
   *
   * @param int|string $offset
   * @param mixed      $value
   */
  public function offsetSet($offset, $value)
  {
    $this->_results[$offset] = $value;
  }

  /**
   * ArrayAccess
   *
   * @param int|string $offset
   */
  public function offsetUnset($offset)
  {
    unset($this->_results[$offset]);
  }

  /**
   * Serializable
   *
   * @return string
   */
  public function serialize()
  {
    return serialize($this->_results);
  }

  /**
   * Serializable
   *
   * @param string $serialized
   *
   * @return array
   */
  public function unserialize($serialized)
  {
    return unserialize($serialized);
  }

  /**
   * Call a method on all models in a result set. This allows for method
   * chaining such as setting a property on all models in a result set or
   * any other batch operation across models.
   *
   * @example ORM::for_table('Widget')->find_many()->set('field', 'value')->save();
   *
   * @param string $method
   * @param array  $params
   *
   * @return $this
   *
   * @throws IdiormMethodMissingException
   */
  public function __call($method, $params = array())
  {
    foreach ($this->_results as $model) {
      if (method_exists($model, $method)) {
        call_user_func_array(array($model, $method), $params);
      } else {
        throw new IdiormMethodMissingException("Method $method() does not exist in class " . get_class($this));
      }
    }

    return $this;
  }

}
