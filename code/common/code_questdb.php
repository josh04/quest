<?php

/**
 * Allows the system to connect to and interact with the database
 *
 * The aim of this package is to make coding easier and allow
 * queries to be entered with ease and less ssecurity worries.
 * The code is based primarily of ADODB, and acts a a light-
 * weight version specifically designed for PHP. It's important
 * to appreciate that a lot of this code is based off, or the
 * same as, their's and they deserve all the credit. Their
 * website is here: http://adodb.sourceforge.net
 * Hmm
 * The main functions of QuestDB are Execute and AutoExecute,
 * though there are several others available, as made clear below.
 * 
 * @package QuestDB
 * @author Grego
 */

class QuestDB {

    public $isConnected = false;
    public $_lastQuery = '';
    public $_queryCount = 0;
    public $_Queries = array();
    public $_showErrors = true;
    public $_lastError = '';
    public $_output_buffer = '';
    public $_capturedErrors = array();
    public $replaceQuote = "\\'";
    public $nameQuote = '`';
    private $_con;
    private $_queryID;
    private $config = array();
    public $_langError = array
    (
        1 => 'Necessary arguments not sent',
        2 => 'Error establishing mySQL database connection',
        3 => 'mySQL database connection is not active',
        4 => 'An unexpected error occured',
        5 => 'Argument mismatch error',
        6 => 'Update without a WHERE parameter',
        7 => 'Invalid record set provided',
        8 => 'Invalid error message',
    );
    
   /**
    * constructor; sets defines for langError
    * 
    */
    public function __construct() {
        if (ARGUMENT_NOT_SENT != 1) {
            define('ARGUMENT_NOT_SENT', 1);
        }
        if (ERROR_CONNECTING != 2) {
            define('ERROR_CONNECTING', 2);
        }
        if (CONNECTION_NOT_ACTIVE != 3) {
            define('CONNECTION_NOT_ACTIVE', 3);
        }
        if (UNEXPECTED_ERROR != 4) {
            define('UNEXPECTED_ERROR', 4);
        }
        if (ARGUMENT_MISMATCH_ERROR != 5) {
            define('ARGUMENT_MISMATCH_ERROR', 5);
        }
        if (NO_WHERE_PARAMETER != 6) {
            define('NO_WHERE_PARAMETER', 6);
        }
        if (INVALID_RECORD_SET != 7) {
            define('INVALID_RECORD_SET', 7);
        }
        if (INVALID_ERROR_MESSAGE != 8) {
            define('INVALID_ERROR_MESSAGE', 8);
        }
    }

   /**
    * Connect to the database
    * 
    * @param string $server the server where the database is hosted
    * @param string $username username to access database
    * @param string $password password to access database
    * @param string $database the name of the database to connect to
    * @return boolean whether a connection was successful
    */
    public function Connect($server = 'localhost', $username = '', $password = '', $database = '') {
        $this->_con = mysqli_init();
        if ($ret = @mysqli_real_connect($this->_con, $server, $username, $password)) {
            
            $this->config = array(
                'username' => $username,
                'password' => $password,
                'server'   => $server
            );

            if ($database!="") {
                $this->SwitchDatabase($database);
            }
            $this->isConnected = true;
        } else {
            $this->CatchError($this->_langError[ERROR_CONNECTING].' in '.__FILE__.' on line '.__LINE__);
        }
        return $ret;
    }

   /**
    * Switch database
    * 
    * @param string $database database to switch to
    * @return boolean whether the database was successfully switched
    */
    public function SwitchDatabase($database = '') {
        $ret = false;
            if (!$database) {
                $this->CatchError($this->_langError[ARGUMENT_NOT_SENT].' in '.__FILE__.' on line '.__LINE__);
            } else if (!$this->_con) {
                $this->CatchError($this->_langError[CONNECTION_NOT_ACTIVE].' in '.__FILE__.' on line '.__LINE__);
            } else if (!@mysqli_select_db($this->_con, $database)) {
                $this->CatchError($this->_langError[UNEXPECTED_ERROR].' in '.__FILE__.' on line '.__LINE__);
            } else {
                $this->database = $database;
                $ret = true;
            }
        return $ret;
    }

