<?php

declare(strict_types=1);

namespace Psi\Bundle\ObjectTemplate\Template;

/**
 * Determine a file path from a given class reflection class.
 *
 * An *ordered* map of namespaces => paths must be provided. It should be
 * ordered by namespace length (longest to shortest).
 */
class Locator
{
    private $extension;
    private $map;

    public function __construct(array $map, string $extension)
    {
        $this->extension = $extension;
        $this->map = $map;
    }

    public function locate(\ReflectionClass $class): string
    {
        list($resolvedNs, $basePath) = $this->resolvePath($class->getName());

        $fname = str_replace('\\', '.', substr($class->getName(), strlen($resolvedNs)));
        if (0 === strpos($fname, '.')) {
            $fname = substr($fname, 1);
        }

        $fpath = sprintf(
            '%s/%s.%s',
            $basePath,
            $fname,
            ($this->extension ? $this->extension : '')
        );

        return $fpath;
    }

    private function resolvePath($classFqn): array
    {
        $resolved = null;
        foreach ($this->map as $namespace => $path) {
            if ($namespace === $classFqn) {
                continue;
            }

            if (0 === strpos($classFqn, $namespace)) {
                $resolved = [$namespace, $path];
                break;
            }
        }

        if (null === $resolved) {
            throw new \InvalidArgumentException(sprintf(
                'Could not resolve path for class "%s" in namespaces: "%s"',
                $classFqn, implode('", "', array_keys($this->map))
            ));
        }

        return $resolved;
    }
}
