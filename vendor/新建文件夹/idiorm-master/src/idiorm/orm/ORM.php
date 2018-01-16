<?php

namespace idiorm\orm;

/**
 *
 * Idiorm
 *
 * http://github.com/j4mie/idiorm/
 *
 * A single-class super-simple database abstraction layer for PHP.
 * Provides (nearly) zero-configuration object-relational mapping
 * and a fluent interface for building basic, commonly-used queries.
 *
 * BSD Licensed.
 *
 * Copyright (c) 2010, Jamie Matthews
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */
class ORM implements \ArrayAccess
{

  // ----------------------- //
  // --- CLASS CONSTANTS --- //
  // ----------------------- //

  // WHERE and HAVING condition array keys
  const CONDITION_FRAGMENT = 0;
  const CONDITION_VALUES   = 1;
  const DEFAULT_CONNECTION = 'default';

  // Limit clause style
  const LIMIT_STYLE_TOP_N = 'top';
  const LIMIT_STYLE_LIMIT = 'limit';

  // ------------------------ //
  // --- CLASS PROPERTIES --- //
  // ------------------------ //

  /**
   * Class configuration
   *
   * @var array
   */
  protected static $_default_config = array(
      'connection_string'          => 'sqlite::memory:',
      'id_column'                  => 'id',
      'id_column_overrides'        => array(),
      'error_mode'                 => \PDO::ERRMODE_EXCEPTION,
      'username'                   => null,
      'password'                   => null,
      'driver_options'             => null,
      'identifier_quote_character' => null, // if this is null, will be autodetected
      'limit_clause_style'         => null, // if this is null, will be autodetected
      'logging'                    => false,
      'logger'                     => null,
      'caching'                    => false,
      'caching_auto_clear'         => false,
      'return_result_sets'         => false,
  );

  /**
   * Map of configuration settings
   *
   * @var array
   */
  protected static $_config = array();

  /**
   * Map of database connections, instances of the PDO class
   *
   * @var array
   */
  protected static $_db = array();

  /**
   * Last query run, only populated if logging is enabled
   *
   * @var string
   */
  protected static $_last_query;

  /**
   * Log of all queries run, mapped by connection key, only populated if logging is enabled
   *
   * @var array
   */
  protected static $_query_log = array();

  /**
   * Query cache, only used if query caching is enabled
   *
   * @var array
   */
  protected static $_query_cache = array();

  /**
   * Reference to previously used PDOStatement object to enable low-level access, if needed
   *
   * @var null|\PDOStatement
   */
  protected static $_last_statement = null;

  // --------------------------- //
  // --- INSTANCE PROPERTIES --- //
  // --------------------------- //

  /**
   * Key name of the connections in static::$_db used by this instance
   *
   * @var string
   */
  protected $_connection_name;

  /**
   * The name of the table the current ORM instance is associated with
   *
   * @var string
   */
  protected $_table_name;

  /**
   * Alias for the table to be used in SELECT queries
   *
   * @var null|string
   */
  protected $_table_alias = null;

  /**
   * Values to be bound to the query
   *
   * @var array
   */
  protected $_values = array();

  /**
   * Columns to select in the result
   *
   * @var array
   */
  protected $_result_columns = array('*');

  /**
   * Are we using the default result column or have these been manually changed?
   *
   * @var bool
   */
  protected $_using_default_result_columns = true;

  /**
   * Join sources
   *
   * @var array
   */
  protected $_join_sources = array();

  /**
   * Should the query include a DISTINCT keyword?
   *
   * @var bool
   */
  protected $_distinct = false;

  /**
   * Is this a raw query?
   *
   * @var bool
   */
  protected $_is_raw_query = false;

  /**
   * The raw query
   *
   * @var string
   */
  protected $_raw_query = '';

  /**
   * The raw query parameters
   *
   * @var array
   */
  protected $_raw_parameters = array();

  /**
   * Array of WHERE clauses
   *
   * @var array
   */
  protected $_where_conditions = array();

  /**
   * LIMIT
   *
   * @var null|int
   */
  protected $_limit = null;

  /**
   * OFFSET
   *
   * @var null|int
   */
  protected $_offset = null;

  /**
   * ORDER BY
   *
   * @var array
   */
  protected $_order_by = array();

  /**
   * GROUP BY
   *
   * @var array
   */
  protected $_group_by = array();

  /**
   * HAVING
   *
   * @var array
   */
  protected $_having_conditions = array();

  /**
   * The data for a hydrated instance of the class
   *
   * @var array
   */
  protected $_data = array();

  /**
   * Fields that have been modified during the lifetime of the object
   *
   * @var array
   */
  protected $_dirty_fields = array();

  /**
   * Fields that are to be inserted in the DB raw
   *
   * @var array
   */
  protected $_expr_fields = array();

  /**
   * Is this a new object (has create() been called)?
   *
   * @var bool
   */
  protected $_is_new = false;

  /**
   * Name of the column to use as the primary key for
   * this instance only. Overrides the config settings.
   *
   * @var null|string
   */
  protected $_instance_id_column = null;

  /**
   * Refresh cache for current query?
   *
   * @var bool
   */
  protected $_refresh_cache = false;

  /**
   * Disable caching for current query?
   *
   * @var bool
   */
  protected $_no_caching = false;

  // ---------------------- //
  // --- STATIC METHODS --- //
  // ---------------------- //

  /**
   * Pass configuration settings to the class in the form of
   * key/value pairs. As a shortcut, if the second argument
   * is omitted and the key is a string, the setting is
   * assumed to be the DSN string used by PDO to connect
   * to the database (often, this will be the only configuration
   * required to use Idiorm). If you have more than one setting
   * you wish to configure, another shortcut is to pass an array
   * of settings (and omit the second argument).
   *
   * @param string|array $key
   * @param mixed        $value
   * @param string       $connection_name Which connection to use
   */
  public static function configure($key, $value = null, $connection_name = self::DEFAULT_CONNECTION)
  {
    static::_setup_db_config($connection_name); //ensures at least default config is set

    if (is_array($key)) {
      // Shortcut: If only one array argument is passed,
      // assume it's an array of configuration settings
      foreach ($key as $conf_key => $conf_value) {
        static::configure($conf_key, $conf_value, $connection_name);
      }
    } else {
      if (null === $value) {
        // Shortcut: If only one string argument is passed,
        // assume it's a connection string
        $value = $key;
        $key = 'connection_string';
      }
      static::$_config[$connection_name][$key] = $value;
    }
  }

  /**
   * Retrieve configuration options by key, or as whole array.
   *
   * @param string|null $key
   * @param string      $connection_name Which connection to use
   *
   * @return string|array
   */
  public static function get_config($key = null, $connection_name = self::DEFAULT_CONNECTION)
  {
    if ($key) {
      return static::$_config[$connection_name][$key];
    } else {
      return static::$_config[$connection_name];
    }
  }

  /**
   * Delete all configs in _config array.
   */
  public static function reset_config()
  {
    static::$_config = array();
  }

  /**
   * Despite its slightly odd name, this is actually the factory
   * method used to acquire instances of the class. It is named
   * this way for the sake of a readable interface, ie
   * ORM::for_table('table_name')->find_one()-> etc. As such,
   * this will normally be the first method called in a chain.
   *
   * @param string $table_name
   * @param string $connection_name Which connection to use
   *
   * @return static
   */
  public static function for_table($table_name, $connection_name = self::DEFAULT_CONNECTION)
  {
    static::_setup_db($connection_name);

    return new static($table_name, array(), $connection_name);
  }

  /**
   * Set up the database connection used by the class
   *
   * @param string $connection_name Which connection to use
   */
  protected static function _setup_db($connection_name = self::DEFAULT_CONNECTION)
  {
    if (
        !array_key_exists($connection_name, static::$_db)
        ||
        !is_object(static::$_db[$connection_name])
    ) {
      static::_setup_db_config($connection_name);

      $db = new \PDO(
          static::$_config[$connection_name]['connection_string'], static::$_config[$connection_name]['username'], static::$_config[$connection_name]['password'], static::$_config[$connection_name]['driver_options']
      );

      $db->setAttribute(\PDO::ATTR_ERRMODE, static::$_config[$connection_name]['error_mode']);
      static::set_db($db, $connection_name);
    }
  }

  /**
   * Ensures configuration (multiple connections) is at least set to default.
   *
   * @param string $connection_name Which connection to use
   */
  protected static function _setup_db_config($connection_name)
  {
    if (!array_key_exists($connection_name, static::$_config)) {
      static::$_config[$connection_name] = static::$_default_config;
    }
  }

  /**
   * Set the PDO object used by Idiorm to communicate with the database.
   * This is public in case the ORM should use a ready-instantiated
   * PDO object as its database connection. Accepts an optional string key
   * to identify the connection if multiple connections are used.
   *
   * @param \PDO   $db
   * @param string $connection_name Which connection to use
   */
  public static function set_db($db, $connection_name = self::DEFAULT_CONNECTION)
  {
    static::_setup_db_config($connection_name);
    static::$_db[$connection_name] = $db;

    if (null !== static::$_db[$connection_name]) {
      static::_setup_identifier_quote_character($connection_name);
      static::_setup_limit_clause_style($connection_name);
    }
  }

  /**
   * Delete all registered PDO objects in _db array.
   */
  public static function reset_db()
  {
    static::$_db = array();
  }

  /**
   * Detect and initialise the character used to quote identifiers
   * (table names, column names etc). If this has been specified
   * manually using ORM::configure('identifier_quote_character', 'some-char'),
   * this will do nothing.
   *
   * @param string $connection_name Which connection to use
   */
  protected static function _setup_identifier_quote_character($connection_name)
  {
    if (null === static::$_config[$connection_name]['identifier_quote_character']) {
      static::$_config[$connection_name]['identifier_quote_character'] = static::_detect_identifier_quote_character($connection_name);
    }
  }

