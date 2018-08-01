<?php
class DB
{
    # @object, The PDO object
    private static $pdo;

    # @object, PDO statement object
    private static $sQuery;

    # @array,  The database settings
    private static $settings;

    # @bool ,  Connected to the database
    private static $bConnected = false;

    # @array, The parameters of the SQL query
    private static $parameters;

	public static $query_list;
	public static $db_prefix;
	public static $db_name;

    /**
     *   Default Constructor
     *
     *	1. Instantiate Log class.
     *	2. Connect to database.
     *	3. Creates the parameter array.
     */
    public function __construct()
    {
        //self::Connect();
		//self::$parameters = array();
    }

	static function work()
    {
        DB::Connect();
		cache::connect();
		DB::$parameters = array();
    }

    /**
     *	This method makes connection to the database.
     *
     *	1. Reads the database settings from a ini file.
     *	2. Puts  the ini content into the settings array.
     *	3. Tries to connect to the database.
     *	4. If connection failed, exception is displayed and a log file gets created.
     */
    private static function Connect()
    {
		$app_database = config::app('app_database');
		$app_database_master = $app_database['master'];
		$config = $app_database[$app_database_master];

        self::$settings = $config;
		self::$db_prefix	= $config['dbpref'];
		self::$db_name	= $config['dbname'];

        $dsn = 'mysql:dbname=' . self::$settings["dbname"] . ';host=' . self::$settings["dbhost"] . '';
        try {
            # Read settings from INI file, set UTF8
            self::$pdo = new PDO($dsn, self::$settings["dbuser"], self::$settings["dbpass"], array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
            ));

            # We can now log any exceptions on Fatal error.
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            # Disable emulation of prepared statements, use REAL prepared statements instead.
            self::$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			if (config::app('app_profiling')) {
            	self::$pdo->exec('SET SESSION query_cache_type = OFF, PROFILING = 1');
			}

            # Connection succeeded, set the boolean to true.
            self::$bConnected = true;
        }
        catch (PDOException $e) {
            # Write into log
            echo $e->getMessage();
            exit;
        }
    }
    /*
     *   You can use this little method if you want to close the PDO connection
     *
     */
    public static function CloseConnection()
    {
        # Set the PDO object to null to close the connection
        # http://www.php.net/manual/en/pdo.connections.php
        self::$pdo = null;
    }

    /**
     *	Every method which needs to execute a SQL query uses this method.
     *
     *	1. If not connected, connect to the database.
     *	2. Prepare Query.
     *	3. Parameterize Query.
     *	4. Execute Query.
     *	5. On exception : Write Exception into the log + SQL query.
     *	6. Reset the Parameters.
     */
    private static function Init($query, $parameters = "")
    {
        # Connect to database
        if (!self::$bConnected) {
            self::Connect();
        }
        try {

			$time_start = microtime();

            # Prepare query
            self::$sQuery = self::$pdo->prepare($query);

            # Add parameters to the parameter array
            self::bindMore($parameters);

            # Bind parameters
            if (!empty(self::$parameters)) {
                foreach (self::$parameters as $param => $value) {

                    $type = PDO::PARAM_STR;
                    switch ($value[1]) {
                        case is_int($value[1]):
                            $type = PDO::PARAM_INT;
                            break;
                        case is_bool($value[1]):
                            $type = PDO::PARAM_BOOL;
                            break;
                        case is_null($value[1]):
                            $type = PDO::PARAM_NULL;
                            break;
                    }
                    // Add type when binding the values to the column
                    self::$sQuery->bindValue($value[0], $value[1], $type);
                }
            }

			self::$sQuery->execute();
			$time_end = microtime();

			self::$query_list[] = ["query"=>$query, 'time_start'=>$time_start, 'time_end'=>$time_end];
        }
        catch (PDOException $e) {
            # Write into log and display Exception
            self::ExceptionLog($query);
        }

        # Reset the parameters
        self::$parameters = array();
    }

    /**
     *	@void
     *
     *	Add the parameter to the parameter array
     *	@param string $para
     *	@param string $value
     */
    public static function bind($para, $value)
    {
        self::$parameters[sizeof(self::$parameters)] = [":" . $para , $value];
    }
    /**
     *	@void
     *
     *	Add more parameters to the parameter array
     *	@param array $parray
     */
    public static function bindMore($parray)
    {
        if (empty(self::$parameters) && is_array($parray)) {
            $columns = array_keys($parray);
            foreach ($columns as $i => &$column) {
                self::bind($column, $parray[$column]);
            }
        }
    }

    /**
     *  Returns the last inserted id.
     *  @return string
     */
    public static function lastInsertId()
    {
        return self::$pdo->lastInsertId();
    }

    /**
     * Starts the transaction
     * @return boolean, true on success or false on failure
     */
    public static function beginTransaction()
    {
        return self::$pdo->beginTransaction();
    }

    /**
     *  Execute Transaction
     *  @return boolean, true on success or false on failure
     */
    public static function executeTransaction()
    {
        return self::$pdo->commit();
    }

    /**
     *  Rollback of Transaction
     *  @return boolean, true on success or false on failure
     */
    public static function rollBack()
    {
        return self::$pdo->rollBack();
    }


	public static function query($query, $TTL = null, $params = null, $fetchmode = PDO::FETCH_ASSOC)
	{
		$query = trim(str_replace("\r", " ", $query));

		$TTL = strtoupper(substr(trim($query), 0, 6)) == 'SELECT' ? $TTL : null;
		$result = cache::check(md5($query), $TTL);
		if($result === false) {
			try {
				self::Init($query, $params);

				$rawStatement = explode(" ", preg_replace("/\s+|\t+|\n+/", " ", $query));

				# Which SQL statement is used
				$statement = strtolower($rawStatement[0]);

				if ($statement === 'select' || $statement === 'show') {
					$result = self::$sQuery->fetchAll($fetchmode);
				} elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
					$result = self::$sQuery->rowCount();
				} else {
					$result = NULL;
				}

				cache::write(md5($query), $result, $TTL);
			} catch (Exception $e) {

			}
		}
		return $result;
	}
    public static function column($query, $TTL = null, $params = null)
    {
		$TTL = strtoupper(substr(trim($query), 0, 6)) == 'SELECT' ? $TTL : null;
		$result = cache::check(md5($query), $TTL);
		if($result === false) {
			try {
				self::Init($query, $params);
		        $Columns = self::$sQuery->fetchAll(PDO::FETCH_NUM);
		        $result = null;
		        foreach ($Columns as $cells) {
		            $result[] = $cells[0];
		        }

				cache::write(md5($query), $result, $TTL);
			} catch (Exception $e) {

			}
		}
		return $result;
    }
    public static function row($query, $TTL = null, $params = null, $fetchmode = PDO::FETCH_OBJ)
    {
		$TTL = strtoupper(substr(trim($query), 0, 6)) == 'SELECT' ? $TTL : null;
		$result = cache::check(md5($query), $TTL);
		if($result === false) {
			try {
				self::Init($query, $params);
	        	$result = self::$sQuery->fetch($fetchmode);
	        	self::$sQuery->closeCursor(); // Frees up the connection to the server so that other SQL statements may be issued,

				cache::write(md5($query), $result, $TTL);
			} catch (Exception $e) {

			}
		}
        return $result;
    }
    public static function fetchrow($query, $TTL = null, $params = null, $fetchmode = PDO::FETCH_OBJ)
    {
		$TTL = strtoupper(substr(trim($query), 0, 6)) == 'SELECT' ? $TTL : null;
		$result = cache::check(md5($query), $TTL);
		if($result === false) {
			try {
				self::Init($query, $params);
				$result = self::$sQuery->fetchAll($fetchmode);
		        self::$sQuery->closeCursor(); // Frees up the connection to the server so that other SQL statements may be issued,

				cache::write(md5($query), $result, $TTL);
			} catch (Exception $e) {

			}
		}
        return $result;
    }
    public static function fetcharray($query, $TTL = null, $params = null, $fetchmode = PDO::FETCH_ASSOC)
    {
		$TTL = strtoupper(substr(trim($query), 0, 6)) == 'SELECT' ? $TTL : null;
		$result = cache::check(md5($query), $TTL);
		if($result === false) {
			try {
				self::Init($query, $params);
				$result = self::$sQuery->fetchAll($fetchmode);
		        self::$sQuery->closeCursor(); // Frees up the connection to the server so that other SQL statements may be issued,

				cache::write(md5($query), $result, $TTL);
			} catch (Exception $e) {

			}
		}
        return $result;
    }
	public static function numrows($query, $TTL = null, $params = null, $fetchmode = PDO::FETCH_OBJ)
    {
		$TTL = strtoupper(substr(trim($query), 0, 6)) == 'SELECT' ? $TTL : null;
		$result = cache::check(md5($query), $TTL);
		if($result === false) {
			try {
				self::Init($query, $params);
				$result = self::$sQuery->fetchAll($fetchmode);
		        self::$sQuery->closeCursor(); // Frees up the connection to the server so that other SQL statements may be issued,

				cache::write(md5($query), $result, $TTL);
			} catch (Exception $e) {

			}
		}
        return count($result);
    }
    public static function single($query, $TTL = null, $params = null)
    {
		$TTL = strtoupper(substr(trim($query), 0, 6)) == 'SELECT' ? $TTL : null;
		$result = cache::check(md5($query), $TTL);
		if($result === false) {
			try {
				self::Init($query, $params);
				$result = self::$sQuery->fetchColumn();
		        self::$sQuery->closeCursor(); // Frees up the connection to the server so that other SQL statements may be issued

				cache::write(md5($query), $result, $TTL);
			} catch (Exception $e) {

			}
		}
        return $result;
    }

    /**
     * Writes the log and returns the exception
     *
     * @param  string $query
     * @return string
     */
    public static function ExceptionLog($query = "")
    {
		$message = self::$pdo->errorInfo()[2];
		if($message!='') {
			$log = "
				sql_error: ".$message.",<br>
				sql_query: ".$query.",<br>
				url: ".HOST . '?' . $_SERVER['QUERY_STRING']."
			";
			logs::add($log, 0, 1);
		}
    }

	// query list
	public static function getQueries()
	{
		return self::$query_list;
	}

	public static function DBProfilesGet($type = '')
	{
		if (!config::app('app_profiling')) return false;

		$list = '<table class="table table-striped">';
        $time = 0;
		if(self::getQueries()) {
			foreach (self::getQueries() as $id=>$qe) {
				$time += microtime_diff($qe['time_start'], $qe['time_end']);

				$list .= '<tr>';
				$list .= '<td>'. ($id+1). '</td>';
				$list .= '<td>'. $qe['query']. '</td>';
				$list .= '<td>'. number_format(microtime_diff($qe['time_start'], $qe['time_end']), 6). '</td>';
				$list .= '</tr>';
			}
		}
		$list .= '</table>';

		$time = number_format($time * 1, 6, '.', '');
		$count = count(self::$query_list);

		switch ($type)
		{
			case 'list':  return $list;  break;
			case 'time':  return $time;  break;
			case 'count': return $count; break;
		}

		return false;
	}

	public static function get_statistic() {
		$stat = [];
		$stat['total_time'] = number_format(microtime_diff(START_MICROTIME, microtime()), 6, '.', ' ');
		$stat['memory'] = number_format(memory_get_peak_usage()/1024, 0, '.', ' ');
		$stat['count'] = self::DBProfilesGet('count');
		$stat['time'] = self::DBProfilesGet('time');
		$stat['average'] = ($stat['count'] > 0) ? number_format(($stat['time']/$stat['count'])* 1, 6, '.', '') : 0;
		$stat['status'] = self::$pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
		$stat['list'] = self::DBProfilesGet('list');
		$stat['php'] = phpversion();
		$stat['mysql'] = self::$pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

		twig::assign('stat', $stat);
		return twig::fetch('get_statistic.tpl');
	}

	public static function array2object($array) {
	    if (is_array($array)) {
	        $obj = new StdClass();

	        foreach ($array as $key => $val){
	            $obj->$key = $val;
	        }
	    }
	    else { $obj = $array; }
	    return $obj;
	}
}
?>
