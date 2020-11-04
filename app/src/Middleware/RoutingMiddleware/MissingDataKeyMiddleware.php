<?php

namespace Acme\Middleware\RoutingMiddleware;

use Acme\Exception\Validation\MissingDataKeyException;

class MissingDataKeyMiddleware
{
    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */

    public function __invoke($request, $response, $next)
    {
        $data = $request->getParsedBody();
        $path  = $request->getUri()->getPath();
        $path_fmt = explode('lead/', $path)[1];

        if ($data) {

            if ($data['contact']) {

                if ($path_fmt === 'request') {
                    $response = $next($request, $response);
                    return $response;
                }

                if ($data['evaluate']) {

                    $response = $next($request, $response);
                    return $response;
                } else {
                    throw new MissingDataKeyException('Evaluate');
                    return $response->withStatus(400);
                }
            } else {
                throw new MissingDataKeyException('Contact');
                return $response->withStatus(400);
            }
        } else {
            return $response->withStatus(400);
        }
    }
}