  /**
   * Detect and initialise the limit clause style ("SELECT TOP 5" /
   * "... LIMIT 5"). If this has been specified manually using
   * ORM::configure('limit_clause_style', 'top'), this will do nothing.
   *
   * @param string $connection_name Which connection to use
   */
  public static function _setup_limit_clause_style($connection_name)
  {
    if (null === static::$_config[$connection_name]['limit_clause_style']) {
      static::$_config[$connection_name]['limit_clause_style'] = static::_detect_limit_clause_style($connection_name);
    }
  }

  /**
   * Return the correct character used to quote identifiers (table
   * names, column names etc) by looking at the driver being used by PDO.
   *
   * @param string $connection_name Which connection to use
   *
   * @return string
   */
  protected static function _detect_identifier_quote_character($connection_name)
  {
    switch (static::get_db($connection_name)->getAttribute(\PDO::ATTR_DRIVER_NAME)) {
      case 'pgsql':
      case 'sqlsrv':
      case 'dblib':
      case 'mssql':
      case 'sybase':
      case 'firebird':
        return '"';
      case 'mysql':
      case 'sqlite':
      case 'sqlite2':
      default:
        return '`';
    }
  }

  /**
   * Returns a constant after determining the appropriate limit clause
   * style
   *
   * @param string $connection_name Which connection to use
   *
   * @return string Limit clause style keyword/constant
   */
  protected static function _detect_limit_clause_style($connection_name)
  {
    switch (static::get_db($connection_name)->getAttribute(\PDO::ATTR_DRIVER_NAME)) {
      case 'sqlsrv':
      case 'dblib':
      case 'mssql':
        return self::LIMIT_STYLE_TOP_N;
      default:
        return self::LIMIT_STYLE_LIMIT;
    }
  }

  /**
   * Returns the PDO instance used by the the ORM to communicate with
   * the database. This can be called if any low-level DB access is
   * required outside the class. If multiple connections are used,
   * accepts an optional key name for the connection.
   *
   * @param string $connection_name Which connection to use
   *
   * @return \PDO
   */
  public static function get_db($connection_name = self::DEFAULT_CONNECTION)
  {
    static::_setup_db($connection_name); // required in case this is called before Idiorm is instantiated
    return static::$_db[$connection_name];
  }

  /**
   * Executes a raw query as a wrapper for PDOStatement::execute.
   * Useful for queries that can't be accomplished through Idiorm,
   * particularly those using engine-specific features.
   *
   * @example raw_execute('SELECT `name`, AVG(`order`) FROM `customer` GROUP BY `name` HAVING AVG(`order`) > 10')
   * @example raw_execute('INSERT OR REPLACE INTO `widget` (`id`, `name`) SELECT `id`, `name` FROM `other_table`')
   *
   * @param string $query           The raw SQL query
   * @param array  $parameters      Optional bound parameters
   * @param string $connection_name Which connection to use
   *
   * @return bool Success
   */
  public static function raw_execute($query, $parameters = array(), $connection_name = self::DEFAULT_CONNECTION)
  {
    static::_setup_db($connection_name);

    return static::_execute($query, $parameters, $connection_name);
  }

  /**
   * Returns the PDOStatement instance last used by any connection wrapped by the ORM.
   * Useful for access to PDOStatement::rowCount() or error information
   *
   * @return \PDOStatement
   */
  public static function get_last_statement()
  {
    return static::$_last_statement;
  }

  /**
   * Internal helper method for executing statements. Logs queries, and
   * stores statement object in ::_last_statement, accessible publicly
   * through ::get_last_statement()
   *
   * @param string $query
   * @param array  $parameters      An array of parameters to be bound in to the query
   * @param string $connection_name Which connection to use
   *
   * @return bool Response of PDOStatement::execute()
   *
   * @throws \Exception
   */
  protected static function _execute($query, $parameters = array(), $connection_name = self::DEFAULT_CONNECTION)
  {
    $time = microtime(true);

    try {
      $statement = static::get_db($connection_name)->prepare($query);
      static::$_last_statement = $statement;

      foreach ($parameters as $key => &$param) {

        if (null === $param) {
          $type = \PDO::PARAM_NULL;
        } elseif (is_bool($param)) {
          $type = \PDO::PARAM_BOOL;
        } elseif (is_int($param)) {
          $type = \PDO::PARAM_INT;
        } else {
          $type = \PDO::PARAM_STR;
        }

        $statement->bindParam(is_int($key) ? ++$key : $key, $param, $type);
      }
      unset($param);

      $q = $statement->execute();
      static::_log_query($query, $parameters, $connection_name, microtime(true) - $time);
    } catch (\Exception $ex) {
      static::_log_query($query, $parameters, $connection_name, microtime(true) - $time);
      throw $ex;
    }

    return $q;
  }

  /**
   * Add a query to the internal query log. Only works if the
   * 'logging' config option is set to true.
   *
   * This works by manually binding the parameters to the query - the
   * query isn't executed like this (PDO normally passes the query and
   * parameters to the database which takes care of the binding) but
   * doing it this way makes the logged queries more readable.
   *
   * @param string $query
   * @param array  $parameters      An array of parameters to be bound in to the query
   * @param string $connection_name Which connection to use
   * @param float  $query_time      Query time
   *
   * @return bool
   */
  protected static function _log_query($query, $parameters, $connection_name, $query_time)
  {
    // If logging is not enabled, do nothing
    if (!static::$_config[$connection_name]['logging']) {
      return false;
    }

    if (!isset(static::$_query_log[$connection_name])) {
      static::$_query_log[$connection_name] = array();
    }

    // Strip out any non-integer indexes from the parameters
    foreach ($parameters as $key => $value) {
      if (!is_int($key)) {
        unset($parameters[$key]);
      }
    }

    if (count($parameters) > 0) {
      // Escape the parameters
      $parameters = array_map(array(static::get_db($connection_name), 'quote'), $parameters);

      // Avoid %format collision for vsprintf
      $query = str_replace('%', '%%', $query);

      // Replace placeholders in the query for vsprintf
      if (false !== strpos($query, "'") || false !== strpos($query, '"')) {
        $query = IdiormString::str_replace_outside_quotes('?', '%s', $query);
      } else {
        $query = str_replace('?', '%s', $query);
      }

      // Replace the question marks in the query with the parameters
      $bound_query = vsprintf($query, $parameters);
    } else {
      $bound_query = $query;
    }

    static::$_last_query = $bound_query;
    static::$_query_log[$connection_name][] = $bound_query;


    if (is_callable(static::$_config[$connection_name]['logger'])) {
      $logger = static::$_config[$connection_name]['logger'];
      $logger($bound_query, $query_time);
    }

    return true;
  }

  /**
   * Get the last query executed. Only works if the
   * 'logging' config option is set to true. Otherwise
   * this will return null. Returns last query from all connections if
   * no connection_name is specified
   *
   * @param null|string $connection_name Which connection to use
   *
   * @return string
   */
  public static function get_last_query($connection_name = null)
  {
    if ($connection_name === null) {
      return static::$_last_query;
    }
    if (!isset(static::$_query_log[$connection_name])) {
      return '';
    }

    return end(static::$_query_log[$connection_name]);
  }

  /**
   * Get an array containing all the queries run on a
   * specified connection up to now.
   * Only works if the 'logging' config option is
   * set to true. Otherwise, returned array will be empty.
   *
   * @param string $connection_name Which connection to use
   *
   * @return array
   */
  public static function get_query_log($connection_name = self::DEFAULT_CONNECTION)
  {
    if (isset(static::$_query_log[$connection_name])) {
      return static::$_query_log[$connection_name];
    }

    return array();
  }

  /**
   * Get a list of the available connection names
   *
   * @return array
   */
  public static function get_connection_names()
  {
    return array_keys(static::$_db);
  }

  // ------------------------ //
  // --- INSTANCE METHODS --- //
  // ------------------------ //

  /**
   * "Private" constructor; shouldn't be called directly.
   * Use the ORM::for_table factory method instead.
   *
   * @param string $table_name
   * @param array  $data
   * @param string $connection_name
   */
  protected function __construct($table_name, $data = array(), $connection_name = self::DEFAULT_CONNECTION)
  {
    $this->_table_name = $table_name;
    $this->_data = $data;

    $this->_connection_name = $connection_name;
    static::_setup_db_config($connection_name);
  }

  /**
   * Create a new, empty instance of the class. Used
   * to add a new row to your database. May optionally
   * be passed an associative array of data to populate
   * the instance. If so, all fields will be flagged as
   * dirty so all will be saved to the database when
   * save() is called.
   *
   * @param null|array $data
   *
   * @return $this
   */
  public function create($data = null)
  {
    $this->_is_new = true;
    if (null !== $data) {
      return $this->hydrate($data)->force_all_dirty();
    }

    return $this;
  }

  /**
   * Specify the ID column to use for this instance or array of instances only.
   * This overrides the id_column and id_column_overrides settings.
   *
   * This is mostly useful for libraries built on top of Idiorm, and will
   * not normally be used in manually built queries. If you don't know why
   * you would want to use this, you should probably just ignore it.
   *
   * @param string $id_column
   *
   * @return $this
   */
  public function use_id_column($id_column)
  {
    $this->_instance_id_column = $id_column;

    return $this;
  }