   /**
    * Add an error to the log and output it if allowed
    * (TODO) Allow $error to be integer to refer to array or string
    * 
    * @param mixed $error the error text, or the id of the error in _langError
    * @param string $module the module in which the error occured. Can be a function or anything else you like
    * @param boolean $silent if set to true, doesn't output the error
    */
    public function CatchError($error, $module='') {
        if(is_numeric($error)) $error = $this->_langError[$error];
        // For irony's sake, CatchError can call an error in itself
        if(!is_string($error)) {
            $error = $this->_langError[INVALID_ERROR_MESSAGE];
            $module = "CatchError";
            }
        $this->_lastError = $error;
        $this->_capturedErrors[] = array(
                'error'     => $error,
                'module'    => $module,
                'lastQuery' => $this->_lastQuery
            );
        if ($this->_showErrors) {
            echo "<strong>QuestDB error".($module!=''?' ('.$module.')':'').":</strong> ".$error;
        }
    }

   /**
    * Retrieve the last error caught
    * 
    * @return string the error text
    */
    public function LastError() {
        if($this->_lastError!='') return $this->_lastError;
        return false;
    }

   /**
    * Synonym for LastError()
    * 
    * @return string the error text
    */
    public function ErrorMsg() {
        return $this->lastError();
    }
    
    // Only works for INSERT, UPDATE and DELETE query's
    function Affected_Rows() {
      $result =  @mysqli_affected_rows($this->_con);
      return $result;
    }

   /**
    * Synonym for execute
    * 
    * @param string $sql the SQL to be executed
    * @param array $fields an array of values to replace erotemes
    * @return mixed a record set if applicable or a success boolean
    */
    public function Query($sql, $fields = false) {
        return $this->execute($sql, $fields);
    }

   /**
    * Perform an SQL query, substituting in variables if provided
    * 
    * @param string $sql the SQL to be executed
    * @param array $fields an array of values to replace erotemes
    * @return QuestDBrs a record set if applicable or a success boolean
    */
    public function execute($sql, $fields = false) {
        if (is_array($fields[0])) {
            foreach ($fields as $fields_temp) {
                $ret = $this->execute($sql, $fields_temp);
                if (!$ret) {
                    break;
                }
            }
            return $ret;
        }
        if ($fields) {
            if(!is_array($fields)) $fields = array($fields);
            $sqlbits = explode("?",$sql);
            $param_count = sizeof($sqlbits)-1;
            $sql = ''; $i = 0;
            while(list(, $v) = each($fields)) {
                $sql .= $sqlbits[$i];
                $type = gettype($v);
                
                if($type=='string')
                    $sql .= $this->qstr($v);
                else if($type=='boolean')
                    $sql .= $v ? '1' : '0';
                else if($type=='double')
                    $sql .= str_replace("," , "." , $v);
                else if ($type == 'object') {
                    if (method_exists($v, '__toString')) {
                        $sql .= $this->qstr($v->__toString());
                    } else {
                        $sql .= $this->qstr((string) $v);
                    }
                } else if($v === null)
                    $sql .= '';
                else
                    $sql .= $v;
                $i++;

                if ($i == $param_count) break;
            }

            if (isset($sqlbits[$i])) {
                $sql .= $sqlbits[$i];
                if ($i+1 != sizeof($sqlbits)) {
                    $this->CatchError($this->_langError[ARGUMENT_MISMATCH_ERROR]); return false;
                }
            } else if ($i != sizeof($sqlbits)) {
                $this->CatchError( $this->_langError[ARGUMENT_MISMATCH_ERROR]); return false;
            }

            $ret = $this->_execute($sql, $fields);
        } else {
            $ret = $this->_execute($sql, false);
        }
        return $ret;
    }

   /**
    * Straight out perform a raw SQL query; no funny business
    * 
    * @param string $sql the SQL to be executed
    * @param array $fields an array of values that were introduced in execute()
    * @return QuestDBrs a record set if applicable or a success boolean
    */
    private function _execute($sql, $fields) {
        $sql .= "; ";
        mysqli_multi_query($this->_con, $sql);
        $this->_queryID = mysqli_store_result($this->_con);
        $this->_lastQuery = $sql;
        $this->_lastError = mysqli_error($this->_con);
        if ($this->_queryID === 1) {
            return true;
        }

        $this->Flush();
        $this->_lastQuery = $sql;
        $this->_Queries[] = $sql;
        $this->_queryCount++;

        $rs = new QuestDBrs($this->_queryID, $this->_lastQuery);
        return $rs;
    }

