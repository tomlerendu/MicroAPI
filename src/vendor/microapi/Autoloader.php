<?php
namespace MicroAPI;

class Autoloader
{
	private $prefixes;

	public function __construct()
	{
		$this->prefixes = [];
		//Register this class as the autoloader
		spl_autoload_register([$this, 'getClass']);
	}

	public function getClass($className)
	{
		//Trim the left backslash
		$className = ltrim($className, '\\');
		//Split into prefix, path and class name
		$className = explode('\\', $className, 2);
		$prefix = $className[0];
		$path = strtolower(substr($className[1], 0, strrpos($className[1], '\\')));
		$name = strrchr($className[1], '\\');
		if($name === false) $name = $className[1];
		$name = ltrim($name, '\\');

		if(isset($this->prefixes[$prefix]))
		{
			$path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
			$classString = realpath($this->prefixes[$prefix] . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $name . '.php');

			return $this->requireClass($classString);
		}
		else
			return false;
	}

	public function requireClass($location)
	{
		if(file_exists($location))
		{
			require $location;
			return true;
		}
		else
			return false;
	}

	public function addNamespace($prefix, $directory)
	{
		$this->prefixes[$prefix] = $directory;
	}

	public function addNamespaces($namespaceArray)
	{
		foreach($namespaceArray as $prefix => $directory)
			$this->addNamespace($prefix, $directory);
	}
}