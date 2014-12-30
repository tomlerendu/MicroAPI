<?php
namespace MicroAPI;

class Autoloader
{
	private $prefixes = [];

    /**
     * Constructs a new instance of Autoloader which will register it's self as an autoloader
     */
    public function __construct()
	{
		//Register the class as an autoloader
		spl_autoload_register([$this, 'getClass']);
	}

    /**
     * Attempts to import a class using its fully qualified name
     *
     * @param $className - The class to be imported
     * @return bool - True if the class was successfully imported, false if not
     */
    public function getClass($className)
	{
		//Trim the left backslash
		$className = ltrim($className, '\\');
		//Split into prefix, path and class name
		$className = explode('\\', $className, 2);
		$prefix = $className[0];
		$path = strtolower(substr($className[1], 0, strrpos($className[1], '\\')));
		$name = strrchr($className[1], '\\');
		if ($name === false)
            $name = $className[1];
		$name = ltrim($name, '\\');

		if (isset($this->prefixes[$prefix])) {
			$path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
            $classLoc = $this->prefixes[$prefix] . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $name . '.php';
            $classLoc = realpath($classLoc);

			return $this->requireClass($classLoc);
		} else
			return false;
	}

    /**
     * Attempts to import a class from a given location on the file system
     *
     * @param $location - The location of the class on the file system
     * @return bool - True if the class was imported, false if not
     */
    private function requireClass($location)
	{
		if (file_exists($location)) {
			require $location;
			return true;
		}

	    return false;
	}

    /**
     * Adds a new namespace to the autoloader
     *
     * @param $prefix - The first part of a the fully qualified class name
     * @param $directory - The directory on the file system the prefix maps to
     */
    public function addNamespace($prefix, $directory)
	{
		$this->prefixes[$prefix] = $directory;
	}

    /**
     * Adds multiple namespaces to the autoloader
     *
     * @param $namespaceArray - An array of prefixes and directories to be loaded into the autoloader
     */
    public function addNamespaces($namespaceArray)
	{
		foreach ($namespaceArray as $prefix => $directory)
			$this->addNamespace($prefix, $directory);
	}
}