   /**
    * Return the rows affected by the last query
    * 
    * @return integer the number of rows affected by the last query
    */
    public function affectedRows() {
        return @mysqli_affected_rows($this->_con);
    }

   /**
    * Return the ID of the last inserted row
    * 
    * @return integer the ID of the last inserted row
    */
    public function insertID() {
        $id = mysqli_insert_id($this->_con);
        return $id;
    }

   /**
    * Synonym for insertID()
    * 
    * @return integer the ID of the last inserted row
    */
    public function Insert_Id() {
        return $this->insertID();
    }

   /**
    * Quote a string for execution
    * 
    * @param string $s the string needing quotes
    * @param string $magic_quotes if the string has come from REQUEST,
    *                     set this parameter to get_magic_quotes_gpc()
    * @return string the quoted string
    */
    public function qstr($s, $magic_quotes = false) {
        if (!$magic_quotes) {

            return "'".mysqli_real_escape_string($this->_con, $s)."'";
            //if ($this->replaceQuote[0] == '\\'){
                // only since php 4.0.5
            //    $s = str_replace(array('\\',"\0"),array('\\\\',"\\\0"),$s);
                //$s = str_replace("\0","\\\0", str_replace('\\','\\\\',$s));
            //}
            //return  "'".str_replace("'",$this->replaceQuote,$s)."'";
        }

        // undo magic quotes for "
        $s = str_replace('\\"','"',$s);

        if ($this->replaceQuote == "\\'")  // ' already quoted, no need to change anything
            return "'$s'";
        else {// change \' to '' for sybase/mssql
            $s = str_replace('\\\\','\\',$s);
            return "'".str_replace("\\'",$this->replaceQuote,$s)."'";
        }
    }

   /**
    * Automatically perform an insert or update query with the given data
    * 
    * @param string $table the table to insert into or update
    * @param string $fields an array where the keys are field names and values are the values to act with
    * @param string $mode 'INSERT' or 'UPDATE'
    * @param string $where the where to specify which record to update
    * @return boolean whether the action was a success
    */
    public function AutoExecute($table, $fields, $mode, $where = false) {

        $sql = 'SELECT * FROM '.$table;  
        if ($where!==FALSE) $sql .= ' WHERE '.$where;
        else if ($mode == 'UPDATE' || $mode == 'UPDATE') {
            $this->CatchError(6);
            return false;
        }

        $rs = $this->Execute($sql,false);
        if(!$rs) return false;

        switch((string) $mode) {
            case 'UPDATE':
                $sql = $this->GetUpdateSQL($rs, $fields);
                break;
            case 'INSERT':
                $sql = $this->GetInsertSQL($rs, $fields);
                break;
            default:
                $this->CatchError("Unknown mode=$mode",'AutoExecute');
                return false;
        }

        $ret = false;
        if ($sql) $ret = $this->Execute($sql);
        if ($ret) $ret = true;
        return $ret;
    }

   /**
    * Return a single row, as an array, from a query. If there are multiple rows, the first is returned.
    * 
    * @param string $sql the SQL to get the row from
    * @param string $fields an array of variables to replace erotemes
    * @return mixed an array of values on success or FALSE on failure
    */
    public function GetOne($sql, $fields = false) {
        $ret = false;
        $rs = $this->execute($sql, $fields);
        
        if($rs) {
            if ($rs->EOF) $ret = false;
            else $ret = reset($rs->fields);
            $rs->Close();
        }
        return $ret;
    }

