<?

/**
 * Autoloader
 *
 * @param $className - class name
 */
function __autoload( $className )
{
	// try to load the module in include_path folders.
	$className = str_replace(
		'_',
		DIRECTORY_SEPARATOR,
		strtok( $className, './\\:' ) );

	@ include_once( $className . '.php' );
}
