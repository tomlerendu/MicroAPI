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
		$className = ltrim($className, '\\');
		$className = explode('\\', $className, 2);

		if(isset($this->prefixes[$className[0]]))
		{
			$className[1] = str_replace('\\', DIRECTORY_SEPARATOR, $className[1]);
			$classString = $this->prefixes[$className[0]] . DIRECTORY_SEPARATOR . $className[1] . '.php';

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