   /**
    * Returns an SQL statement updating the fields $arrFields in the table
    * contained in $rs
    * 
    * @param object $rs a QuestDBrs object made of the relevant SELECT query
    * @param array $arrFields a set of name-value pairs containing field names and values
    * @param boolean $forceUpdate if set, will update fields even if the new value is the same as the current
    * @return string an UPDATE SQL statement
    */
    private function getUpdateSQL($rs, $arrFields, $forceUpdate = false) {

        if (!$rs) {
            $this->CatchError(7,"getUpdateSQL");
            return false;
        }

        $fieldUpdatedCount = 0;
        $arrFields = array_change_key_case($arrFields, CASE_UPPER);

        $hasnumeric = isset($rs->fields[0]);
        $setFields = '';

        // A big loop going through each field in the record set
        // and adding to update query if it has changed
        for ($i=0, $max=$rs->FieldCount(); $i < $max; $i++) {
            // Get the field from the recordset
            $field = $rs->FetchField($i);

            // If the recordset field is one
            // of the fields passed in then process.
            $upperfname = strtoupper($field->name);
            if (array_key_exists($upperfname, $arrFields)) {

                // If the existing field value in the recordset
                // is different from the value passed in then
                // go ahead and append the field name and new value to
                // the update query.

                if ($hasnumeric) $val = $rs->fields[$i];
                else if (isset($rs->fields[$upperfname])) $val = $rs->fields[$upperfname];
                else if (isset($rs->fields[$field->name])) $val =  $rs->fields[$field->name];
                else if (isset($rs->fields[strtolower($upperfname)])) $val =  $rs->fields[strtolower($upperfname)];
                else $val = '';

                    if ($forceUpdate || strcmp($val, $arrFields[$upperfname])) {
                    // Set the counter for the number of fields that will be updated.
                    $fieldUpdatedCount++;

                    if ((strpos($upperfname,' ') !== false))
                        $fnameq = $this->nameQuote.$upperfname.$this->nameQuote;
                    else
                        $fnameq = $upperfname;


                        // is_null requires php 4.0.4
                        if (is_null($arrFields[$upperfname])
                             || (empty($arrFields[$upperfname]) && strlen($arrFields[$upperfname]) == 0)
                             || $arrFields[$upperfname] === $this->null2null
                            )
                        {
                            //Set the value that was given in array, so you can give both null and empty values
                            if (is_null($arrFields[$upperfname]) || $arrFields[$upperfname] === $this->null2null) {
                                $setFields .= $this->nameQuote.$field->name.$this->nameQuote . " = null, ";
                            } else {
                               $setFields .= $this->column_sql('U', $upperfname, $fnameq, $arrFields);
                            }
                        } else {
                        // A little thing to make cleaner SQL
                        $setFields .= $this->column_sql('U', $upperfname, $fnameq, $arrFields);
                        }
                }
            }
        } //for


        // If there were any modified fields then build the rest of the update query.
        if ($fieldUpdatedCount > 0 || $forceUpdate) {
            // Get the table name from the existing query.
            if (!empty($rs->tableName)) $tableName = $rs->tableName;
            else {
                preg_match("/FROM\s+".'([]0-9a-z_\:\"\`\.\@\[-]*)'."/is", $rs->sql, $tableName);
                $tableName = $tableName[1];
            }
            // Get the full where clause excluding the word "WHERE" from
            // the existing query.
            preg_match('/\sWHERE\s(.*)/is', $rs->sql, $whereClause);

            $discard = false;
            // not a good hack, improvements?
            if ($whereClause) {
                if (preg_match('/\s(ORDER\s.*)/is', $whereClause[1], $discard));
                else if (preg_match('/\s(LIMIT\s.*)/is', $whereClause[1], $discard));
                else if (preg_match('/\s(FOR UPDATE.*)/is', $whereClause[1], $discard));
                else preg_match('/\s.*(\) WHERE .*)/is', $whereClause[1], $discard); # see http://sourceforge.net/tracker/index.php?func=detail&aid=1379638&group_id=42718&atid=433976
            } else
                $whereClause = array(false,false);

        if ($discard)
            $whereClause[1] = substr($whereClause[1], 0, strlen($whereClause[1]) - strlen($discard[1]));

        $sql = 'UPDATE '.$tableName.' SET '.substr($setFields, 0, -2);
        if (strlen($whereClause[1]) > 0) 
            $sql .= ' WHERE '.$whereClause[1];
            return $sql;

        } else {
            return false;
        }
    }

