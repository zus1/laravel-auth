<?php

namespace Zus1\LaravelAuth\Middleware;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CustomAuthorize
{
    private array $possibleRouteParameters;

    public function __construct()
    {
        $this->possibleRouteParameters = (array) config('laravel-auth.authorization.possible_route_parameters');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $policyAction = $this->routeToPolicyActionMapping($request->route()->action['as']);
        $routeParameterKeys = array_intersect(array_keys($request->route()->parameters()), $this->possibleRouteParameters);

        $subjects = $this->getAuthorizationSubject($request, $routeParameterKeys);

        if(is_string($subjects) === false) {
            $this->resolveAdditionalSubject(routeName: $request->route()->action['as'], subjects: $subjects);
        }

        Gate::authorize($policyAction, $subjects);

        return $next($request);
    }

    private function resolveAdditionalSubject(string $routeName, array &$subjects): void
    {
        $additionalSubjects = (array) config('laravel-auth.authorization.additional_subjects');

        if(!array_key_exists($routeName, $additionalSubjects)) {
            return;
        }

        is_array($additional = $additionalSubjects[$routeName]) ?
            $subjects = [$additional, ...$subjects] : array_unshift($subjects, $additional);
    }

    /**
     * @return string|Model[]
     */
    private function getAuthorizationSubject(Request $request, array $routeParameterKey): string|array
    {
        if($routeParameterKey === []) {
            $controllerPathParts = explode('\\', $request->route()->action['controller']);

            $class = sprintf('App\Models\%s', $controllerPathParts[3]);
            if(!file_exists(base_path(str_replace('\\', '/', lcfirst($class.'.php'))))) {
                $class = sprintf('%s\\%s', config('laravel-auth.user_namespace'), $controllerPathParts[3]);
            }

            return get_class(new $class);
        } else {
            $subjects = [];
            foreach ($routeParameterKey as $key) {
                $subjects[] = $request->route()->parameter($key);
            }

            return $subjects;
        }
    }

    private function routeToPolicyActionMapping(string $routeName): string
    {
        $mappings = (array) config('laravel-auth.authorization.mapping');

        return array_key_exists($routeName, $mappings) ? $mappings[$routeName] :
            throw new HttpException('Unknown route name '.$routeName);
    }
}