  /**
   * Create an ORM instance from the given row (an associative
   * array of data fetched from the database)
   *
   * @param array $row
   *
   * @return $this
   */
  protected function _create_instance_from_row($row)
  {
    $instance = static::for_table($this->_table_name, $this->_connection_name);
    $instance->use_id_column($this->_instance_id_column);
    $instance->hydrate($row);

    return $instance;
  }

  /**
   * Tell the ORM that you are expecting a single result
   * back from your query, and execute it. Will return
   * a single instance of the ORM class, or false if no
   * rows were returned.
   * As a shortcut, you may supply an ID as a parameter
   * to this method. This will perform a primary key
   * lookup on the table.
   *
   * @param mixed $id
   *
   * @return false|ORM false on error
   */
  public function find_one($id = null)
  {
    if (null !== $id) {
      $this->where_id_is($id);
    }
    $this->limit(1);
    $rows = $this->_run();

    if (empty($rows)) {
      return false;
    }

    return $this->_create_instance_from_row($rows[0]);
  }

  /**
   * Tell the ORM that you are expecting multiple results
   * from your query, and execute it. Will return an array
   * of instances of the ORM class, or an empty array if
   * no rows were returned.
   *
   * @return array|IdiormResultSet
   */
  public function find_many()
  {
    if (static::$_config[$this->_connection_name]['return_result_sets']) {
      return $this->find_result_set();
    }

    return $this->_find_many();
  }

  /**
   * Tell the ORM that you are expecting multiple results
   * from your query, and execute it. Will return an array
   * of instances of the ORM class, or an empty array if
   * no rows were returned.
   *
   * @return array
   */
  protected function _find_many()
  {
    $rows = $this->_run();

    return array_map(array($this, '_create_instance_from_row'), $rows);
  }

  /**
   * Tell the ORM that you are expecting multiple results
   * from your query, and execute it. Will return a result set object
   * containing instances of the ORM class.
   *
   * @return IdiormResultSet
   */
  public function find_result_set()
  {
    return new IdiormResultSet($this->_find_many());
  }

  /**
   * Tell the ORM that you are expecting multiple results
   * from your query, and execute it. Will return an array,
   * or an empty array if no rows were returned.
   *
   * @return array
   */
  public function find_array()
  {
    return $this->_run();
  }

  /**
   * Tell the ORM that you wish to execute a COUNT query.
   * Will return an integer representing the number of
   * rows returned.
   *
   * @param string $column
   *
   * @return int
   */
  public function count($column = '*')
  {
    return $this->_call_aggregate_db_function(__FUNCTION__, $column);
  }

  /**
   * Tell the ORM that you wish to execute a MAX query.
   * Will return the max value of the choosen column.
   *
   * @param string $column
   *
   * @return int
   */
  public function max($column)
  {
    return $this->_call_aggregate_db_function(__FUNCTION__, $column);
  }

  /**
   * Tell the ORM that you wish to execute a MIN query.
   * Will return the min value of the choosen column.
   *
   * @param string $column
   *
   * @return int|float
   */
  public function min($column)
  {
    return $this->_call_aggregate_db_function(__FUNCTION__, $column);
  }

  /**
   * Tell the ORM that you wish to execute a AVG query.
   * Will return the average value of the choosen column.
   *
   * @param string $column
   *
   * @return float
   */
  public function avg($column)
  {
    return $this->_call_aggregate_db_function(__FUNCTION__, $column);
  }

  /**
   * Tell the ORM that you wish to execute a SUM query.
   * Will return the sum of the choosen column.
   *
   * @param string $column
   *
   * @return int|float
   */
  public function sum($column)
  {
    return $this->_call_aggregate_db_function(__FUNCTION__, $column);
  }

  /**
   * Execute an aggregate query on the current connection.
   *
   * @param string $sql_function The aggregate function to call eg. MIN, COUNT, etc
   * @param string $column       The column to execute the aggregate query against
   *
   * @return float|int|mixed
   */
  protected function _call_aggregate_db_function($sql_function, $column)
  {
    $alias = strtolower($sql_function);
    $sql_function = strtoupper($sql_function);
    if ('*' != $column) {
      $column = $this->_quote_identifier($column);
    }
    $result_columns = $this->_result_columns;
    $this->_result_columns = array();
    $this->select_expr("$sql_function($column)", $alias);
    $result = $this->find_one();
    $this->_result_columns = $result_columns;

    $return_value = 0;
    if ($result !== false && isset($result->$alias)) {
      if (!is_numeric($result->$alias)) {
        $return_value = $result->$alias;
      } elseif ((int)$result->$alias == (float)$result->$alias) {
        $return_value = (int)$result->$alias;
      } else {
        $return_value = (float)$result->$alias;
      }
    }

    return $return_value;
  }

  /**
   * This method can be called to hydrate (populate) this
   * instance of the class from an associative array of data.
   * This will usually be called only from inside the class,
   * but it's public in case you need to call it directly.
   *
   * @param array $data
   *
   * @return $this
   */
  public function hydrate($data = array())
  {
    $this->_data = $data;

    return $this;
  }

  /**
   * Force the ORM to flag all the fields in the $data array
   * as "dirty" and therefore update them when save() is called.
   *
   * @return $this
   */
  public function force_all_dirty()
  {
    $this->_dirty_fields = $this->_data;

    return $this;
  }

  /**
   * Perform a raw query. The query can contain placeholders in
   * either named or question mark style. If placeholders are
   * used, the parameters should be an array of values which will
   * be bound to the placeholders in the query. If this method
   * is called, all other query building methods will be ignored.
   *
   * @param string $query
   * @param array  $parameters
   *
   * @return $this
   */
  public function raw_query($query, $parameters = array())
  {
    $this->_is_raw_query = true;
    $this->_raw_query = $query;
    $this->_raw_parameters = $parameters;

    return $this;
  }

  /**
   * Add an alias for the main table to be used in SELECT queries
   *
   * @param string $alias
   *
   * @return $this
   */
  public function table_alias($alias)
  {
    $this->_table_alias = $alias;

    return $this;
  }

  /**
   * Internal method to add an unquoted expression to the set
   * of columns returned by the SELECT query. The second optional
   * argument is the alias to return the expression as.
   *
   * @param string $expr
   * @param mixed  $alias
   *
   * @return $this
   */
  protected function _add_result_column($expr, $alias = null)
  {
    if (null !== $alias) {
      $expr .= ' AS ' . $this->_quote_identifier($alias);
    }

    if ($this->_using_default_result_columns) {
      $this->_result_columns = array($expr);
      $this->_using_default_result_columns = false;
    } else {
      $this->_result_columns[] = $expr;
    }

    return $this;
  }

  /**
   * Counts the number of columns that belong to the primary
   * key and their value is null.
   *
   * @return int
   */
  public function count_null_id_columns()
  {
    if (is_array($this->_get_id_column_name())) {
      return count(array_filter($this->id(), 'is_null'));
    } else {
      return (null === $this->id() ? 1 : 0);
    }
  }

  /**
   * Add a column to the list of columns returned by the SELECT
   * query. This defaults to '*'. The second optional argument is
   * the alias to return the column as.
   *
   * @param string $column
   * @param mixed  $alias
   *
   * @return $this
   */
  public function select($column, $alias = null)
  {
    $column = $this->_quote_identifier($column);

    return $this->_add_result_column($column, $alias);
  }

  /**
   * Add an unquoted expression to the list of columns returned
   * by the SELECT query. The second optional argument is
   * the alias to return the column as.
   *
   * @param string $expr
   * @param mixed  $alias
   *
   * @return $this
   */
  public function select_expr($expr, $alias = null)
  {
    return $this->_add_result_column($expr, $alias);
  }

  /**
   * Add columns to the list of columns returned by the SELECT
   * query. This defaults to '*'. Many columns can be supplied
   * as either an array or as a list of parameters to the method.
   *
   * Note that the alias must not be numeric - if you want a
   * numeric alias then prepend it with some alpha chars. eg. a1
   *
   * @example select_many(array('alias' => 'column', 'column2', 'alias2' => 'column3'), 'column4', 'column5');
   * @example select_many('column', 'column2', 'column3');
   * @example select_many(array('column', 'column2', 'column3'), 'column4', 'column5');
   *
   * @return $this
   */
  public function select_many()
  {
    $columns = func_get_args();
    if (!empty($columns)) {
      $columns = $this->_normalise_select_many_columns($columns);
      foreach ($columns as $alias => $column) {
        if (is_numeric($alias)) {
          $alias = null;
        }
        $this->select($column, $alias);
      }
    }

    return $this;
  }

  /**
   * Add an unquoted expression to the list of columns returned
   * by the SELECT query. Many columns can be supplied as either
   * an array or as a list of parameters to the method.
   *
   * Note that the alias must not be numeric - if you want a
   * numeric alias then prepend it with some alpha chars. eg. a1
   *
   * @example select_many_expr(array('alias' => 'column', 'column2', 'alias2' => 'column3'), 'column4', 'column5')
   * @example select_many_expr('column', 'column2', 'column3')
   * @example select_many_expr(array('column', 'column2', 'column3'), 'column4', 'column5')
   *
   * @return $this
   */
  public function select_many_expr()
  {
    $columns = func_get_args();
    if (!empty($columns)) {
      $columns = $this->_normalise_select_many_columns($columns);
      foreach ($columns as $alias => $column) {
        if (is_numeric($alias)) {
          $alias = null;
        }
        $this->select_expr($column, $alias);
      }
    }

    return $this;
  }