   /**
    * Returns an INSERT query inserting the fields $arrFields into the table
    * that $rs is based upon
    * 
    * @param object $rs a QuestDBrs object made of the relevant SELECT query
    * @param array $arrFields a set of name-value pairs containing field names and values
    * @return string an INSERT SQL statement
    */
    private function getInsertSQL($rs, $arrFields) {
        $tableName = '';
        $values = '';
        $fields = '';
        $recordSet = null;
        $arrFields = array_change_key_case($arrFields, CASE_UPPER);
        $fieldInsertedCount = 0;

        if (is_string($rs)) {
            // ok we have a table name
            // try and get the column info ourself.
            $tableName = $rs;
            // rather than using fancy any functions, let's just do it like before
            $rs = $this->Execute("SELECT * FROM ".$tableName, false);
            if(!$rs) return false;
        }
        
        if (get_class($rs) == 'QuestDBrs') {
            for ($i=0, $max=$rs->FieldCount(); $i < $max; $i++) 
                $columns[] = $rs->FetchField($i);
            $recordSet = $rs;

        } else {
            $this->CatchError(7, "getInsertSQL");
            return false;
        }

        // Loop through all of the fields in the recordset
        foreach( $columns as $field ) { 
            $upperfname = strtoupper($field->name);
            if (array_key_exists($upperfname, $arrFields)) {
            $bad = false;
                if ((strpos($upperfname,' ') !== false) || ($ADODB_QUOTE_FIELDNAMES) || 1)
                    $fnameq = $this->nameQuote.$upperfname.$this->nameQuote;
                else
                    $fnameq = $upperfname;

                /********************************************************/
                if (is_null($arrFields[$upperfname])
                 || (empty($arrFields[$upperfname]) && strlen($arrFields[$upperfname]) == 0)
                ) {
                    //Set the value that was given in array, so you can give both null and empty values
                    if (is_null($arrFields[$upperfname]) || $arrFields[$upperfname] === $this->null2null) { 
                        $values  .= "null, ";
                    } else {
                        $values .= $this->column_sql('I', $upperfname, $fnameq, $arrFields);
                    }
            /*********************************************************/
                } else {
                    // A little thing to make cleaner SQL
                    $values .= $this->column_sql('I', $upperfname, $fnameq, $arrFields);
                }

                if ($bad) continue;
                // How many fields are we inserting?
                $fieldInsertedCount++;


                // Get the name of the fields to insert
                $fields .= $fnameq . ", ";

                }

        }

        // If there were any inserted fields then build the rest of the insert query.
        if ($fieldInsertedCount <= 0)  return false;

        // Get the table name from the existing query.
        if (!$tableName) {
            if (!empty($rs->tableName)) $tableName = $rs->tableName;
            else if (preg_match("/FROM\s+".'([]0-9a-z_\:\"\`\.\@\[-]*)'."/is", $rs->sql, $tableName))
                $tableName = $tableName[1];
            else 
                return false;
        }

        // Strip off the comma and space on the end of both the fields
        // and their values.
        $fields = substr($fields, 0, -2);
        $values = substr($values, 0, -2);

        // Append the fields and their values to the insert query.
        return 'INSERT INTO '.$tableName.' ( '.$fields.' ) VALUES ( '.$values.' )';
    }

   /**
    * Clear the last query, so there's no later confusion
    * 
    */
    private function Flush() {
        $this->_lastQuery = null;
    }

   /**
    * Allow QuestDB to display errors
    * 
    * @param boolean $show whether or not to display errors
    */
    public function ShowErrors($show = true) {
        $this->_showErrors = $show;
    }

   /**
    * Stop QuestDB from displaying errors
    * 
    */
    public function HideErrors() {
        $this->ShowErrors(false);
    }

   /**
    * Appends a field to an INSERT/UPDATE list, applying vague formatting
    * 
    * @param string $action either "I" for an INSERT query or "U" for UPDATE
    * @param string $name the name of the field we want to add
    * @param string $fnameq the name of the field, as it appears in the query
    * @param array $fields the field set of the table we're changing
    * @return string the text to append to the SQL statement
    */
    public function column_sql($action, $fname, $fnameq, $fields)  {
        $v = $fields[$fname];
        $type = gettype($v);
        
        if($type=='string')
            $val .= $this->qstr($v);
        else if($type=='boolean')
            $val .= $v ? '1' : '0';
        else if($type=='double')
            $val = str_replace("," , "." , $v);
        else if ($typ == 'object') {
            if (method_exists($v, '__toString')) $val = $this->qstr($v->__toString());
            else $val = $this->qstr((string) $v);
        } else if($v === null)
            $val = '';
        else
            $val = $v;

        if ($action == 'I') return $val . ", ";
        return $this->nameQuote.$fnameq.$this->nameQuote . "=" . $val  . ", ";
    }

