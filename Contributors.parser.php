<?php

/**
 * Class for the parser part of Contributors extension
 *
 * @file
 * @ingroup Extensions
 */
 
class ContributorsParser {

	/**
	 * @param $parser Parser
	 * @param $frame PPFrame
	 * @param $args array
	 * @return string
	*/

	public static function ifContribsIn ( $parser, $frame, $args ) {

		$output = 0;
		
		// By default, let's take current title
		$title = $parser->getTitle();

		if ( isset( $args[0] ) ) {
		
			$titleText = trim( $frame->expand( $args[0] ) );
			
			if ( !empty( $titleText ) ) {
				echo $titleText;
				$title = Title::newFromText( $titleText );
			}
			
		}
		
		if ( isset( $args[1] ) ) {
		
			$groupstr = trim( $frame->expand( $args[1] ) );
		
			if ( !empty( $groupstr ) ) { 
				
				// Array of groups
				$groups = preg_split ( "/\s*,\s*/" , $groupstr );
				
				$contributors = SpecialContributors::getContributors( $title );
				
				if ( self::checkContributorsGroups( $contributors, $groups ) ) {
					
					$output = 1;
				}
				
			}
			
		} 
		
		return $parser->insertStripItem( $output, $parser->mStripState );
	}
	
	/**
	 * @param $contributors array
	 * @param $groups array
	 * @return boolean
	*/
	
	public static function checkContributorsGroups( $contributors, $groups ) {
	
		foreach ( $contributors as $username => $info ) {
		
			$user = User::newFromName( $username );
			$roles = $user->getEffectiveGroups();
			
			foreach ( $roles as $role ) {

				if ( in_array( $role, $groups ) ){
					return true;
				}
			}
		}
	
		return false;
	}

}

