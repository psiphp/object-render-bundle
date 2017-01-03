<?php

declare(strict_types=1);

namespace Psi\Bundle\ObjectRender\Template;

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

    public function locate(\ReflectionClass $class, string $variant = null): string
    {
        list($resolvedNs, $basePath) = $this->resolvePath($class->getName());

        $fname = str_replace('\\', '.', substr($class->getName(), strlen($resolvedNs)));
        if (0 === strpos($fname, '.')) {
            $fname = substr($fname, 1);
        }

        $fpath = $this->getTemplatePath($basePath, $fname, $variant);

        return $fpath;
    }

    private function getTemplatePath(string $basePath, string $fname, string $variant = null)
    {
        $extension = ($this->extension ? $this->extension : '');

        if (null === $variant) {
            return sprintf(
                '%s/%s.%s',
                $basePath,
                $fname,
                $extension
            );
        }

        return sprintf(
            '%s/%s/%s.%s',
            $basePath,
            $fname,
            $variant,
            $extension
        );
    }

    private function resolvePath($classFqn): array
    {
        foreach ($this->map as $namespace => $path) {
            if ($namespace === $classFqn) {
                continue;
            }

            if (0 === strpos($classFqn, $namespace)) {
                return [$namespace, $path];
            }
        }

        throw new \InvalidArgumentException(sprintf(
            'Could not resolve path for class "%s" in namespaces: "%s"',
            $classFqn, implode('", "', array_keys($this->map))
        ));
    }
}