  /**
   * Take a column specification for the select many methods and convert it
   * into a normalised array of columns and aliases.
   *
   * It is designed to turn the following styles into a normalised array:
   *
   * array(array('alias' => 'column', 'column2', 'alias2' => 'column3'), 'column4', 'column5'))
   *
   * @param array $columns
   *
   * @return array
   */
  protected function _normalise_select_many_columns($columns)
  {
    $return = array();
    foreach ($columns as $column) {
      if (is_array($column)) {
        foreach ($column as $key => $value) {
          if (!is_numeric($key)) {
            $return[$key] = $value;
          } else {
            $return[] = $value;
          }
        }
      } else {
        $return[] = $column;
      }
    }

    return $return;
  }

  /**
   * Add a DISTINCT keyword before the list of columns in the SELECT query
   *
   * @return $this
   */
  public function distinct()
  {
    $this->_distinct = true;

    return $this;
  }

  /**
   * Internal method to add a JOIN source to the query.
   *
   * The join_operator should be one of INNER, LEFT OUTER, CROSS etc - this
   * will be prepended to JOIN.
   *
   * The table should be the name of the table to join to.
   *
   * The constraint may be either a string or an array with three elements. If it
   * is a string, it will be compiled into the query as-is, with no escaping. The
   * recommended way to supply the constraint is as an array with three elements:
   *
   * first_column, operator, second_column
   *
   * Example: array('user.id', '=', 'profile.user_id')
   *
   * will compile to
   *
   * ON `user`.`id` = `profile`.`user_id`
   *
   * The final (optional) argument specifies an alias for the joined table.
   *
   * @param string      $join_operator
   * @param string      $table
   * @param string      $constraint
   * @param string|null $table_alias
   *
   * @return $this
   */
  protected function _add_join_source($join_operator, $table, $constraint, $table_alias = null)
  {
    $join_operator = trim("{$join_operator} JOIN");

    $table = $this->_quote_identifier($table);

    // Add table alias if present
    if (null !== $table_alias) {
      $table_alias = $this->_quote_identifier($table_alias);
      $table .= " {$table_alias}";
    }

    // Build the constraint
    if (is_array($constraint)) {
      list($first_column, $operator, $second_column) = $constraint;
      $first_column = $this->_quote_identifier($first_column);
      $second_column = $this->_quote_identifier($second_column);
      $constraint = "{$first_column} {$operator} {$second_column}";
    }

    $this->_join_sources[] = "{$join_operator} {$table} ON {$constraint}";

    return $this;
  }

  /**
   * Add a RAW JOIN source to the query
   *
   * @param string $table
   * @param string $constraint
   * @param string $table_alias
   * @param array  $parameters
   *
   * @return $this
   */
  public function raw_join($table, $constraint, $table_alias, $parameters = array())
  {
    // Add table alias if present
    if (null !== $table_alias) {
      $table_alias = $this->_quote_identifier($table_alias);
      $table .= " {$table_alias}";
    }

    $this->_values = array_merge($this->_values, $parameters);

    // Build the constraint
    if (is_array($constraint)) {
      list($first_column, $operator, $second_column) = $constraint;
      $first_column = $this->_quote_identifier($first_column);
      $second_column = $this->_quote_identifier($second_column);
      $constraint = "{$first_column} {$operator} {$second_column}";
    }

    $this->_join_sources[] = "{$table} ON {$constraint}";

    return $this;
  }

  /**
   * Add a simple JOIN source to the query
   *
   * @param string      $table
   * @param string      $constraint
   * @param string|null $table_alias
   *
   * @return $this
   */
  public function join($table, $constraint, $table_alias = null)
  {
    return $this->_add_join_source('', $table, $constraint, $table_alias);
  }

  /**
   * Add an INNER JOIN souce to the query
   */
  /**
   * @param string      $table
   * @param string      $constraint
   * @param null|string $table_alias
   *
   * @return $this
   */
  public function inner_join($table, $constraint, $table_alias = null)
  {
    return $this->_add_join_source('INNER', $table, $constraint, $table_alias);
  }

  /**
   * Add an LEFT JOIN source to the query
   *
   * @param string $table
   * @param        $constraint
   * @param null   $table_alias
   *
   * @return $this
   */
  public function left_join($table, $constraint, $table_alias = null)
  {
    return $this->_add_join_source('LEFT', $table, $constraint, $table_alias);
  }

  /**
   * Add an RIGHT JOIN source to the query
   *
   * @param string $table
   * @param        $constraint
   * @param null   $table_alias
   *
   * @return $this
   */
  public function right_join($table, $constraint, $table_alias = null)
  {
    return $this->_add_join_source('RIGHT', $table, $constraint, $table_alias);
  }

  /**
   * Add a LEFT OUTER JOIN souce to the query
   *
   * @param string      $table
   * @param string      $constraint
   * @param null|string $table_alias
   *
   * @return $this
   */
  public function left_outer_join($table, $constraint, $table_alias = null)
  {
    return $this->_add_join_source('LEFT OUTER', $table, $constraint, $table_alias);
  }

  /**
   * Add an RIGHT OUTER JOIN souce to the query
   *
   * @param string      $table
   * @param string      $constraint
   * @param null|string $table_alias
   *
   * @return $this
   */
  public function right_outer_join($table, $constraint, $table_alias = null)
  {
    return $this->_add_join_source('RIGHT OUTER', $table, $constraint, $table_alias);
  }

  /**
   * Add an FULL OUTER JOIN souce to the query
   */
  /**
   * @param string      $table
   * @param string      $constraint
   * @param null|string $table_alias
   *
   * @return $this
   */
  public function full_outer_join($table, $constraint, $table_alias = null)
  {
    return $this->_add_join_source('FULL OUTER', $table, $constraint, $table_alias);
  }

  /**
   * Internal method to add a HAVING condition to the query
   */
  /**
   * @param string $fragment
   * @param array  $values
   *
   * @return $this
   */
  protected function _add_having($fragment, $values = array())
  {
    return $this->_add_condition('having', $fragment, $values);
  }

  /**
   * Internal method to add a HAVING condition to the query
   */
  /**
   * @param string|array $column_name
   * @param string       $separator
   * @param mixed        $value
   *
   * @return $this
   */
  protected function _add_simple_having($column_name, $separator, $value)
  {
    return $this->_add_simple_condition('having', $column_name, $separator, $value);
  }

  /**
   * Internal method to add a HAVING clause with multiple values (like IN and NOT IN)
   */
  /**
   * @param string|array $column_name
   * @param string       $separator
   * @param mixed        $values
   *
   * @return $this
   */
  public function _add_having_placeholder($column_name, $separator, $values)
  {
    if (!is_array($column_name)) {
      $data = array($column_name => $values);
    } else {
      $data = $column_name;
    }

    $result = $this;
    foreach ($data as $key => $val) {
      $column = $result->_quote_identifier($key);
      $placeholders = $result->_create_placeholders($val);
      $result = $result->_add_having("{$column} {$separator} ({$placeholders})", $val);
    }

    return $result;
  }

  /**
   * Internal method to add a HAVING clause with no parameters(like IS NULL and IS NOT NULL)
   *
   * @param string|array $column_name
   * @param string       $operator
   *
   * @return $this
   */
  public function _add_having_no_value($column_name, $operator)
  {
    if (is_array($column_name)) {
      $conditions = $column_name;
    } else {
      $conditions = array($column_name);
    }

    $result = $this;
    foreach ($conditions as $column) {
      $column = $this->_quote_identifier($column);
      $result = $result->_add_having("{$column} {$operator}");
    }

    return $result;
  }

  /**
   * Internal method to add a WHERE condition to the query
   *
   * @param string $fragment
   * @param array  $values
   *
   * @return $this
   */
  protected function _add_where($fragment, $values = array())
  {
    return $this->_add_condition('where', $fragment, $values);
  }

  /**
   * Internal method to add a WHERE condition to the query
   *
   * @param string|array $column_name
   * @param string       $separator
   * @param mixed        $value
   *
   * @return $this
   */
  protected function _add_simple_where($column_name, $separator, $value)
  {
    return $this->_add_simple_condition('where', $column_name, $separator, $value);
  }

  /**
   * Add a WHERE clause with multiple values (like IN and NOT IN)
   *
   * @param string|array $column_name
   * @param string       $separator
   * @param mixed        $values
   *
   * @return $this
   */
  public function _add_where_placeholder($column_name, $separator, $values)
  {
    if (!is_array($column_name)) {
      $data = array($column_name => $values);
    } else {
      $data = $column_name;
    }

    $result = $this;
    foreach ($data as $key => $val) {
      $column = $result->_quote_identifier($key);
      $placeholders = $result->_create_placeholders($val);
      $result = $result->_add_where("{$column} {$separator} ({$placeholders})", $val);
    }

    return $result;
  }

  /**
   * Add a WHERE clause with no parameters(like IS NULL and IS NOT NULL)
   *
   * @param string|array $column_name
   * @param string       $operator
   *
   * @return $this
   */
  public function _add_where_no_value($column_name, $operator)
  {
    if (is_array($column_name)) {
      $conditions = $column_name;
    } else {
      $conditions = array($column_name);
    }

    $result = $this;
    foreach ($conditions as $column) {
      $column = $this->_quote_identifier($column);
      $result = $result->_add_where("{$column} {$operator}");
    }

    return $result;
  }

  /**
   * Internal method to add a HAVING or WHERE condition to the query
   *
   * @param string $type
   * @param string $fragment
   * @param mixed  $values
   *
   * @return $this
   */
  protected function _add_condition($type, $fragment, $values = array())
  {
    $conditions_class_property_name = "_{$type}_conditions";

    if (!is_array($values)) {
      $values = array($values);
    }

    array_push(
        $this->$conditions_class_property_name,
        array(
            static::CONDITION_FRAGMENT => $fragment,
            static::CONDITION_VALUES   => $values,
        )
    );

    return $this;
  }

