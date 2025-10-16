<?php

namespace App\Support;

use ReflectionClass, ReflectionMethod, ReflectionParameter, Route, Str;

final class AdvancedRoute
{
    private static array $httpMethods = ['any', 'get', 'post', 'put', 'patch', 'delete'];
    private static ?string $methodNameAtStartOfStringPattern = null;

    protected static function slug(ReflectionMethod $method): string
    {
        // Can't use str_replace to remove the httpMethod from the method name because this removes als strings wherever
        // they appear in the string, so for example, getCompanyName, becomes "compName" instead of companyName
        // because "any"
        // is removed from the word company in addition to the removal of "get" from the front of the string. Use a preg
        // to anchor the search to the front of the method name.
        if (!self::$methodNameAtStartOfStringPattern) {
            self::$methodNameAtStartOfStringPattern = '/^(' . implode('|', self::$httpMethods) . ')/';
        }

        $cleaned = preg_replace(self::$methodNameAtStartOfStringPattern, '', $method->name);
        $snaked  = Str::snake($cleaned, ' ');
        $slug    = Str::slug($snaked);

        if ($slug === 'index') {
            $slug = '';
        }

        foreach ($method->getParameters() as $parameter) {
            if (!self::isAllowedType($parameter)) {
                continue;
            }

            $slug .= sprintf(
                '/{%s%s}',
                strtolower($parameter->getName()),
                $parameter->isDefaultValueAvailable()
                    ? '?'
                    : ''
            );
        }

        if ($slug != null && $slug[0] == '/') {
            return substr($slug, 1);
        }

        return $slug;
    }

    protected static function isAllowedType(ReflectionParameter $param): bool
    {
        if (!$param->hasType()) {
            return true;
        }

        return in_array($param->getType()->__toString(), ['int', 'float', 'string', '?int', '?float', '?string']);
    }

    /**
     * @throws \ReflectionException
     */
    public static function controller(string $path, string $controllerClassName): void
    {
        if (class_exists($controllerClassName)) {
            $class = new ReflectionClass($controllerClassName);
        } else {
            $class = new ReflectionClass(app()->getNamespace() . 'Http\Controllers\\' . $controllerClassName);
        }

        $publicMethods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

        // The methods from each class will be in some random order, but mostly likely in the
        // order they were defined in the original file.  However, when issuing route commands we
        // need to ensure that more specific routes take precedences over ones with a parameter.
        // So for example if we have both the routes
        // Route::get('foo/{any}', 'FooController@anyIndex');
        // Route::get('foo/about', 'FooController@getAbout');
        // We need to ensure that the '/foo/about' route is issued first, or it will never be invoked
        // as the anyIndex will be called instead.
        $methods = [];
        foreach ($publicMethods as $reflectionMethod) {
            if ($reflectionMethod->name == 'getMiddleware') {
                continue;
            }

            $method        = new \stdClass();
            $method->name  = $reflectionMethod->name;
            $method->class = $reflectionMethod->class;
            $method->slug  = self::slug($reflectionMethod);
            $methods[]     = $method;
        }

        // Sort the routes so that the routes without any parameters, one with no {, come first.
        usort($methods, function ($a, $b) {
            $aHasParam = str_contains($a->slug, '{');
            $bHasParam = str_contains($b->slug, '{');

            if (!$aHasParam && $bHasParam) {
                return -1;
            }
            if (!$bHasParam && $aHasParam) {
                return 1;
            }
            return (strcmp($a->slug, $b->slug));
        });

        foreach ($methods as $method) {
            $slug       = $method->slug;
            $methodName = $method->name;
            $slug_path  = $path . '/' . $slug;

            foreach (self::$httpMethods as $httpMethod) {
                if (str_starts_with($methodName, $httpMethod)) {
                    Route::$httpMethod($slug_path, $controllerClassName . '@' . $methodName);
                }
            }
        }
    }

    /**
     * Ability to use several path-controller pairs
     *
     * Example:
     * [
     *     '/personal' => 'PersonalController',
     *     '/news'     => 'NewsController',
     *     ...
     * ]
     *
     * @param array $routes
     *
     * @throws \ReflectionException
     */
    public static function controllers(array $routes): void
    {
        foreach ($routes as $path => $controllerClassName) {
            AdvancedRoute::controller($path, $controllerClassName);
        }
    }
}
