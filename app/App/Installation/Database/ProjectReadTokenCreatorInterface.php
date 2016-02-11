<?php  namespace Logstats\App\Installation\Database; 

interface ProjectReadTokenCreatorInterface {

	/**
	 * Renames token column to write_token
	 * Adds read_token column to table
	 * Creates read tokens on created projects
	 */
	public function createReadTokens();
}