  /**
   * Helper method to compile a simple COLUMN SEPARATOR VALUE
   * style HAVING or WHERE condition into a string and value ready to
   * be passed to the _add_condition method. Avoids duplication
   * of the call to _quote_identifier
   *
   * If column_name is an associative array, it will add a condition for each column
   *
   * @param string       $type
   * @param string|array $column_name
   * @param string       $separator
   * @param string|int   $value
   *
   * @return $this
   */
  protected function _add_simple_condition($type, $column_name, $separator, $value)
  {
    if (is_array($column_name)) {
      $multiple = $column_name;
    } else {
      $multiple = array($column_name => $value);
    }

    $result = $this;

    foreach ($multiple as $key => $val) {
      // Add the table name in case of ambiguous columns
      if (count($result->_join_sources) > 0 && strpos($key, '.') === false) {

        $table = $result->_table_name;
        if (null !== $result->_table_alias) {
          $table = $result->_table_alias;
        }

        $key = "{$table}.{$key}";
      }
      $key = $result->_quote_identifier($key);
      $result = $result->_add_condition($type, "{$key} {$separator} ?", $val);
    }

    return $result;
  }

  /**
   * Add a WHERE clause width DATE
   *
   * If column_name is an associative array, it will add a condition for each column
   *
   * @param string       $type
   * @param string|array $column_name
   * @param string       $separator
   * @param string|int   $value
   *
   * @return $this
   */
  protected function _add_date_condition($type, $column_name, $separator, $value)
  {
    $multiple = is_array($column_name) ? $column_name : array($column_name => $value);
    $result = $this;

    foreach ($multiple as $key => $val) {
      // Add the table name in case of ambiguous columns
      if (count($result->_join_sources) > 0 && strpos($key, '.') === false) {

        $table = $result->_table_name;
        if (!is_null($result->_table_alias)) {
          $table = $result->_table_alias;
        }

        $key = "{$table}.{$key}";
      }
      $key = 'DATE(' . $result->_quote_identifier($key) . ')';
      $result = $result->_add_condition($type, "{$key} {$separator} ?", $val);
    }

    return $result;
  }

  /**
   * Return a string containing the given number of question marks,
   * separated by commas. Eg "?, ?, ?"
   *
   * @param mixed $fields
   *
   * @return string
   */
  protected function _create_placeholders($fields)
  {
    if (!empty($fields)) {
      $db_fields = array();
      foreach ($fields as $key => $value) {
        // Process expression fields directly into the query
        if (array_key_exists($key, $this->_expr_fields)) {
          $db_fields[] = $value;
        } else {
          $db_fields[] = '?';
        }
      }

      return implode(', ', $db_fields);
    } else {
      return '';
    }
  }

  /**
   * Helper method that filters a column/value array returning only those
   * columns that belong to a compound primary key.
   *
   * If the key contains a column that does not exist in the given array,
   * a null value will be returned for it.
   *
   * @param mixed $value
   *
   * @return array
   */
  protected function _get_compound_id_column_values($value)
  {
    $filtered = array();
    foreach ($this->_get_id_column_name() as $key) {
      $filtered[$key] = isset($value[$key]) ? $value[$key] : null;
    }

    return $filtered;
  }

  /**
   * Helper method that filters an array containing compound column/value
   * arrays.
   *
   * @param array $values
   *
   * @return array
   */
  protected function _get_compound_id_column_values_array($values)
  {
    $filtered = array();
    foreach ($values as $value) {
      $filtered[] = $this->_get_compound_id_column_values($value);
    }

    return $filtered;
  }

  /**
   * Add a WHERE column = value clause to your query. Each time
   * this is called in the chain, an additional WHERE will be
   * added, and these will be ANDed together when the final query
   * is built.
   *
   * If you use an array in $column_name, a new clause will be
   * added for each element. In this case, $value is ignored.
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where($column_name, $value = null)
  {
    return $this->where_equal($column_name, $value);
  }

  /**
   * More explicitly named version of for the where() method.
   * Can be used if preferred.
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_equal($column_name, $value = null)
  {
    return $this->_add_simple_where($column_name, '=', $value);
  }

  /**
   * Add a WHERE column != value clause to your query.
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_not_equal($column_name, $value = null)
  {
    return $this->_add_simple_where($column_name, '!=', $value);
  }

  /**
   * Special method to query the table by its primary key
   *
   * If primary key is compound, only the columns that
   * belong to they key will be used for the query
   *
   * @param mixed $id
   *
   * @return $this
   */
  public function where_id_is($id)
  {
    if (is_array($this->_get_id_column_name())) {
      return $this->where($this->_get_compound_id_column_values($id), null);
    } else {
      return $this->where($this->_get_id_column_name(), $id);
    }
  }

  /**
   * Allows adding a WHERE clause that matches any of the conditions
   * specified in the array. Each element in the associative array will
   * be a different condition, where the key will be the column name.
   *
   * By default, an equal operator will be used against all columns, but
   * it can be overridden for any or every column using the second parameter.
   *
   * Each condition will be ORed together when added to the final query.
   *
   * @param array  $values
   * @param string $operator
   *
   * @return $this
   */
  public function where_any_is($values, $operator = '=')
  {
    $data = array();
    $query = array('((');
    $first = true;
    foreach ($values as $item) {
      if ($first) {
        $first = false;
      } else {
        $query[] = ') OR (';
      }
      $firstsub = true;

      foreach ($item as $key => $subItem) {

        if (is_string($operator)) {
          $op = $operator;
        } else {
          $op = (isset($operator[$key]) ? $operator[$key] : '=');
        }

        if ($firstsub) {
          $firstsub = false;
        } else {
          $query[] = 'AND';
        }

        $query[] = $this->_quote_identifier($key);
        $data[] = $subItem;
        $query[] = $op . ' ?';
      }
    }
    $query[] = '))';

    return $this->where_raw(implode($query, ' '), $data);
  }

  /**
   * Similar to where_id_is() but allowing multiple primary keys.
   *
   * If primary key is compound, only the columns that
   * belong to they key will be used for the query
   *
   * @param mixed $ids
   *
   * @return $this
   */
  public function where_id_in($ids)
  {
    if (is_array($this->_get_id_column_name())) {
      return $this->where_any_is($this->_get_compound_id_column_values_array($ids));
    } else {
      return $this->where_in($this->_get_id_column_name(), $ids);
    }
  }

  /**
   * Add a WHERE ... LIKE clause to your query.
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_like($column_name, $value = null)
  {
    return $this->_add_simple_where($column_name, 'LIKE', $value);
  }

  /**
   * Add where WHERE ... NOT LIKE clause to your query.
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_not_like($column_name, $value = null)
  {
    return $this->_add_simple_where($column_name, 'NOT LIKE', $value);
  }

  /**
   * Add a WHERE ... > clause to your query
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_gt($column_name, $value = null)
  {
    return $this->_add_simple_where($column_name, '>', $value);
  }

  /**
   * Add a WHERE ... < clause to your query
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_lt($column_name, $value = null)
  {
    return $this->_add_simple_where($column_name, '<', $value);
  }

  /**
   * Add a WHERE ... >= clause to your query
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_gte($column_name, $value = null)
  {
    return $this->_add_simple_where($column_name, '>=', $value);
  }

  /**
   * Add a WHERE ... <= clause to your query
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_lte($column_name, $value = null)
  {
    return $this->_add_simple_where($column_name, '<=', $value);
  }

  /**
   * Add a WHERE ... IN clause to your query
   *
   * @param string $column_name
   * @param mixed  $values
   *
   * @return $this
   */
  public function where_in($column_name, $values)
  {
    return $this->_add_where_placeholder($column_name, 'IN', $values);
  }

  /**
   * Add a WHERE ... NOT IN clause to your query
   *
   * @param string $column_name
   * @param mixed  $values
   *
   * @return $this
   */
  public function where_not_in($column_name, $values)
  {
    return $this->_add_where_placeholder($column_name, 'NOT IN', $values);
  }

  /**
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_date_eq($column_name, $value = null)
  {
    return $this->_add_date_condition('where', $column_name, '=', $value);
  }


  /**
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_date_lt($column_name, $value = null)
  {
    return $this->_add_date_condition('where', $column_name, '<', $value);
  }

  /**
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_date_gt($column_name, $value = null)
  {
    return $this->_add_date_condition('where', $column_name, '>', $value);
  }

  /**
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_date_le($column_name, $value = null)
  {
    return $this->_add_date_condition('where', $column_name, '<=', $value);
  }

  /**
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function where_date_ge($column_name, $value = null)
  {
    return $this->_add_date_condition('where', $column_name, '>=', $value);
  }

  /**
   * Add a WHERE column IS NULL clause to your query.
   *
   * @param string $column_name
   *
   * @return $this
   */
  public function where_null($column_name)
  {
    return $this->_add_where_no_value($column_name, 'IS NULL');
  }

  /**
   * Add a WHERE column IS NOT NULL clause to your query
   *
   * @param string $column_name
   *
   * @return $this
   */
  public function where_not_null($column_name)
  {
    return $this->_add_where_no_value($column_name, 'IS NOT NULL');
  }

  /**
   * Add a raw WHERE clause to the query. The clause should
   * contain question mark placeholders, which will be bound
   * to the parameters supplied in the second argument.
   *
   * @param string $clause
   * @param array  $parameters
   *
   * @return $this
   */
  public function where_raw($clause, $parameters = array())
  {
    return $this->_add_where($clause, $parameters);
  }

