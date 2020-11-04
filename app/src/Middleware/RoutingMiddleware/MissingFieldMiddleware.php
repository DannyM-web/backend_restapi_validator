<?php

namespace Acme\Middleware\RoutingMiddleware;

use Acme\Exception\Validation\MissingValidateFieldException;

class MissingFieldMiddleware
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

    private static $contactFieldsFromRequest = ['name', 'surname', 'email', 'phone', 'property_id'];
    private static $contactFields = ['name', 'surname', 'email', 'phone'];
    private static $evaluateFields = ['typology', 'surface', 'floor', 'condition', 'address', 'latitude', 'longitude'];

    public function __invoke($request, $response, $next)
    {
        $data = $request->getParsedBody();
        $contactData = $data['contact'];
        $evaluateData = $data['evaluate'];

        $path  = $request->getUri()->getPath();
        $path_fmt = explode('lead/', $path)[1];

        if ($path_fmt === 'request') {
            foreach (self::$contactFieldsFromRequest as $contactFieldFromRequest) {

                if (!array_key_exists($contactFieldFromRequest, $contactData)) {
                    throw new MissingValidateFieldException($contactFieldFromRequest);
                }
                $response = $next($request, $response);
                return $response;
            }
        }

        foreach (self::$contactFields as $contactField) {

            if (!array_key_exists($contactField, $contactData)) {
                throw new MissingValidateFieldException($contactField);
            }
        }
        foreach (self::$evaluateFields as $evaluateField) {
            if (!array_key_exists($evaluateField, $evaluateData)) {
                throw new MissingValidateFieldException($evaluateField);
            }
        }

        $response = $next($request, $response);

        return $response;
    }
}
