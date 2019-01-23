<?php
/*
 * Microsoft SQL Server Driver for PHP
*/
class SQLSRV{
	
	private $settings = array(
		'Host'						=>	'HOSTNAME',
		'Database'					=>	'DATABASE',
		'UID'						=>	'USERNAME',
		'PWD'						=>	'PASSWORD',
		"CharacterSet"				=>	"UTF-8",
		"ConnectionPooling"			=>	"1",
		"MultipleActiveResultSets"	=>	'0'
	);
	
	private $connection = NULL;
	private $debbug = true;
	private $stmt = false;
	private $transaction = false;
	private $num_rows = false;
	
	// Connect to database
	function __construct($setting = array()){
		
		$this->settings = array_merge($this->settings, $setting);
		
		$host = $this->settings['Host'];
		unset($this->settings['Host']);
		
		if( $this->connection = sqlsrv_connect($host, $this->settings) ) {
			$this->settings = NULL;
		}else{
			echo "Connection could not be established.<br />";
			$this->error();
		}
	}
	
	// Prepares and executes a query
	public function query($sql_query=false, $conn=false, $params=array(), $options=array()){
		if($sql_query===false) return $this->stmt;
		if(!$conn) $conn = $this->connection;
		$this->stmt = sqlsrv_query($conn, $sql_query, $params, $options);
		if($this->debbug) $this->check_stmt();
		$this->num_rows = sqlsrv_num_rows($this->stmt);
		return $this;
	}
	
	// Prepares a query for execution
	public function prepare($sql_query=false, $params=array(), $conn=false){
		if($sql_query===false) return $this->stmt;
		if(!$conn) $conn = $this->connection;
		$this->stmt = sqlsrv_prepare($conn, $sql_query, $params);
		if($this->debbug) $this->check_stmt();
		$this->num_rows = sqlsrv_num_rows($this->stmt);
		return $this;
	}
	
	// Executes a statement prepared with $this->prepare()
	public function execute(){
		if($this->debbug && sqlsrv_execute( $this->stmt ) === false)
		{	
			$this->error();
		}
		return $this;
	}
	
	// Returns the number of rows modified by the last INSERT, UPDATE, or DELETE query executed
	public function rows_affected(){
		$rows_affected = sqlsrv_rows_affected($this->stmt);
		if($rows_affected === false && $this->debbug) {
			 $this->error();
		}
		return $rows_affected;
	}
	
	// Retrieves metadata for the fields
	public function field_metadata(){				
		$array = array();
		$sqlsrv_field_metadata = sqlsrv_field_metadata( $this->stmt );
		foreach($sqlsrv_field_metadata as $row => $fieldMetadata ) {
			$array[]=$fieldMetadata["Name"];
		}
		
		return $array;
	}
	
	// Makes the next row in a result set available for reading
	public function fetch(){
		if(sqlsrv_fetch( $this->stmt )  === false && $this->debbug) {
			 $this->error();
		}
		
		return $this;
	}
	
	// Gets field data from the currently selected row
	public function get_field($index, $getAsType=NULL){
		return sqlsrv_get_field( $this->stmt, $index, $getAsType );
	}
	
	// Returns a rows as an array
	public function fetch_array(){
		$array = array();
		while( $row = sqlsrv_fetch_array($this->stmt, SQLSRV_FETCH_ASSOC) ) {
			$array[]=$row;
		}
		return $array;
	}
	
	// Retrieves the rows of data in a result set as an object
	public function fetch_object(){
		$array = array();
		while( $row = sqlsrv_fetch_object($this->stmt) ) {
			$array[]=$row;
		}
		return $array;
	}
	
	// Retrieves the number of rows in a result set
	public function num_rows(){
		return $this->num_rows;
	}
	
	// Begins a database transaction
	public function begin_transaction($conn=false){
		if(!$conn) $conn = $this->connection;
		
		if($this->debbug)
		{
			$this->transaction = false;
			if ( sqlsrv_begin_transaction( $conn ) === false ) {
				 $this->error();
			}
			$this->transaction = true;
		}
		else
		{
			$this->transaction = sqlsrv_begin_transaction( $conn );
		}
		return $this;
	}
	
	// Commits a transaction that was begun with $this->begin_transaction()
	public function commit($conn=false){
		if(!$conn) $conn = $this->connection;
		$this->transaction = sqlsrv_commit( $conn );
		return $this;
	}
	
	// Rolls back a transaction that was begun with $this->begin_transaction()
	public function rollback($conn=false){
		if(!$conn) $conn = $this->connection;
		$this->transaction = sqlsrv_rollback( $conn );
		return $this;
	}
	
	// Return transaction status true/false
	public function transaction(){
		return $this->transaction;
	}
	
	// Sends data from parameter streams to the server
	public function send_stream_data(){
		$i = 1;
		while(sqlsrv_send_stream_data( $this->stmt )) ++$i;
		return $i;
	}
	
	// Returns error and warning information about the last SQLSRV operation performed
	public function error(){
		die( '<pre>' . print_r( sqlsrv_errors(), true) . '</pre>' );
	}
	
	// Check resources for the specified statement
	public function check_stmt(){
		if($this->stmt === false) {
			$this->error();
		}
		return $this;
	}
	
	// Get resources for the specified statement
	public function get_stmt(){
		return $this->stmt;
	}
	
	// Frees all resources for the specified statement
	public function free_stmt(){
		return sqlsrv_free_stmt( $this->stmt );
	}
	
	// Disable instance
	public function disable_debbug(){
		$this->debbug = false;
		return $this;
	}
	
	// Cancels a statement
	public function cancel(){
		sqlsrv_cancel( $this->stmt );
		return $this;
	}
	
	// Returns information about the client and specified connection
	public function client_info(){
		return sqlsrv_client_info( $this->connection );
	}
	
	// Returns information about the server
	public function server_info(){
		return sqlsrv_server_info( $this->connection );
	}
	
	// Closes an open connection and releases resourses associated with the connection
	public function close($conn=false){
		if($conn) return sqlsrv_close( $conn );
		
		$conn = $this->connection;
		$this->stmt = false;
		$this->settings = NULL;
		$this->transaction = false;
		$this->debbug = true;
		return sqlsrv_close( $conn );
	}
	
	// Creset all on script end
	function __destruct(){
		$this->stmt = false;
		$this->settings = NULL;
		$this->transaction = false;
		$this->debbug = true;
		return $this->close( $this->connection );
	}
}