  /**
   * Add a LIMIT to the query
   *
   * @param int $limit
   *
   * @return $this
   */
  public function limit($limit)
  {
    $this->_limit = $limit;

    return $this;
  }

  /**
   * Add an OFFSET to the query
   *
   * @param $offset
   *
   * @return $this
   */
  public function offset($offset)
  {
    $this->_offset = $offset;

    return $this;
  }

  /**
   * Add an ORDER BY clause to the query
   *
   * @param string $column_name
   * @param string $ordering
   *
   * @return $this
   */
  protected function _add_order_by($column_name, $ordering)
  {
    $column_name = $this->_quote_identifier($column_name);
    $this->_order_by[] = "{$column_name} {$ordering}";

    return $this;
  }

  /**
   * Add an ORDER BY column DESC clause
   *
   * @param string $column_name
   *
   * @return $this
   */
  public function order_by_desc($column_name)
  {
    return $this->_add_order_by($column_name, 'DESC');
  }

  /**
   * Add an ORDER BY RAND clause
   */
  public function order_by_rand()
  {
    $this->_order_by[] = 'RAND()';

    return $this;
  }

  /**
   * Add an ORDER BY column ASC clause
   *
   * @param string $column_name
   *
   * @return $this
   */
  public function order_by_asc($column_name)
  {
    return $this->_add_order_by($column_name, 'ASC');
  }

  /**
   * Add an unquoted expression as an ORDER BY clause
   *
   * @param $clause
   *
   * @return $this
   */
  public function order_by_expr($clause)
  {
    $this->_order_by[] = $clause;

    return $this;
  }

  /**
   * Reset the ORDER BY clause
   */
  public function reset_order_by()
  {
    $this->_order_by = array();

    return $this;
  }

  /**
   * Add a column to the list of columns to GROUP BY
   *
   * @param string $column_name
   *
   * @return $this
   */
  public function group_by($column_name)
  {
    $column_name = $this->_quote_identifier($column_name);
    $this->_group_by[] = $column_name;

    return $this;
  }

  /**
   * Add an unquoted expression to the list of columns to GROUP BY
   *
   * @param string $expr
   *
   * @return $this
   */
  public function group_by_expr($expr)
  {
    $this->_group_by[] = $expr;

    return $this;
  }

  /**
   * Add a HAVING column = value clause to your query. Each time
   * this is called in the chain, an additional HAVING will be
   * added, and these will be ANDed together when the final query
   * is built.
   *
   * If you use an array in $column_name, a new clause will be
   * added for each element. In this case, $value is ignored.
   *
   * @param string $column_name
   * @param null   $value
   *
   * @return $this
   */
  public function having($column_name, $value = null)
  {
    return $this->having_equal($column_name, $value);
  }

  /**
   * More explicitly named version of for the having() method.
   * Can be used if preferred.
   *
   * @param string $column_name
   * @param null   $value
   *
   * @return $this
   */
  public function having_equal($column_name, $value = null)
  {
    return $this->_add_simple_having($column_name, '=', $value);
  }

  /**
   * Add a HAVING column != value clause to your query.
   *
   * @param string $column_name
   * @param null   $value
   *
   * @return $this
   */
  public function having_not_equal($column_name, $value = null)
  {
    return $this->_add_simple_having($column_name, '!=', $value);
  }

  /**
   * Special method to query the table by its primary key.
   *
   * If primary key is compound, only the columns that
   * belong to they key will be used for the query
   *
   * @param $id
   *
   * @return $this
   */
  public function having_id_is($id)
  {
    if (is_array($this->_get_id_column_name())) {
      return $this->having($this->_get_compound_id_column_values($id));
    } else {
      return $this->having($this->_get_id_column_name(), $id);
    }
  }

  /**
   * Add a HAVING ... LIKE clause to your query.
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function having_like($column_name, $value = null)
  {
    return $this->_add_simple_having($column_name, 'LIKE', $value);
  }

  /**
   * Add where HAVING ... NOT LIKE clause to your query.
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function having_not_like($column_name, $value = null)
  {
    return $this->_add_simple_having($column_name, 'NOT LIKE', $value);
  }

  /**
   * Add a HAVING ... > clause to your query
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function having_gt($column_name, $value = null)
  {
    return $this->_add_simple_having($column_name, '>', $value);
  }

  /**
   * Add a HAVING ... < clause to your query
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function having_lt($column_name, $value = null)
  {
    return $this->_add_simple_having($column_name, '<', $value);
  }

  /**
   * Add a HAVING ... >= clause to your query
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function having_gte($column_name, $value = null)
  {
    return $this->_add_simple_having($column_name, '>=', $value);
  }

  /**
   * Add a HAVING ... <= clause to your query
   *
   * @param string $column_name
   * @param mixed  $value
   *
   * @return $this
   */
  public function having_lte($column_name, $value = null)
  {
    return $this->_add_simple_having($column_name, '<=', $value);
  }

  /**
   * Add a HAVING ... IN clause to your query
   *
   * @param string $column_name
   * @param mixed  $values
   *
   * @return $this
   */
  public function having_in($column_name, $values = null)
  {
    return $this->_add_having_placeholder($column_name, 'IN', $values);
  }

  /**
   * Add a HAVING ... NOT IN clause to your query
   *
   * @param string $column_name
   * @param mixed  $values
   *
   * @return $this
   */
  public function having_not_in($column_name, $values = null)
  {
    return $this->_add_having_placeholder($column_name, 'NOT IN', $values);
  }

  /**
   * Add a HAVING column IS NULL clause to your query
   *
   * @param string $column_name
   *
   * @return $this
   */
  public function having_null($column_name)
  {
    return $this->_add_having_no_value($column_name, 'IS NULL');
  }

  /**
   * Add a HAVING column IS NOT NULL clause to your query
   *
   * @param string $column_name
   *
   * @return $this
   */
  public function having_not_null($column_name)
  {
    return $this->_add_having_no_value($column_name, 'IS NOT NULL');
  }

  /**
   * Add a raw HAVING clause to the query. The clause should
   * contain question mark placeholders, which will be bound
   * to the parameters supplied in the second argument.
   *
   * @param string $clause
   * @param array  $parameters
   *
   * @return $this
   */
  public function having_raw($clause, $parameters = array())
  {
    return $this->_add_having($clause, $parameters);
  }

  /**
   * Activate cache refreshing for current query
   *
   * @return $this
   */
  public function refreshCache()
  {
    $this->_refresh_cache = true;

    return $this;
  }

  /**
   * Disable caching for current query
   *
   * @return $this
   */
  public function noCaching()
  {
    $this->_no_caching = true;

    return $this;
  }

  /**
   * Build a SELECT statement based on the clauses that have
   * been passed to this instance by chaining method calls.
   */
  protected function _build_select()
  {
    // If the query is raw, just set the $this->_values to be
    // the raw query parameters and return the raw query
    if ($this->_is_raw_query) {
      $this->_values = $this->_raw_parameters;

      return $this->_raw_query;
    }

    // Build and return the full SELECT statement by concatenating
    // the results of calling each separate builder method.
    return $this->_join_if_not_empty(
        ' ',
        array(
            $this->_build_select_start(),
            $this->_build_join(),
            $this->_build_where(),
            $this->_build_group_by(),
            $this->_build_having(),
            $this->_build_order_by(),
            $this->_build_limit(),
            $this->_build_offset(),
        )
    );
  }

  /**
   * Build the start of the SELECT statement
   *
   * @return string
   */
  protected function _build_select_start()
  {
    $fragment = 'SELECT ';
    $result_columns = implode(', ', $this->_result_columns);

    if (
        null !== $this->_limit
        &&
        static::$_config[$this->_connection_name]['limit_clause_style'] === self::LIMIT_STYLE_TOP_N
    ) {
      $fragment .= "TOP {$this->_limit} ";
    }

    if ($this->_distinct) {
      $result_columns = 'DISTINCT ' . $result_columns;
    }

    $fragment .= "{$result_columns} FROM " . $this->_quote_identifier($this->_table_name);

    if (null !== $this->_table_alias) {
      $fragment .= ' ' . $this->_quote_identifier($this->_table_alias);
    }

    return $fragment;
  }

  /**
   * Build the JOIN sources
   *
   * @return string
   */
  protected function _build_join()
  {
    if (count($this->_join_sources) === 0) {
      return '';
    }

    return implode(' ', $this->_join_sources);
  }

  /**
   * Build the WHERE clause(s)
   *
   * @return string
   */
  protected function _build_where()
  {
    return $this->_build_conditions('where');
  }

  /**
   * Build the HAVING clause(s)
   *
   * @return string
   */
  protected function _build_having()
  {
    return $this->_build_conditions('having');
  }

  /**
   * Build GROUP BY
   *
   * @return string
   */
  protected function _build_group_by()
  {
    if (count($this->_group_by) === 0) {
      return '';
    }

    return 'GROUP BY ' . implode(', ', $this->_group_by);
  }

  /**
   * Build a WHERE or HAVING clause
   *
   * @param string $type
   *
   * @return string
   */
  protected function _build_conditions($type)
  {
    $conditions_class_property_name = "_{$type}_conditions";
    // If there are no clauses, return empty string
    if (count($this->$conditions_class_property_name) === 0) {
      return '';
    }

    $conditions = array();
    foreach ($this->$conditions_class_property_name as $condition) {
      $conditions[] = $condition[static::CONDITION_FRAGMENT];
      /** @noinspection SlowArrayOperationsInLoopInspection */
      $this->_values = array_merge($this->_values, $condition[static::CONDITION_VALUES]);
    }

    return strtoupper($type) . ' ' . implode(' AND ', $conditions);
  }