   /**
    * Check if the database is connected
    * 
    * @return boolean if the database is connected or not
    */
    public function isConnected() {
        return $this->isConnected;
    }

   /**
    * Close the database connection
    * 
    * @return boolean whether the connection could be closed
    */
    public function Close() {
        if(mysqli_close($this->_con)) {
            $this->_con = false;
            $this->isConnected = false;
            return true;
        } else {
            $this->CatchError("Could not close connection to database");
            return false;
        }
    }

} // End QuestDB

/*******************************************************************/

class QuestDBrs {

    var $sql;
    var $result;
    var $_array = array();
    var $fields = array();
    var $RecordCount = 0;
    var $FieldCount = 0;
    var $currentRow = 0;

   /**
    * Construct the record set properly
    * 
    * @param mysql_link $result the relevant record set
    * @param string $sql the SQL query used to generate the record set
    */
    public function __construct(&$result, $sql) {
        $this->sql = $sql;
        $this->result =& $result;
        
        if ($result) {

            $this->FieldCount = mysqli_num_fields($this->result);
            
            while ($a = mysqli_fetch_array($this->result)) {
                $this->RecordCount++;
                $this->_array[] = $a;
            }

            if (!$this->RecordCount) {
                $this->EOF = true;
            }
        }

        $this->currentRow = 0;
        $this->_fetch();
    }

   /**
    * Simple functions to return some stored variables
    * 
    * @return integer the field count, record count or current row depending on which function you call
    */
    public function NumFields() { return $this->FieldCount; }
    public function FieldCount() { return $this->FieldCount; }
    public function NumRows() { return $this->RecordCount; }
    public function RecordCount() { return $this->RecordCount; }
    public function CurrentRow() {return $this->_currentRow;}

   /**
    * Return the record set as an 2D array, with rows containing a series of elements
    * 
    * @return array the record set of the object in array form
    */
    public function GetArray() {
        $this->_array;
    }

   /**
    * Retrieve the next row from the record set
    * 
    * @return mixed either an array of the data row pulled next from the table or FALSE
    */
    public function FetchRow() {
        $ret = $this->fields;
        $this->currentRow++;
        $this->_fetch();
        return $ret;
    }

   /**
    * Retrieve data about a specific field (column) in the record set
    * 
    * @param integer $column the field offset (starts at 0)
    * @return object the data gathered
    */
    public function FetchField( $column ) {
        return mysqli_fetch_field_direct($this->result, $column);
    }

   /**
    * Retrieves the next row of data for FetchRow() (if it exists)
    * 
    * @return boolean whether or not the data could be fetched
    */
    private function _fetch() {
        if ($this->RecordCount <= $this->currentRow) {
            $this->fields = false;
            return false;
        }
        
        $this->fields = $this->_array[$this->currentRow];
        return true;
    }

   /**
    * Jump to a specific row in the record set
    * 
    * @param integer $row the row to move to
    * @return boolean whether or not the move action could be completed
    */
    public function Move($row = 0) {
        if($row == $this->currentRow) return true;
        if($row > $this->RecordCount) return false;
        if($row < 0) return false;

        $this->currentRow = $row;
        $this->_fetch();
        return true;
    }

   /**
    * Close the record set object; fairly nominal but points for effort
    * 
    * @return boolean whether or not the object could be closed
    */
    public function Close() {
        $this->result = null;
        return true;
    }

} // End QuestDBrs


   /**
    * Make me a database object!
    * 
    * Admittedly, this isn't a particularly necessary function. But
    * it looks nicer than the using "New QuestDB", which isn't as
    * nice syntax as other parts of the PHP code...
    * 
    * @return object the QuestDB object
    */
    function NewQuestDB() {
        return New QuestDB;
    }

?>