  /**
   * Build ORDER BY
   *
   * @return string
   */
  protected function _build_order_by()
  {
    if (count($this->_order_by) === 0) {
      return '';
    }

    $db = static::get_db($this->_connection_name);

    return 'ORDER BY ' . trim($db->quote(implode(', ', $this->_order_by)), "'");
  }

  /**
   * Build LIMIT
   *
   * @return string
   */
  protected function _build_limit()
  {
    // init
    $fragment = '';

    if (
        null !== $this->_limit
        &&
        static::$_config[$this->_connection_name]['limit_clause_style'] == self::LIMIT_STYLE_LIMIT
    ) {
      if (static::get_db($this->_connection_name)->getAttribute(\PDO::ATTR_DRIVER_NAME) == 'firebird') {
        $fragment = 'ROWS';
      } else {
        $fragment = 'LIMIT';
      }

      $this->_limit = (int)$this->_limit;

      $fragment .= " {$this->_limit}";
    }

    return $fragment;
  }

  /**
   * Build OFFSET
   *
   * @return string
   */
  protected function _build_offset()
  {
    if (null !== $this->_offset) {
      $clause = 'OFFSET';
      if (static::get_db($this->_connection_name)->getAttribute(\PDO::ATTR_DRIVER_NAME) == 'firebird') {
        $clause = 'TO';
      }

      $this->_offset = (int)$this->_offset;

      return "$clause " . $this->_offset;
    }

    return '';
  }

  /**
   * Wrapper around PHP's join function which
   * only adds the pieces if they are not empty.
   *
   * @param string $glue
   * @param array  $pieces
   *
   * @return string
   */
  protected function _join_if_not_empty($glue, $pieces)
  {
    $filtered_pieces = array();
    foreach ($pieces as $piece) {
      if (is_string($piece)) {
        $piece = trim($piece);
      }
      if (!empty($piece)) {
        $filtered_pieces[] = $piece;
      }
    }

    return implode($glue, $filtered_pieces);
  }

  /**
   * Quote a string that is used as an identifier
   * (table names, column names etc). This method can
   * also deal with dot-separated identifiers eg table.column
   *
   * @param string $identifier
   *
   * @return string
   */
  protected function _quote_one_identifier($identifier)
  {
    $parts = explode('.', $identifier);
    $parts = array_map(array($this, '_quote_identifier_part'), $parts);

    return implode('.', $parts);
  }

  /**
   * Quote a string that is used as an identifier
   * (table names, column names etc) or an array containing
   * multiple identifiers. This method can also deal with
   * dot-separated identifiers eg table.column
   *
   * @param array|string $identifier
   *
   * @return string
   */
  protected function _quote_identifier($identifier)
  {
    if (is_array($identifier)) {
      $result = array_map(array($this, '_quote_one_identifier'), $identifier);

      return implode(', ', $result);
    } else {
      return $this->_quote_one_identifier($identifier);
    }
  }

  /**
   * This method performs the actual quoting of a single
   * part of an identifier, using the identifier quote
   * character specified in the config (or autodetected).
   *
   * @param string $part
   *
   * @return string
   */
  protected function _quote_identifier_part($part)
  {
    if ($part === '*') {
      return $part;
    }

    $quote_character = static::$_config[$this->_connection_name]['identifier_quote_character'];

    // double up any identifier quotes to escape them
    return $quote_character . str_replace($quote_character, $quote_character . $quote_character, $part) . $quote_character;
  }

  /**
   * Create a cache key for the given query and parameters.
   *
   * @param string      $query
   * @param array       $parameters
   * @param null|string $table_name
   * @param string      $connection_name
   *
   * @return mixed|string
   */
  protected static function _create_cache_key($query, $parameters, $table_name = null, $connection_name = self::DEFAULT_CONNECTION)
  {
    if (
        isset(static::$_config[$connection_name]['create_cache_key']) === true
        &&
        is_callable(static::$_config[$connection_name]['create_cache_key']) === true
    ) {
      return call_user_func_array(
          static::$_config[$connection_name]['create_cache_key'],
          array(
              $query,
              $parameters,
              $table_name,
              $connection_name,
          )
      );
    }
    $parameter_string = implode(',', $parameters);
    $key = $query . ':' . $parameter_string;

    return sha1($key);
  }

  /**
   * Check the query cache for the given cache key. If a value
   * is cached for the key, return the value. Otherwise, return false.
   *
   * @param string      $cache_key
   * @param null|string $table_name
   * @param string      $connection_name
   *
   * @return bool|mixed
   */
  protected static function _check_query_cache($cache_key, $table_name = null, $connection_name = self::DEFAULT_CONNECTION)
  {
    if (
        isset(static::$_config[$connection_name]['check_query_cache']) === true
        &&
        is_callable(static::$_config[$connection_name]['check_query_cache']) === true
    ) {
      return call_user_func_array(
          static::$_config[$connection_name]['check_query_cache'],
          array(
              $cache_key,
              $table_name,
              $connection_name,
          )
      );
    } elseif (isset(static::$_query_cache[$connection_name][$cache_key])) {
      return static::$_query_cache[$connection_name][$cache_key];
    }

    return false;
  }

  /**
   * Clear the query cache
   *
   * @param null|string $table_name
   * @param string      $connection_name
   *
   * @return bool|mixed
   */
  public static function clear_cache($table_name = null, $connection_name = self::DEFAULT_CONNECTION)
  {
    // init
    static::$_query_cache = array();

    if (
        isset(static::$_config[$connection_name]['clear_cache']) === true
        &&
        is_callable(static::$_config[$connection_name]['clear_cache']) === true
    ) {
      return call_user_func_array(
          static::$_config[$connection_name]['clear_cache'],
          array(
              $table_name,
              $connection_name,
          )
      );
    }

    return false;
  }

  /**
   * Add the given value to the query cache.
   *
   * @param string      $cache_key
   * @param string      $value
   * @param null|string $table_name
   * @param string      $connection_name
   *
   * @return bool|mixed
   */
  protected static function _cache_query_result($cache_key, $value, $table_name = null, $connection_name = self::DEFAULT_CONNECTION)
  {
    if (
        isset(static::$_config[$connection_name]['cache_query_result']) === true
        &&
        is_callable(static::$_config[$connection_name]['cache_query_result']) === true
    ) {

      return call_user_func_array(
          static::$_config[$connection_name]['cache_query_result'],
          array(
              $cache_key,
              $value,
              $table_name,
              $connection_name,
          )
      );

    } elseif (!isset(static::$_query_cache[$connection_name])) {
      static::$_query_cache[$connection_name] = array();
    }

    static::$_query_cache[$connection_name][$cache_key] = $value;

    return true;
  }

  /**
   * Execute the SELECT query that has been built up by chaining methods
   * on this class. Return an array of rows as associative arrays.
   */
  protected function _run()
  {
    // init
    $cache_key = false;

    $query = $this->_build_select();
    $caching_enabled = static::$_config[$this->_connection_name]['caching'];

    if ($caching_enabled && !$this->_no_caching) {
      $cache_key = static::_create_cache_key($query, $this->_values, $this->_table_name, $this->_connection_name);

      if (!$this->_refresh_cache) {
        $cached_result = static::_check_query_cache($cache_key, $this->_table_name, $this->_connection_name);

        if ($cached_result !== false) {
          return $cached_result;
        }
      }
    }

    static::_execute($query, $this->_values, $this->_connection_name);
    $statement = static::get_last_statement();

    $rows = array();
    /** @noinspection PhpAssignmentInConditionInspection */
    while ($row = $statement->fetch(\PDO::FETCH_ASSOC)) {
      $rows[] = $row;
    }

    if ($cache_key) {
      static::_cache_query_result($cache_key, $rows, $this->_table_name, $this->_connection_name);
    }

    // reset Idiorm after executing the query
    $this->_values = array();
    $this->_result_columns = array('*');
    $this->_using_default_result_columns = true;

    return $rows;
  }

  /**
   * Return the raw data wrapped by this ORM
   * instance as an associative array. Column
   * names may optionally be supplied as arguments,
   * if so, only those keys will be returned.
   */
  public function as_array()
  {
    if (func_num_args() === 0) {
      return $this->_data;
    }
    $args = func_get_args();

    return array_intersect_key($this->_data, array_flip($args));
  }

  /**
   * Return the raw data wrapped by this ORM
   * instance as an json.
   *
   * @param int $options
   *
   * @return string
   */
  public function as_json($options = 0)
  {
    return json_encode($this->as_array(), $options);
  }

  /**
   * Return the value of a property of this object (database row)
   * or null if not present.
   *
   * If a column-names array is passed, it will return a associative array
   * with the value of each column or null if it is not present.
   *
   * @param mixed $key
   *
   * @return mixed
   */
  public function get($key)
  {
    if (is_array($key)) {
      $result = array();
      foreach ($key as $column) {
        $result[$column] = isset($this->_data[$column]) ? $this->_data[$column] : null;
      }

      return $result;
    } else {
      return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }
  }

  /**
   * Return the name of the column in the database table which contains
   * the primary key ID of the row.
   */
  protected function _get_id_column_name()
  {
    if (null !== $this->_instance_id_column) {
      return $this->_instance_id_column;
    }

    if (isset(static::$_config[$this->_connection_name]['id_column_overrides'][$this->_table_name])) {
      return static::$_config[$this->_connection_name]['id_column_overrides'][$this->_table_name];
    }

    return static::$_config[$this->_connection_name]['id_column'];
  }

  /**
   * Get the primary key ID of this object.
   *
   * @param bool $disallow_null
   *
   * @return mixed
   *
   * @throws \Exception
   */
  public function id($disallow_null = false)
  {
    $id = $this->get($this->_get_id_column_name());

    if ($disallow_null) {
      if (is_array($id)) {
        foreach ($id as $id_part) {
          if ($id_part === null) {
            throw new \Exception('Primary key ID contains null value(s)');
          }
        }
      } elseif ($id === null) {
        throw new \Exception('Primary key ID missing from row or is null');
      }
    }

    return $id;
  }

  /**
   * Set a property to a particular value on this object.
   * To set multiple properties at once, pass an associative array
   * as the first parameter and leave out the second parameter.
   * Flags the properties as 'dirty' so they will be saved to the
   * database when save() is called.
   *
   * @param mixed $key
   * @param mixed $value
   *
   * @return $this
   */
  public function set($key, $value = null)
  {
    return $this->_set_orm_property($key, $value);
  }

  /**
   * Set a property to a particular value on this object.
   * To set multiple properties at once, pass an associative array
   * as the first parameter and leave out the second parameter.
   * Flags the properties as 'dirty' so they will be saved to the
   * database when save() is called.
   *
   * @param string|array $key
   * @param string|null  $value
   *
   * @return $this
   */
  public function set_expr($key, $value = null)
  {
    return $this->_set_orm_property($key, $value, true);
  }

  /**
   * Set a property on the ORM object.
   *
   * @param  string|array $key
   * @param string|null   $value
   * @param bool          $expr
   *
   * @return $this
   */
  protected function _set_orm_property($key, $value = null, $expr = false)
  {
    if (!is_array($key)) {
      $key = array($key => $value);
    }

    /** @noinspection SuspiciousLoopInspection */
    foreach ($key as $field => $value) {
      $this->_data[$field] = $value;
      $this->_dirty_fields[$field] = $value;
      if (false === $expr && isset($this->_expr_fields[$field])) {
        unset($this->_expr_fields[$field]);
      } elseif (true === $expr) {
        $this->_expr_fields[$field] = true;
      }
    }

    return $this;
  }

  /**
   * Check whether the given field has been changed since this
   * object was saved.
   *
   * @param string $key
   *
   * @return bool
   */
  public function is_dirty($key)
  {
    return array_key_exists($key, $this->_dirty_fields);
  }

  /**
   * Check whether the model was the result of a call to create() or not
   *
   * @return bool
   */
  public function is_new()
  {
    return $this->_is_new;
  }

  /**
   * Save any fields which have been modified on this object
   * to the database.
   *
   * @return bool
   *
   * @throws \Exception
   */
  public function save()
  {
    // remove any expression fields as they are already baked into the query
    $values = array_values(array_diff_key($this->_dirty_fields, $this->_expr_fields));

    if (!$this->_is_new) {

      // UPDATE

      // If there are no dirty values, do nothing
      if (
          empty($values)
          &&
          0 === count($this->_expr_fields)
      ) {
        return true;
      }

      $query = $this->_build_update();
      $id = $this->id(true);

      if (is_array($id)) {
        $values = array_merge($values, array_values($id));
      } else {
        $values[] = $id;
      }

    } else {

      // INSERT
      $query = $this->_build_insert();
    }

    $success = static::_execute($query, $values, $this->_connection_name);
    $caching_auto_clear_enabled = static::$_config[$this->_connection_name]['caching_auto_clear'];

    if ($caching_auto_clear_enabled) {
      static::clear_cache($this->_table_name, $this->_connection_name);
    }

    // If we've just inserted a new record, set the ID of this object
    if ($success && $this->_is_new) {

      $this->_is_new = false;
      if ($this->count_null_id_columns() != 0) {
        $db = static::get_db($this->_connection_name);

        if ($db->getAttribute(\PDO::ATTR_DRIVER_NAME) == 'pgsql') {

          // it may return several columns if a compound primary
          // key is used
          $row = static::get_last_statement()->fetch(\PDO::FETCH_ASSOC);
          foreach ($row as $key => $value) {
            $this->_data[$key] = $value;
          }

        } else {
          $column = $this->_get_id_column_name();
          // if the primary key is compound, assign the last inserted id
          // to the first column
          if (is_array($column)) {
            $column = reset($column);
          }
          $this->_data[$column] = $db->lastInsertId();
        }
      }
    }

    $this->_dirty_fields = $this->_expr_fields = array();

    return $success;
  }

  /**
   * Add a WHERE clause for every column that belongs to the primary key
   *
   * @param array $query warning: this is a reference
   */
  public function _add_id_column_conditions(&$query)
  {
    $query[] = 'WHERE';

    if (is_array($this->_get_id_column_name())) {
      $keys = $this->_get_id_column_name();
    } else {
      $keys = array($this->_get_id_column_name());
    }

    $first = true;
    foreach ($keys as $key) {

      if ($first) {
        $first = false;
      } else {
        $query[] = 'AND';
      }

      $query[] = $this->_quote_identifier($key);
      $query[] = '= ?';
    }
  }

  /**
   * Build an UPDATE query
   *
   * @return string
   */
  protected function _build_update()
  {
    $query = array();
    $query[] = "UPDATE {$this->_quote_identifier($this->_table_name)} SET";

    $field_list = array();
    foreach ($this->_dirty_fields as $key => $value) {

      if (!array_key_exists($key, $this->_expr_fields)) {
        $value = '?';
      }

      $field_list[] = "{$this->_quote_identifier($key)} = $value";
    }

    $query[] = implode(', ', $field_list);
    $this->_add_id_column_conditions($query);

    return implode(' ', $query);
  }

  /**
   * Build an INSERT query
   *
   * @return string
   */
  protected function _build_insert()
  {
    $query[] = 'INSERT INTO';
    $query[] = $this->_quote_identifier($this->_table_name);
    $field_list = array_map(array($this, '_quote_identifier'), array_keys($this->_dirty_fields));
    $query[] = '(' . implode(', ', $field_list) . ')';
    $query[] = 'VALUES';

    $placeholders = $this->_create_placeholders($this->_dirty_fields);
    $query[] = "({$placeholders})";

    if (static::get_db($this->_connection_name)->getAttribute(\PDO::ATTR_DRIVER_NAME) == 'pgsql') {
      $query[] = 'RETURNING ' . $this->_quote_identifier($this->_get_id_column_name());
    }

    return implode(' ', $query);
  }

  /**
   * Delete this record from the database
   *
   * @return bool
   */
  public function delete()
  {
    $query = array(
        'DELETE FROM',
        $this->_quote_identifier($this->_table_name),
    );
    $this->_add_id_column_conditions($query);

    return static::_execute(
        implode(' ', $query), is_array($this->id(true)) ?
        array_values($this->id(true)) :
        array($this->id(true)), $this->_connection_name
    );
  }

  /**
   * Delete many records from the database
   *
   * @return bool
   */
  public function delete_many()
  {
    // Build and return the full DELETE statement by concatenating
    // the results of calling each separate builder method.
    $query = $this->_join_if_not_empty(
        ' ',
        array(
            'DELETE FROM',
            $this->_quote_identifier($this->_table_name),
            $this->_build_where(),
        )
    );

    return static::_execute($query, $this->_values, $this->_connection_name);
  }

  // --------------------- //
  // ---  ArrayAccess  --- //
  // --------------------- //

  /**
   * @param mixed $key
   *
   * @return bool
   */
  public function offsetExists($key)
  {
    return array_key_exists($key, $this->_data);
  }

  /**
   * @param mixed $key
   *
   * @return mixed
   */
  public function offsetGet($key)
  {
    return $this->get($key);
  }

  /**
   * @param mixed $key
   * @param mixed $value
   */
  public function offsetSet($key, $value)
  {
    if (null === $key) {
      throw new \InvalidArgumentException('You must specify a key/array index.');
    }
    $this->set($key, $value);
  }

  /**
   * @param mixed $key
   */
  public function offsetUnset($key)
  {
    unset($this->_data[$key]);
    unset($this->_dirty_fields[$key]);
  }

  // --------------------- //
  // --- MAGIC METHODS --- //
  // --------------------- //

  /**
   * @param $key
   *
   * @return mixed
   */
  public function __get($key)
  {
    return $this->offsetGet($key);
  }

  /**
   * @param $key
   * @param $value
   */
  public function __set($key, $value)
  {
    $this->offsetSet($key, $value);
  }

  /**
   * @param $key
   */
  public function __unset($key)
  {
    $this->offsetUnset($key);
  }

  /**
   * @param $key
   *
   * @return bool
   */
  public function __isset($key)
  {
    return $this->offsetExists($key);
  }

  /**
   * Magic method to capture calls to undefined class methods.
   * In this case we are attempting to convert camel case formatted
   * methods into underscore formatted methods.
   *
   * This allows us to call ORM methods using camel case and remain
   * backwards compatible.
   *
   * @param  string $name
   * @param  array  $arguments
   *
   * @return $this
   *
   * @throws IdiormMethodMissingException
   */
  public function __call($name, $arguments)
  {
    $method = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));

    if (method_exists($this, $method)) {
      return call_user_func_array(array($this, $method), $arguments);
    } else {
      throw new IdiormMethodMissingException("Method $name() does not exist in class " . get_class($this));
    }
  }

  /**
   * Magic method to capture calls to undefined static class methods.
   * In this case we are attempting to convert camel case formatted
   * methods into underscore formatted methods.
   *
   * This allows us to call ORM methods using camel case and remain
   * backwards compatible.
   *
   * @param  string $name
   * @param  array  $arguments
   *
   * @return $this
   */
  public static function __callStatic($name, $arguments)
  {
    $method = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));

    return call_user_func_array(array('idiorm\orm\ORM', $method), $arguments);
  }

}
